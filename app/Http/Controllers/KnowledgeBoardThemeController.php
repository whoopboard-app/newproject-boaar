<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBoard;
use App\Models\KnowledgeBoardTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KnowledgeBoardThemeController extends Controller
{
    /**
     * Display themes listing page with Default and Custom cards.
     */
    public function index()
    {
        // Only Owner, Admin, and Moderator can access
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access theme settings.');
        }

        $teamId = Auth::user()->current_team_id;

        $knowledgeBoards = KnowledgeBoard::where('team_id', $teamId)
            ->with('theme')
            ->orderBy('name')
            ->get();

        return view('settings.themes.index', compact('knowledgeBoards'));
    }

    /**
     * Show theme editor - handles both "default" and "custom" views.
     */
    public function edit($knowledgeBoard)
    {
        // Only Owner, Admin, and Moderator can access
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access theme settings.');
        }

        $teamId = Auth::user()->current_team_id;

        // Get all knowledge boards for the dropdown
        $knowledgeBoards = KnowledgeBoard::where('team_id', $teamId)
            ->orderBy('name')
            ->get();

        // Check if viewing default or custom theme
        $isDefault = ($knowledgeBoard === 'default');

        // Check if any custom theme exists
        $hasCustomTheme = KnowledgeBoardTheme::where('team_id', $teamId)
            ->where('theme_type', 'custom')
            ->exists();

        // For custom theme, get the first custom theme or create default values
        $theme = null;
        $applyTo = 'all';

        if (!$isDefault) {
            // Get the first custom theme if exists
            $customTheme = KnowledgeBoardTheme::where('team_id', $teamId)
                ->where('theme_type', 'custom')
                ->first();

            if ($customTheme) {
                $theme = $customTheme;
                $applyTo = $customTheme->knowledge_board_id;

                // Check if all KBs have the same custom theme
                $customCount = KnowledgeBoardTheme::where('team_id', $teamId)
                    ->where('theme_type', 'custom')
                    ->count();

                if ($customCount === $knowledgeBoards->count()) {
                    $applyTo = 'all';
                }
            } else {
                // Create a default theme object for the form
                $theme = new KnowledgeBoardTheme([
                    'header_background_color' => '#11939A',
                    'header_text_color' => '#FFFFFF',
                    'footer_background_color' => '#11939A',
                    'footer_text_color' => '#FFFFFF',
                ]);
            }
        } else {
            // Default theme - create default values object
            $theme = new KnowledgeBoardTheme([
                'header_background_color' => '#11939A',
                'header_text_color' => '#FFFFFF',
                'footer_background_color' => '#11939A',
                'footer_text_color' => '#FFFFFF',
            ]);
        }

        return view('settings.themes.edit', compact(
            'knowledgeBoards',
            'theme',
            'isDefault',
            'hasCustomTheme',
            'applyTo'
        ));
    }

    /**
     * Update theme settings - handles "apply_to" and "theme_type" selection.
     */
    public function update(Request $request, $knowledgeBoard)
    {
        // Only Owner, Admin, and Moderator can access
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access theme settings.');
        }

        $teamId = Auth::user()->current_team_id;

        $themeType = $request->input('theme_type', 'custom');
        $applyTo = $request->input('apply_to', 'all');

        // If switching to default theme, delete the custom themes
        if ($themeType === 'default') {
            if ($applyTo === 'all') {
                // Delete all custom themes for this team
                KnowledgeBoardTheme::where('team_id', $teamId)->delete();
                $message = 'Default theme applied to all Knowledge Boards!';
            } else {
                // Delete specific Knowledge Board theme
                KnowledgeBoardTheme::where('team_id', $teamId)
                    ->where('knowledge_board_id', $applyTo)
                    ->delete();
                $message = 'Default theme applied successfully!';
            }

            return redirect()->route('settings.themes.edit', ['knowledgeBoard' => 'custom'])
                ->with('success', $message);
        }

        // Custom theme - validate and save
        $validated = $request->validate([
            'apply_to' => 'required|string',
            'header_background_color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'header_text_color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'header_intro_text' => 'nullable|string|max:255',
            'header_short_description' => 'nullable|string|max:1000',
            'footer_background_color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'footer_text_color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'google_analytics_id' => 'nullable|string|max:50',
        ]);

        unset($validated['apply_to']);

        // Mark as custom theme and set as active
        $validated['theme_type'] = 'custom';
        $validated['is_active'] = true;

        // Determine which Knowledge Boards to apply the theme to
        if ($applyTo === 'all') {
            // Apply to all Knowledge Boards
            $knowledgeBoardIds = KnowledgeBoard::where('team_id', $teamId)->pluck('id');
        } else {
            // Apply to specific Knowledge Board
            $knowledgeBoardIds = collect([$applyTo]);
        }

        // Update or create theme for each selected Knowledge Board
        foreach ($knowledgeBoardIds as $kbId) {
            KnowledgeBoardTheme::updateOrCreate(
                [
                    'team_id' => $teamId,
                    'knowledge_board_id' => $kbId,
                ],
                $validated
            );
        }

        $message = $applyTo === 'all'
            ? 'Custom theme applied to all Knowledge Boards successfully!'
            : 'Custom theme updated successfully!';

        return redirect()->route('settings.themes.edit', ['knowledgeBoard' => 'custom'])
            ->with('success', $message);
    }

    /**
     * Reset theme to defaults.
     */
    public function reset(Request $request, $knowledgeBoard)
    {
        // Only Owner, Admin, and Moderator can access
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access theme settings.');
        }

        $teamId = Auth::user()->current_team_id;
        $applyTo = $request->input('apply_to', 'all');

        if ($applyTo === 'all') {
            // Reset all themes for this team
            KnowledgeBoardTheme::where('team_id', $teamId)->delete();
        } else {
            // Reset specific Knowledge Board theme
            KnowledgeBoardTheme::where('team_id', $teamId)
                ->where('knowledge_board_id', $applyTo)
                ->delete();
        }

        $message = $applyTo === 'all'
            ? 'All themes reset to defaults successfully!'
            : 'Theme reset to default successfully!';

        return redirect()->route('settings.themes.edit', ['knowledgeBoard' => 'custom'])
            ->with('success', $message);
    }

    /**
     * Select/activate a theme (default or custom) without deleting.
     */
    public function selectTheme($theme)
    {
        // Only Owner, Admin, and Moderator can access
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access theme settings.');
        }

        $teamId = Auth::user()->current_team_id;

        if ($theme === 'default') {
            // Deactivate all custom themes (set is_active to false)
            KnowledgeBoardTheme::where('team_id', $teamId)
                ->update(['is_active' => false]);
            $message = 'Default theme is now active!';
        } else {
            // Activate custom themes (set is_active to true)
            KnowledgeBoardTheme::where('team_id', $teamId)
                ->where('theme_type', 'custom')
                ->update(['is_active' => true]);
            $message = 'Custom theme is now active!';
        }

        return redirect()->route('settings.themes')
            ->with('success', $message);
    }
}
