<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FeedbackCategory;
use App\Models\FeedbackComment;
use App\Models\Persona;
use App\Models\Roadmap;
use Illuminate\Http\Request;

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
     * Show the form for creating a new feedback.
     */
    public function create()
    {
        $categories = FeedbackCategory::active()->ordered()->get();
        $statuses = Roadmap::active()->ordered()->get();
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
        $validated = $request->validate([
            'idea' => 'required|string',
            'feedback_category_id' => 'nullable|exists:feedback_categories,id',
            'value_description' => 'nullable|string',
            'roadmap_id' => 'nullable|exists:roadmaps,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'login_access_enabled' => 'boolean',
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

        Feedback::create($validated);

        return redirect()->route('feedback.index')
            ->with('success', 'Feedback created successfully!');
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
        $categories = FeedbackCategory::active()->ordered()->get();
        $statuses = Roadmap::active()->ordered()->get();
        $personas = Persona::all();
        $sources = [
            'Admin Added',
            'User Submitted',
            'Social Scraping',
            'Project Management tool',
            'Support System'
        ];

        // Convert tags array to comma-separated string for editing
        $feedback->tags_string = $feedback->tags ? implode(', ', $feedback->tags) : '';

        return view('feedback.edit', compact('feedback', 'categories', 'statuses', 'personas', 'sources'));
    }

    /**
     * Update the specified feedback.
     */
    public function update(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'idea' => 'required|string',
            'feedback_category_id' => 'nullable|exists:feedback_categories,id',
            'value_description' => 'nullable|string',
            'roadmap_id' => 'nullable|exists:roadmaps,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'login_access_enabled' => 'boolean',
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

        $feedback->update($validated);

        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Feedback updated successfully!');
    }

    /**
     * Remove the specified feedback.
     */
    public function destroy(Feedback $feedback)
    {
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
            'comment' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        $validated['feedback_id'] = $feedback->id;
        $validated['user_id'] = auth()->id();
        $validated['is_internal'] = $request->has('is_internal');

        FeedbackComment::create($validated);

        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Comment added successfully!');
    }

    /**
     * Delete a comment.
     */
    public function destroyComment(Feedback $feedback, FeedbackComment $comment)
    {
        if ($comment->feedback_id !== $feedback->id) {
            abort(404);
        }

        $comment->delete();

        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Comment deleted successfully!');
    }
}
