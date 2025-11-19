<?php

namespace App\Http\Controllers;

use App\Models\Changelog;
use App\Models\Category;
use Illuminate\Http\Request;
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
        $categories = Category::where('status', 'active')->get();
        return view('changelog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_description' => 'required|string|min:200|max:500',
            'description' => 'required|string',
            'category' => 'required|exists:categories,id',
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
                'category_id' => $validated['category'],
                'tags' => $tags,
                'author_name' => $validated['author_name'],
                'published_date' => $validated['published_date'],
                'status' => $validated['status'],
            ]);

            return redirect()->route('changelog.create')
                ->with('success', 'Changelog created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('changelog.create')
                ->with('error', 'Failed to create changelog. Please try again. Error: ' . $e->getMessage());
        }
    }
}
