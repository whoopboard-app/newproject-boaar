<?php

namespace App\Http\Controllers;

use App\Models\Changelog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChangelogController extends Controller
{
    public function index()
    {
        $changelogs = Changelog::with('category')->latest()->get();
        return view('changelog.index', compact('changelogs'));
    }

    public function create()
    {
        // Only Owner, Admin, and Moderator can create changelog
        if (!Auth::user()->canManageChangelogAndKnowledge()) {
            return redirect()->route('changelog.index')
                ->with('error', 'You do not have permission to create changelogs.');
        }

        $categories = Category::where('status', 'active')->get();
        $changelog = null;
        return view('changelog.create', compact('categories', 'changelog'));
    }

    public function show(Changelog $changelog)
    {
        $changelog->load('category');
        return view('changelog.show', compact('changelog'));
    }

    public function edit(Changelog $changelog)
    {
        // Only Owner, Admin, and Moderator can edit changelog
        if (!Auth::user()->canEdit()) {
            return redirect()->route('changelog.index')
                ->with('error', 'You do not have permission to edit changelogs.');
        }

        $categories = Category::where('status', 'active')->get();
        return view('changelog.create', compact('categories', 'changelog'));
    }

    public function store(Request $request)
    {
        // Only Owner, Admin, and Moderator can create changelog
        if (!Auth::user()->canManageChangelogAndKnowledge()) {
            return redirect()->route('changelog.index')
                ->with('error', 'You do not have permission to create changelogs.');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_description' => 'required|string|max:200',
            'description' => 'required|string',
            'category' => 'required|array',
            'category.*' => 'exists:categories,id',
            'tags' => 'nullable|string',
            'author_name' => 'required|string|max:255',
            'published_date' => 'required|date',
            'status' => 'required|in:published,draft,scheduled',
        ]);

        try {
            // Handle cover image upload
            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $coverImagePath = $request->file('cover_image')->store('changelogs', 'public');
            }

            // Convert tags string to array
            $tags = [];
            if ($request->filled('tags')) {
                $tagsArray = json_decode($request->tags, true);
                if (is_array($tagsArray)) {
                    $tags = array_map(function($tag) {
                        return is_array($tag) && isset($tag['value']) ? $tag['value'] : $tag;
                    }, $tagsArray);
                }
            }

            Changelog::create([
                'title' => $validated['title'],
                'cover_image' => $coverImagePath,
                'short_description' => $validated['short_description'],
                'description' => $validated['description'],
                'category_id' => $validated['category'][0], // Use first selected category
                'tags' => $tags,
                'author_name' => $validated['author_name'],
                'published_date' => $validated['published_date'],
                'status' => $validated['status'],
            ]);

            return redirect()->route('changelog.index')
                ->with('success', 'Changelog created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('changelog.create')
                ->with('error', 'Failed to create changelog. Please try again. Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Changelog $changelog)
    {
        // Only Owner, Admin, and Moderator can edit changelog
        if (!Auth::user()->canEdit()) {
            return redirect()->route('changelog.index')
                ->with('error', 'You do not have permission to edit changelogs.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_description' => 'required|string|max:200',
            'description' => 'required|string',
            'category' => 'required|array',
            'category.*' => 'exists:categories,id',
            'tags' => 'nullable|string',
            'author_name' => 'required|string|max:255',
            'published_date' => 'required|date',
            'status' => 'required|in:published,draft,scheduled',
        ]);

        try {
            // Handle cover image upload
            $coverImagePath = $changelog->cover_image;
            if ($request->hasFile('cover_image')) {
                // Delete old image
                if ($changelog->cover_image) {
                    Storage::disk('public')->delete($changelog->cover_image);
                }
                $coverImagePath = $request->file('cover_image')->store('changelogs', 'public');
            }

            // Convert tags string to array
            $tags = [];
            if ($request->filled('tags')) {
                $tagsArray = json_decode($request->tags, true);
                if (is_array($tagsArray)) {
                    $tags = array_map(function($tag) {
                        return is_array($tag) && isset($tag['value']) ? $tag['value'] : $tag;
                    }, $tagsArray);
                }
            }

            $changelog->update([
                'title' => $validated['title'],
                'cover_image' => $coverImagePath,
                'short_description' => $validated['short_description'],
                'description' => $validated['description'],
                'category_id' => $validated['category'][0], // Use first selected category
                'tags' => $tags,
                'author_name' => $validated['author_name'],
                'published_date' => $validated['published_date'],
                'status' => $validated['status'],
            ]);

            return redirect()->route('changelog.index')
                ->with('success', 'Changelog updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update changelog. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Changelog $changelog)
    {
        // Only Owner and Admin can delete changelog (not Moderator)
        if (!Auth::user()->canDelete()) {
            return redirect()->route('changelog.index')
                ->with('error', 'You do not have permission to delete changelogs.');
        }

        try {
            // Delete cover image
            if ($changelog->cover_image) {
                Storage::disk('public')->delete($changelog->cover_image);
            }

            $changelog->delete();

            return redirect()->route('changelog.index')
                ->with('success', 'Changelog deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('changelog.index')
                ->with('error', 'Failed to delete changelog. Please try again.');
        }
    }
}
