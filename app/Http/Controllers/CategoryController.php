<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('categories.manage', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $teamId = auth()->user()->current_team_id;

        $validated = $request->validate([
            'category_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->where('team_id', $teamId),
            ],
            'status' => 'required|in:active,inactive',
        ], [
            'category_name.required' => 'Category name is required.',
            'category_name.unique' => 'This category name already exists.',
            'status.required' => 'Status is required.',
        ]);
        try {
            Category::create([
                'name' => $validated['category_name'],
                'status' => $validated['status'],
            ]);

            return redirect()->route('categories.manage')
                ->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('categories.manage')
                ->with('error', 'Failed to create category. Please try again.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $teamId = auth()->user()->current_team_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->where('team_id', $teamId)->ignore($category->id),
            ],
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $category->update($validated);

            return redirect()->route('categories.manage')
                ->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('categories.manage')
                ->with('error', 'Failed to update category. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect()->route('categories.manage')
                ->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('categories.manage')
                ->with('error', 'Failed to delete category. Please try again.');
        }
    }
}
