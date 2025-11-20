<?php

namespace App\Http\Controllers;

use App\Models\Roadmap;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoadmapController extends Controller
{
    /**
     * Display the roadmap status management page.
     */
    public function index()
    {
        $statuses = Roadmap::ordered()->get();
        return view('roadmap.index', compact('statuses'));
    }

    /**
     * Store a new roadmap status.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:40|unique:roadmaps,name',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
        ]);

        // Get the highest sort_order and add 1
        $maxSortOrder = Roadmap::max('sort_order') ?? 0;
        $validated['sort_order'] = $maxSortOrder + 1;
        $validated['is_active'] = $request->has('is_active') ? $request->is_active : true;

        Roadmap::create($validated);

        return redirect()->route('roadmap.index')
            ->with('success', 'Status created successfully!');
    }

    /**
     * Update the specified roadmap status.
     */
    public function update(Request $request, Roadmap $roadmap)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:40',
                Rule::unique('roadmaps', 'name')->ignore($roadmap->id),
            ],
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->is_active : false;

        $roadmap->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
        ]);
    }

    /**
     * Remove the specified roadmap status.
     */
    public function destroy(Roadmap $roadmap)
    {
        $roadmap->delete();

        return response()->json([
            'success' => true,
            'message' => 'Status deleted successfully!',
        ]);
    }

    /**
     * Update the sort order of statuses.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'statuses' => 'required|array',
            'statuses.*.id' => 'required|exists:roadmaps,id',
            'statuses.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['statuses'] as $status) {
            Roadmap::where('id', $status['id'])->update(['sort_order' => $status['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status order updated successfully!',
        ]);
    }

    /**
     * Bulk update all statuses.
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'statuses' => 'required|array',
            'statuses.*.name' => 'required|string|max:40',
            'statuses.*.color' => 'required|string|max:7',
            'statuses.*.is_active' => 'required|boolean',
        ]);

        try {
            $sortOrder = 0;
            $processedIds = [];
            $processedNames = [];

            foreach ($validated['statuses'] as $statusData) {
                // Check for duplicate names
                if (in_array($statusData['name'], $processedNames)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Duplicate status name: ' . $statusData['name'],
                    ], 422);
                }
                $processedNames[] = $statusData['name'];

                // Determine if this is an update or create
                $isUpdate = isset($statusData['id']) && !str_starts_with($statusData['id'], 'new-');

                if ($isUpdate) {
                    // Update existing status by ID
                    $updated = Roadmap::where('id', $statusData['id'])->update([
                        'name' => $statusData['name'],
                        'color' => $statusData['color'],
                        'is_active' => $statusData['is_active'],
                        'sort_order' => $sortOrder++,
                    ]);

                    if ($updated) {
                        $processedIds[] = $statusData['id'];
                    }
                } else {
                    // Create new status
                    $newStatus = Roadmap::create([
                        'name' => $statusData['name'],
                        'color' => $statusData['color'],
                        'is_active' => $statusData['is_active'],
                        'sort_order' => $sortOrder++,
                    ]);

                    $processedIds[] = $newStatus->id;
                }
            }

            // Delete statuses that were not included in the update
            if (!empty($processedIds)) {
                Roadmap::whereNotIn('id', $processedIds)->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Statuses updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating statuses: ' . $e->getMessage(),
            ], 500);
        }
    }
}
