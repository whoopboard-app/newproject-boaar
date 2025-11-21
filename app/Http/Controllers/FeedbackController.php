<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FeedbackCategory;
use App\Models\FeedbackComment;
use App\Models\Persona;
use App\Models\Roadmap;
use App\Models\User;
use App\Notifications\FeedbackLoginAccessNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;

class FeedbackController extends Controller
{
    /**
     * Display a listing of feedbacks.
     */
    public function index()
    {
        $feedbacks = Feedback::with(['category', 'roadmap', 'persona'])
            ->latest()
            ->paginate(20);

        return view('feedback.index', compact('feedbacks'));
    }

    /**
     * Display Kanban board view.
     */
    public function kanban()
    {
        $roadmaps = Roadmap::where('workflow_type', 'feedback workflow')->active()->ordered()->get();

        // Get feedbacks for current team and eager load relationships
        $feedbacks = Feedback::with(['category', 'roadmap', 'persona'])
            ->whereIn('roadmap_id', $roadmaps->pluck('id'))
            ->get()
            ->groupBy('roadmap_id');

        return view('feedback.kanban', compact('feedbacks', 'roadmaps'));
    }

    /**
     * Update feedback status from Kanban board.
     */
    public function updateStatus(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'roadmap_id' => 'required|exists:roadmaps,id',
            'note' => 'nullable|string|max:500',
        ]);

        $oldStatus = $feedback->roadmap ? $feedback->roadmap->name : 'No Status';
        $feedback->update(['roadmap_id' => $validated['roadmap_id']]);
        $newStatus = $feedback->roadmap->name;

        // Add comment if note is provided
        if (!empty($validated['note'])) {
            FeedbackComment::create([
                'feedback_id' => $feedback->id,
                'user_id' => auth()->id(),
                'comment' => "Status changed from '{$oldStatus}' to '{$newStatus}': {$validated['note']}",
                'is_internal' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Feedback status updated successfully',
        ]);
    }

    /**
     * Show the form for creating a new feedback.
     */
    public function create()
    {
        // Owner, Admin, Moderator, and Idea Submitter can create feedback
        if (!Auth::user()->canManageFeedback()) {
            return redirect()->route('feedback.index')
                ->with('error', 'You do not have permission to create feedback.');
        }
        $categories = FeedbackCategory::active()->ordered()->get();
        $statuses = Roadmap::where('workflow_type', 'feedback workflow')->active()->ordered()->get();
        $personas = Persona::all();
        $sources = [
            'Admin Added',
            'User Submitted',
            'Social Scraping',
            'Project Management tool',
            'Support System'
        ];

        return view('feedback.create', compact('categories', 'statuses', 'personas', 'sources'));
    }

    /**
     * Store a newly created feedback.
     */
    public function store(Request $request)
    {
        // Owner, Admin, Moderator, and Idea Submitter can create feedback
        if (!Auth::user()->canManageFeedback()) {
            return redirect()->route('feedback.index')
                ->with('error', 'You do not have permission to create feedback.');
        }

        $validated = $request->validate([
            'idea' => 'required|string',
            'feedback_category_id' => 'nullable|exists:feedback_categories,id',
            'value_description' => 'nullable|string',
            'roadmap_id' => 'nullable|exists:roadmaps,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'login_access_enabled' => 'boolean',
            'is_public' => 'required|boolean',
            'show_in_roadmap' => 'boolean',
            'tags' => 'nullable|string',
            'persona_id' => 'nullable|exists:personas,id',
            'source' => 'required|in:Admin Added,User Submitted,Social Scraping,Project Management tool,Support System',
        ]);

        // Process tags
        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = null;
        }

        $validated['login_access_enabled'] = $request->has('login_access_enabled');
        $validated['is_public'] = $request->input('is_public', true);
        $validated['show_in_roadmap'] = $request->has('show_in_roadmap');
        $validated['team_id'] = Auth::user()->current_team_id;

        $feedback = Feedback::create($validated);

        // Send email if login access is enabled
        if ($validated['login_access_enabled']) {
            // Check if user already exists
            $existingUser = User::where('email', $validated['email'])->first();

            if (!$existingUser) {
                // Generate temporary password
                $temporaryPassword = Str::random(12);

                // Create user account
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($temporaryPassword),
                    'current_team_id' => Auth::user()->current_team_id,
                    'email_verified_at' => now(), // Auto-verify email
                ]);

                // Attach user to team with 'idea_submitter' role
                $user->teams()->attach(Auth::user()->current_team_id, ['role' => 'idea_submitter']);

                // Send notification with temporary password
                Notification::route('mail', $validated['email'])
                    ->notify(new FeedbackLoginAccessNotification($feedback, $temporaryPassword));
            } else {
                // User exists, just send notification without password
                Notification::route('mail', $validated['email'])
                    ->notify(new FeedbackLoginAccessNotification($feedback));
            }
        }

        return redirect()->route('feedback.index')
            ->with('success', 'Feedback created successfully!' . ($validated['login_access_enabled'] ? ' Login access email sent.' : ''));
    }

    /**
     * Display the specified feedback with comments.
     */
    public function show(Feedback $feedback)
    {
        $feedback->load(['category', 'roadmap', 'persona', 'comments.user']);

        return view('feedback.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified feedback.
     */
    public function edit(Feedback $feedback)
    {
        // Owner, Admin, Moderator, and Idea Submitter can edit feedback
        if (!Auth::user()->canManageFeedback()) {
            return redirect()->route('feedback.index')
                ->with('error', 'You do not have permission to edit feedback.');
        }

        $categories = FeedbackCategory::active()->ordered()->get();
        $statuses = Roadmap::where('workflow_type', 'feedback workflow')->active()->ordered()->get();
        $personas = Persona::all();
        $sources = [
            'Admin Added',
            'User Submitted',
            'Social Scraping',
            'Project Management tool',
            'Support System'
        ];

        return view('feedback.create', compact('feedback', 'categories', 'statuses', 'personas', 'sources'));
    }

    /**
     * Update the specified feedback.
     */
    public function update(Request $request, Feedback $feedback)
    {
        // Owner, Admin, Moderator, and Idea Submitter can edit feedback
        if (!Auth::user()->canManageFeedback()) {
            return redirect()->route('feedback.index')
                ->with('error', 'You do not have permission to edit feedback.');
        }

        $validated = $request->validate([
            'idea' => 'required|string',
            'feedback_category_id' => 'nullable|exists:feedback_categories,id',
            'value_description' => 'nullable|string',
            'roadmap_id' => 'nullable|exists:roadmaps,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'login_access_enabled' => 'boolean',
            'is_public' => 'required|boolean',
            'show_in_roadmap' => 'boolean',
            'tags' => 'nullable|string',
            'persona_id' => 'nullable|exists:personas,id',
            'source' => 'required|in:Admin Added,User Submitted,Social Scraping,Project Management tool,Support System',
        ]);

        // Process tags
        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = null;
        }

        $validated['login_access_enabled'] = $request->has('login_access_enabled');
        $validated['is_public'] = $request->input('is_public', true);
        $validated['show_in_roadmap'] = $request->has('show_in_roadmap');

        $feedback->update($validated);

        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Feedback updated successfully!');
    }

    /**
     * Remove the specified feedback.
     */
    public function destroy(Feedback $feedback)
    {
        // Only Owner and Admin can delete feedback (not Moderator or Idea Submitter)
        if (!Auth::user()->canDelete()) {
            return redirect()->route('feedback.index')
                ->with('error', 'You do not have permission to delete feedback.');
        }

        $feedback->delete();

        return redirect()->route('feedback.index')
            ->with('success', 'Feedback deleted successfully!');
    }

    /**
     * Store a new comment on a feedback.
     */
    public function storeComment(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        FeedbackComment::create([
            'feedback_id' => $feedback->id,
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
            'is_internal' => false,
        ]);

        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Comment added successfully!');
    }

    /**
     * Delete a comment.
     */
    public function destroyComment(Feedback $feedback, FeedbackComment $comment)
    {
        // Verify the comment belongs to this feedback
        if ($comment->feedback_id !== $feedback->id) {
            abort(404);
        }

        // Only allow the comment author to delete
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Comment deleted successfully!');
    }
}
