<?php

namespace App\Http\Controllers;

use App\Models\FeedbackCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeedbackCategoryController extends Controller
{
    /**
     * Display the feedback category management page.
     */
    public function index()
    {
        $categories = FeedbackCategory::ordered()->get();
        return view('feedback-category.index', compact('categories'));
    }

    /**
     * Store a new feedback category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:60|unique:feedback_categories,name',
            'is_active' => 'boolean',
        ]);

        // Auto-generate color from palette
        $colorPalette = [
            '#EF4444', '#F97316', '#F59E0B', '#EAB308', '#84CC16',
            '#22C55E', '#10B981', '#14B8A6', '#06B6D4', '#0EA5E9',
            '#3B82F6', '#6366F1', '#8B5CF6', '#A855F7', '#D946EF',
            '#EC4899', '#F43F5E', '#64748B', '#6B7280', '#78716C'
        ];
        $validated['color'] = $colorPalette[array_rand($colorPalette)];

        // Get the highest sort_order and add 1
        $maxSortOrder = FeedbackCategory::max('sort_order') ?? 0;
        $validated['sort_order'] = $maxSortOrder + 1;
        $validated['is_active'] = $request->is_active ?? true;

        FeedbackCategory::create($validated);

        return redirect()->route('feedback-category.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Update the specified feedback category.
     */
    public function update(Request $request, FeedbackCategory $feedbackCategory)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:60',
                Rule::unique('feedback_categories', 'name')->ignore($feedbackCategory->id),
            ],
            'is_active' => 'required|boolean',
        ]);

        $feedbackCategory->update($validated);

        return redirect()->route('feedback-category.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified feedback category.
     */
    public function destroy(FeedbackCategory $feedbackCategory)
    {
        $feedbackCategory->delete();

        return redirect()->route('feedback-category.index')
            ->with('success', 'Category deleted successfully!');
    }

    /**
     * Update the sort order of categories.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:feedback_categories,id',
            'categories.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['categories'] as $category) {
            FeedbackCategory::where('id', $category['id'])->update(['sort_order' => $category['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category order updated successfully!',
        ]);
    }

    /**
     * Bulk update all categories.
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*.name' => 'required|string|max:60',
            'categories.*.color' => 'required|string|max:7',
            'categories.*.is_active' => 'required|boolean',
        ]);

        try {
            $sortOrder = 0;
            $processedIds = [];
            $processedNames = [];

            foreach ($validated['categories'] as $categoryData) {
                // Check for duplicate names
                if (in_array($categoryData['name'], $processedNames)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Duplicate category name: ' . $categoryData['name'],
                    ], 422);
                }
                $processedNames[] = $categoryData['name'];

                // Determine if this is an update or create
                $isUpdate = isset($categoryData['id']) && !str_starts_with($categoryData['id'], 'new-');

                if ($isUpdate) {
                    // Update existing category by ID
                    $updated = FeedbackCategory::where('id', $categoryData['id'])->update([
                        'name' => $categoryData['name'],
                        'color' => $categoryData['color'],
                        'is_active' => $categoryData['is_active'],
                        'sort_order' => $sortOrder++,
                    ]);

                    if ($updated) {
                        $processedIds[] = $categoryData['id'];
                    }
                } else {
                    // Create new category
                    $newCategory = FeedbackCategory::create([
                        'name' => $categoryData['name'],
                        'color' => $categoryData['color'],
                        'is_active' => $categoryData['is_active'],
                        'sort_order' => $sortOrder++,
                    ]);

                    $processedIds[] = $newCategory->id;
                }
            }

            // Delete categories that were not included in the update
            if (!empty($processedIds)) {
                FeedbackCategory::whereNotIn('id', $processedIds)->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Categories updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating categories: ' . $e->getMessage(),
            ], 500);
        }
    }
}
