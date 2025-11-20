<?php

namespace App\Http\Controllers;

use App\Models\BoardArticle;
use App\Models\KnowledgeBoard;
use App\Models\BoardCategory;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BoardArticleController extends Controller
{
    public function create(KnowledgeBoard $knowledgeBoard)
    {
        // Only Owner, Admin, and Moderator can create articles
        if (!Auth::user()->canManageChangelogAndKnowledge()) {
            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('error', 'You do not have permission to create articles.');
        }
        // Get all categories for this board (hierarchical)
        $boardCategories = BoardCategory::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('is_parent', true)
            ->with(['childCategories' => function($query) {
                $query->with('childCategories')->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        // Get all changelog categories
        $changelogCategories = Category::all();

        // Get all users (for author selection)
        $authors = User::all();

        $article = null;

        return view('board-article.create', compact(
            'knowledgeBoard',
            'boardCategories',
            'changelogCategories',
            'authors',
            'article'
        ));
    }

    public function show(KnowledgeBoard $knowledgeBoard, BoardArticle $article)
    {
        $article->load('boardCategory', 'author', 'changelogCategories');
        return view('board-article.show', compact('knowledgeBoard', 'article'));
    }

    public function edit(KnowledgeBoard $knowledgeBoard, BoardArticle $article)
    {
        // Only Owner, Admin, and Moderator can edit articles
        if (!Auth::user()->canEdit()) {
            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('error', 'You do not have permission to edit articles.');
        }

        // Get all categories for this board (hierarchical)
        $boardCategories = BoardCategory::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('is_parent', true)
            ->with(['childCategories' => function($query) {
                $query->with('childCategories')->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        // Get all changelog categories
        $changelogCategories = Category::all();

        // Get all users (for author selection)
        $authors = User::all();

        // Load relationships
        $article->load('changelogCategories');

        return view('board-article.create', compact(
            'knowledgeBoard',
            'boardCategories',
            'changelogCategories',
            'authors',
            'article'
        ));
    }

    public function store(Request $request, KnowledgeBoard $knowledgeBoard)
    {
        // Only Owner, Admin, and Moderator can create articles
        if (!Auth::user()->canManageChangelogAndKnowledge()) {
            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('error', 'You do not have permission to create articles.');
        }

        $validated = $request->validate([
            'article_title' => 'required|string|max:255',
            'board_category_id' => 'required|exists:board_categories,id',
            'detailed_post' => 'required|string',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
            'changelog_categories' => 'nullable|array',
            'changelog_categories.*' => 'exists:categories,id',
            'author_id' => 'required|exists:users,id',
            'display_as_popular' => 'nullable|boolean',
            'status' => 'required|in:published,unpublished,draft',
        ]);

        try {
            // Handle cover image upload
            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $coverImagePath = $request->file('cover_image')->store('board-articles', 'public');
            }

            // Parse tags from JSON string
            $tags = null;
            if ($request->filled('tags')) {
                $tagsData = json_decode($validated['tags'], true);
                if (is_array($tagsData)) {
                    $tags = array_map(function($tag) {
                        return is_array($tag) ? $tag['value'] : $tag;
                    }, $tagsData);
                }
            }

            // Create the article
            $article = BoardArticle::create([
                'knowledge_board_id' => $knowledgeBoard->id,
                'board_category_id' => $validated['board_category_id'],
                'article_title' => $validated['article_title'],
                'detailed_post' => $validated['detailed_post'],
                'cover_image' => $coverImagePath,
                'tags' => $tags,
                'author_id' => $validated['author_id'],
                'display_as_popular' => $request->has('display_as_popular') ? true : false,
                'status' => $validated['status'],
            ]);

            // Attach changelog categories
            if ($request->has('changelog_categories')) {
                $article->changelogCategories()->attach($validated['changelog_categories']);
            }

            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('success', 'Article created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create article. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, KnowledgeBoard $knowledgeBoard, BoardArticle $article)
    {
        // Only Owner, Admin, and Moderator can edit articles
        if (!Auth::user()->canEdit()) {
            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('error', 'You do not have permission to edit articles.');
        }

        $validated = $request->validate([
            'article_title' => 'required|string|max:255',
            'board_category_id' => 'required|exists:board_categories,id',
            'detailed_post' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
            'changelog_categories' => 'nullable|array',
            'changelog_categories.*' => 'exists:categories,id',
            'author_id' => 'required|exists:users,id',
            'display_as_popular' => 'nullable|boolean',
            'status' => 'required|in:published,unpublished,draft',
        ]);

        try {
            // Handle cover image upload
            $coverImagePath = $article->cover_image;
            if ($request->hasFile('cover_image')) {
                // Delete old image
                if ($article->cover_image) {
                    Storage::disk('public')->delete($article->cover_image);
                }
                $coverImagePath = $request->file('cover_image')->store('board-articles', 'public');
            }

            // Parse tags from JSON string
            $tags = null;
            if ($request->filled('tags')) {
                $tagsData = json_decode($validated['tags'], true);
                if (is_array($tagsData)) {
                    $tags = array_map(function($tag) {
                        return is_array($tag) ? $tag['value'] : $tag;
                    }, $tagsData);
                }
            }

            // Update the article
            $article->update([
                'board_category_id' => $validated['board_category_id'],
                'article_title' => $validated['article_title'],
                'detailed_post' => $validated['detailed_post'],
                'cover_image' => $coverImagePath,
                'tags' => $tags,
                'author_id' => $validated['author_id'],
                'display_as_popular' => $request->has('display_as_popular') ? true : false,
                'status' => $validated['status'],
            ]);

            // Sync changelog categories
            if ($request->has('changelog_categories')) {
                $article->changelogCategories()->sync($validated['changelog_categories']);
            } else {
                $article->changelogCategories()->detach();
            }

            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('success', 'Article updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update article. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(KnowledgeBoard $knowledgeBoard, BoardArticle $article)
    {
        // Only Owner and Admin can delete articles (not Moderator)
        if (!Auth::user()->canDelete()) {
            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('error', 'You do not have permission to delete articles.');
        }

        try {
            // Delete cover image
            if ($article->cover_image) {
                Storage::disk('public')->delete($article->cover_image);
            }

            // Delete the article (relationships will be automatically detached)
            $article->delete();

            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('success', 'Article deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('knowledge-board.show', $knowledgeBoard)
                ->with('error', 'Failed to delete article. Please try again.');
        }
    }
}
