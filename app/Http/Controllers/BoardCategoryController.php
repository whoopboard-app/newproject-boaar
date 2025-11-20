<?php

namespace App\Http\Controllers;

use App\Models\BoardCategory;
use App\Models\KnowledgeBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardCategoryController extends Controller
{
    public function create(KnowledgeBoard $knowledgeBoard)
    {
        // Only Owner, Admin, and Moderator can create categories
        if (!Auth::user()->canManageChangelogAndKnowledge()) {
            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('error', 'You do not have permission to create categories.');
        }
        $parentCategories = BoardCategory::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('is_parent', true)
            ->with(['childCategories' => function($query) {
                $query->with('childCategories')->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        $category = null;
        return view('board-category.create', compact('knowledgeBoard', 'parentCategories', 'category'));
    }

    public function show(KnowledgeBoard $knowledgeBoard, BoardCategory $category)
    {
        $category->load('parentCategory', 'childCategories');
        return view('board-category.show', compact('knowledgeBoard', 'category'));
    }

    public function edit(KnowledgeBoard $knowledgeBoard, BoardCategory $category)
    {
        // Only Owner, Admin, and Moderator can edit categories
        if (!Auth::user()->canEdit()) {
            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('error', 'You do not have permission to edit categories.');
        }

        $parentCategories = BoardCategory::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('is_parent', true)
            ->where('id', '!=', $category->id)
            ->with(['childCategories' => function($query) use ($category) {
                $query->where('id', '!=', $category->id)->with('childCategories')->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return view('board-category.create', compact('knowledgeBoard', 'parentCategories', 'category'));
    }

    public function store(Request $request, KnowledgeBoard $knowledgeBoard)
    {
        // Only Owner, Admin, and Moderator can create categories
        if (!Auth::user()->canManageChangelogAndKnowledge()) {
            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('error', 'You do not have permission to create categories.');
        }

        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_icon' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'is_parent' => 'required|boolean',
            'parent_category_id' => 'nullable|exists:board_categories,id',
            'order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            BoardCategory::create([
                'knowledge_board_id' => $knowledgeBoard->id,
                'category_name' => $validated['category_name'],
                'category_icon' => $validated['category_icon'],
                'short_description' => $validated['short_description'],
                'is_parent' => $validated['is_parent'],
                'parent_category_id' => $validated['is_parent'] ? null : $validated['parent_category_id'],
                'order' => $validated['order'],
                'status' => $validated['status'],
            ]);

            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create category. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, KnowledgeBoard $knowledgeBoard, BoardCategory $category)
    {
        // Only Owner, Admin, and Moderator can edit categories
        if (!Auth::user()->canEdit()) {
            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('error', 'You do not have permission to edit categories.');
        }

        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_icon' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'is_parent' => 'required|boolean',
            'parent_category_id' => 'nullable|exists:board_categories,id',
            'order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $category->update([
                'category_name' => $validated['category_name'],
                'category_icon' => $validated['category_icon'],
                'short_description' => $validated['short_description'],
                'is_parent' => $validated['is_parent'],
                'parent_category_id' => $validated['is_parent'] ? null : $validated['parent_category_id'],
                'order' => $validated['order'],
                'status' => $validated['status'],
            ]);

            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update category. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(KnowledgeBoard $knowledgeBoard, BoardCategory $category)
    {
        // Only Owner and Admin can delete categories (not Moderator)
        if (!Auth::user()->canDelete()) {
            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('error', 'You do not have permission to delete categories.');
        }

        try {
            $category->delete();

            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('error', 'Failed to delete category. Please try again.');
        }
    }
}
