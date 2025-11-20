<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBoard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KnowledgeBoardController extends Controller
{
    public function index()
    {
        $boards = KnowledgeBoard::with('boardOwner')->latest()->get();
        return view('knowledge-board.index', compact('boards'));
    }

    public function create()
    {
        // Only Owner, Admin, and Moderator can create knowledge board
        if (!Auth::user()->canManageChangelogAndKnowledge()) {
            return redirect()->route('knowledge-board.index')
                ->with('error', 'You do not have permission to create knowledge boards.');
        }

        $teamMembers = User::all();
        return view('knowledge-board.create', compact('teamMembers'));
    }

    public function show(KnowledgeBoard $knowledgeBoard)
    {
        $knowledgeBoard->load('boardOwner');

        // Load categories with hierarchy
        $categories = \App\Models\BoardCategory::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('is_parent', true)
            ->with(['childCategories' => function($query) {
                $query->with('childCategories')->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        // Load articles with their relationships
        $articles = \App\Models\BoardArticle::where('knowledge_board_id', $knowledgeBoard->id)
            ->with(['boardCategory', 'author', 'changelogCategories'])
            ->latest()
            ->get();

        return view('knowledge-board.show', compact('knowledgeBoard', 'categories', 'articles'));
    }

    public function store(Request $request)
    {
        // Only Owner, Admin, and Moderator can create knowledge board
        if (!Auth::user()->canManageChangelogAndKnowledge()) {
            return redirect()->route('knowledge-board.index')
                ->with('error', 'You do not have permission to create knowledge boards.');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string',
            'cover_page' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'document_type' => 'required|in:manual,help_document',
            'visibility_type' => 'required|in:private,public',
            'status' => 'required|in:published,unpublished,draft',
            'board_owner_id' => 'required|exists:users,id',
            'has_public_url' => 'nullable|boolean',
        ]);

        try {
            // Handle cover page upload
            $coverPagePath = null;
            if ($request->hasFile('cover_page')) {
                $coverPagePath = $request->file('cover_page')->store('knowledge-boards', 'public');
            }

            KnowledgeBoard::create([
                'name' => $validated['name'],
                'short_description' => $validated['short_description'],
                'cover_page' => $coverPagePath,
                'document_type' => $validated['document_type'],
                'visibility_type' => $validated['visibility_type'],
                'status' => $validated['status'],
                'board_owner_id' => $validated['board_owner_id'],
                'has_public_url' => $request->has('has_public_url') ? true : false,
            ]);

            return redirect()->route('knowledge-board.index')
                ->with('success', 'Knowledge Board created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('knowledge-board.create')
                ->with('error', 'Failed to create knowledge board. Please try again. Error: ' . $e->getMessage());
        }
    }
}
