<?php

namespace App\Http\Controllers;

use App\Models\BoardCategory;
use App\Models\KnowledgeBoard;
use Illuminate\Http\Request;

class BoardCategoryController extends Controller
{
    public function create(KnowledgeBoard $knowledgeBoard)
    {
        $parentCategories = BoardCategory::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('is_parent', true)
            ->with(['childCategories' => function($query) {
                $query->with('childCategories')->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return view('board-category.create', compact('knowledgeBoard', 'parentCategories'));
    }

    public function store(Request $request, KnowledgeBoard $knowledgeBoard)
    {
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
}
