<?php

namespace App\Http\Controllers;

use App\Models\UserSegment;
use Illuminate\Http\Request;

class UserSegmentController extends Controller
{
    public function index()
    {
        $segments = UserSegment::latest()->get();
        return view('user-segment.index', compact('segments'));
    }

    public function create()
    {
        $segment = null;
        return view('user-segment.create', compact('segment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'revenue_ranges' => 'nullable|string',
            'locations' => 'nullable|string',
            'age_ranges' => 'nullable|string',
            'genders' => 'nullable|string',
            'languages' => 'nullable|string',
            'user_types' => 'nullable|string',
            'plan_types' => 'nullable|string',
            'engagement_levels' => 'nullable|string',
            'usage_frequencies' => 'nullable|string',
        ]);

        try {
            // Parse all tag fields from JSON strings
            $tagFields = [
                'revenue_ranges', 'locations', 'age_ranges', 'genders',
                'languages', 'user_types', 'plan_types', 'engagement_levels',
                'usage_frequencies'
            ];

            foreach ($tagFields as $field) {
                if ($request->filled($field)) {
                    $tagsData = json_decode($validated[$field], true);
                    if (is_array($tagsData)) {
                        $validated[$field] = array_map(function($tag) {
                            return is_array($tag) ? $tag['value'] : $tag;
                        }, $tagsData);
                    }
                } else {
                    $validated[$field] = null;
                }
            }

            UserSegment::create($validated);

            return redirect()->route('user-segment.index')
                ->with('success', 'User segment created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create user segment. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(UserSegment $userSegment)
    {
        return view('user-segment.show', compact('userSegment'));
    }

    public function edit(UserSegment $userSegment)
    {
        $segment = $userSegment;
        return view('user-segment.create', compact('segment'));
    }

    public function update(Request $request, UserSegment $userSegment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'revenue_ranges' => 'nullable|string',
            'locations' => 'nullable|string',
            'age_ranges' => 'nullable|string',
            'genders' => 'nullable|string',
            'languages' => 'nullable|string',
            'user_types' => 'nullable|string',
            'plan_types' => 'nullable|string',
            'engagement_levels' => 'nullable|string',
            'usage_frequencies' => 'nullable|string',
        ]);

        try {
            // Parse all tag fields from JSON strings
            $tagFields = [
                'revenue_ranges', 'locations', 'age_ranges', 'genders',
                'languages', 'user_types', 'plan_types', 'engagement_levels',
                'usage_frequencies'
            ];

            foreach ($tagFields as $field) {
                if ($request->filled($field)) {
                    $tagsData = json_decode($validated[$field], true);
                    if (is_array($tagsData)) {
                        $validated[$field] = array_map(function($tag) {
                            return is_array($tag) ? $tag['value'] : $tag;
                        }, $tagsData);
                    }
                } else {
                    $validated[$field] = null;
                }
            }

            $userSegment->update($validated);

            return redirect()->route('user-segment.index')
                ->with('success', 'User segment updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update user segment. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(UserSegment $userSegment)
    {
        try {
            $userSegment->delete();

            return redirect()->route('user-segment.index')
                ->with('success', 'User segment deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('user-segment.index')
                ->with('error', 'Failed to delete user segment. Please try again.');
        }
    }
}
