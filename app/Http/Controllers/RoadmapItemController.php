<?php

namespace App\Http\Controllers;

use App\Models\RoadmapItem;
use App\Models\RoadmapItemComment;
use App\Models\Roadmap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoadmapItemController extends Controller
{
    /**
     * Display a listing of roadmap items (List View).
     */
    public function index()
    {
        $roadmapItems = RoadmapItem::with(['feedback', 'roadmapStatus'])
            ->latest()
            ->paginate(20);

        return view('roadmap-items.index', compact('roadmapItems'));
    }

    /**
     * Display Kanban board view for roadmap items.
     */
    public function kanban()
    {
        // Get roadmap workflow statuses
        $roadmapStatuses = Roadmap::where('workflow_type', 'roadmap workflow')
            ->active()
            ->ordered()
            ->get();

        // Get roadmap items grouped by status
        $roadmapItems = RoadmapItem::with(['feedback', 'roadmapStatus'])
            ->whereIn('roadmap_status_id', $roadmapStatuses->pluck('id'))
            ->get()
            ->groupBy('roadmap_status_id');

        return view('roadmap-items.kanban', compact('roadmapItems', 'roadmapStatuses'));
    }

    /**
     * Show the form for creating a new roadmap item.
     */
    public function create()
    {
        $roadmapStatuses = Roadmap::where('workflow_type', 'roadmap workflow')
            ->active()
            ->ordered()
            ->get();

        return view('roadmap-items.create', compact('roadmapStatuses'));
    }

    /**
     * Store a newly created roadmap item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idea' => 'required|string',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'external_pm_tool_id' => 'nullable|string|max:255',
            'roadmap_status_id' => 'required|exists:roadmaps,id',
        ]);

        // Process tags
        if (!empty($validated['tags'])) {
            $tagsArray = array_map('trim', explode(',', $validated['tags']));
            $validated['tags'] = $tagsArray;
        } else {
            $validated['tags'] = [];
        }

        RoadmapItem::create($validated);

        return redirect()->route('roadmap-items.index')
            ->with('success', 'Roadmap item created successfully!');
    }

    /**
     * Display the specified roadmap item.
     */
    public function show(RoadmapItem $roadmapItem)
    {
        $roadmapItem->load(['feedback', 'roadmapStatus', 'comments.user']);

        return view('roadmap-items.show', compact('roadmapItem'));
    }

    /**
     * Show the form for editing the specified roadmap item.
     */
    public function edit(RoadmapItem $roadmapItem)
    {
        $roadmapStatuses = Roadmap::where('workflow_type', 'roadmap workflow')
            ->active()
            ->ordered()
            ->get();

        return view('roadmap-items.edit', compact('roadmapItem', 'roadmapStatuses'));
    }

    /**
     * Update the specified roadmap item.
     */
    public function update(Request $request, RoadmapItem $roadmapItem)
    {
        $validated = $request->validate([
            'idea' => 'required|string',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'external_pm_tool_id' => 'nullable|string|max:255',
            'roadmap_status_id' => 'required|exists:roadmaps,id',
        ]);

        // Process tags
        if (!empty($validated['tags'])) {
            $tagsArray = array_map('trim', explode(',', $validated['tags']));
            $validated['tags'] = $tagsArray;
        } else {
            $validated['tags'] = [];
        }

        $roadmapItem->update($validated);

        return redirect()->route('roadmap-items.show', $roadmapItem)
            ->with('success', 'Roadmap item updated successfully!');
    }

    /**
     * Update roadmap item status from Kanban board.
     */
    public function updateStatus(Request $request, RoadmapItem $roadmapItem)
    {
        $validated = $request->validate([
            'roadmap_status_id' => 'required|exists:roadmaps,id',
            'note' => 'nullable|string',
        ]);

        // Get old and new status names for the comment
        $oldStatus = $roadmapItem->roadmapStatus ? $roadmapItem->roadmapStatus->name : 'None';
        $newStatus = Roadmap::find($validated['roadmap_status_id'])->name;

        // Update the status
        $roadmapItem->update(['roadmap_status_id' => $validated['roadmap_status_id']]);

        // Add a comment about the status change
        $commentText = "Status changed from **{$oldStatus}** to **{$newStatus}**";

        if (!empty($validated['note'])) {
            $commentText .= "\n\nNote: " . $validated['note'];
        }

        $roadmapItem->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $commentText,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
        ]);
    }

    /**
     * Remove the specified roadmap item.
     */
    public function destroy(RoadmapItem $roadmapItem)
    {
        $roadmapItem->delete();

        return redirect()->route('roadmap-items.index')
            ->with('success', 'Roadmap item deleted successfully!');
    }

    /**
     * Add a comment to a roadmap item.
     */
    public function addComment(Request $request, RoadmapItem $roadmapItem)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();

        $roadmapItem->comments()->create($validated);

        return back()->with('success', 'Comment added successfully!');
    }

    /**
     * Delete a comment from a roadmap item.
     */
    public function deleteComment(RoadmapItemComment $comment)
    {
        // Only allow the comment owner or admin to delete
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin()) {
            return back()->with('error', 'You do not have permission to delete this comment.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}
