<?php

namespace App\Http\Controllers;

use App\Models\AppSettings;
use App\Models\RatingSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AppSettingsController extends Controller
{
    public function index()
    {
        // Only Owner, Admin, and Moderator can access app settings
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access app settings.');
        }

        return view('settings.index');
    }

    /**
     * Show general settings form
     */
    public function general()
    {
        // Only Owner, Admin, and Moderator can access app settings
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access app settings.');
        }

        $settings = AppSettings::where('team_id', Auth::user()->current_team_id)->first();

        return view('settings.general', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        // Only Owner, Admin, and Moderator can access app settings
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access app settings.');
        }

        $validated = $request->validate([
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,svg,webp,avif|mimetypes:image/jpeg,image/png,image/svg+xml,image/webp,image/avif|max:2048',
            'product_name' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'unique_url' => 'nullable|string|max:255|regex:/^[a-z0-9-]+$/|unique:app_settings,unique_url,' . Auth::user()->current_team_id . ',team_id',
            'subdomain_url' => 'nullable|string|max:63|regex:/^[a-z0-9-]+$/|unique:app_settings,subdomain_url,' . Auth::user()->current_team_id . ',team_id',
        ]);

        $settings = AppSettings::firstOrNew(['team_id' => Auth::user()->current_team_id]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($settings->logo && Storage::exists('public/' . $settings->logo)) {
                Storage::delete('public/' . $settings->logo);
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('logos', 'public');
            $settings->logo = $logoPath;
        }

        // Auto-generate unique_url if not provided
        if (empty($validated['unique_url'])) {
            $validated['unique_url'] = $this->generateUniqueUrl($validated['product_name']);
        }

        $settings->product_name = $validated['product_name'];
        $settings->website_url = $validated['website_url'];
        $settings->unique_url = $validated['unique_url'];
        $settings->subdomain_url = $validated['subdomain_url'] ?? null;
        $settings->team_id = Auth::user()->current_team_id;

        $settings->save();

        return redirect()->route('settings.general')
            ->with('success', 'General settings updated successfully!');
    }

    /**
     * Generate a unique URL slug from product name
     */
    private function generateUniqueUrl($productName)
    {
        $slug = strtolower(trim($productName));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/\s+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Check if slug is unique, if not append number
        $originalSlug = $slug;
        $counter = 1;

        while (AppSettings::where('unique_url', $slug)
            ->where('team_id', '!=', Auth::user()->current_team_id)
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Show rating settings form
     */
    public function rating()
    {
        // Only Owner, Admin, and Moderator can access app settings
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access app settings.');
        }

        $settings = RatingSettings::forTeam(Auth::user()->current_team_id);

        return view('settings.rating', compact('settings'));
    }

    /**
     * Update rating settings
     */
    public function updateRating(Request $request)
    {
        // Only Owner, Admin, and Moderator can access app settings
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access app settings.');
        }

        $validated = $request->validate([
            'question_text' => 'required|string|max:255',
            'rating_type' => 'required|in:yes_no,emoji,star,numeric,comment_only',
            'apply_to_changelog' => 'nullable|boolean',
            'apply_to_knowledge_board' => 'nullable|boolean',
        ]);

        $settings = RatingSettings::forTeam(Auth::user()->current_team_id);

        $settings->question_text = $validated['question_text'];
        $settings->rating_type = $validated['rating_type'];
        $settings->apply_to_changelog = $request->has('apply_to_changelog');
        $settings->apply_to_knowledge_board = $request->has('apply_to_knowledge_board');

        $settings->save();

        return redirect()->route('settings.rating')
            ->with('success', 'Rating settings updated successfully!');
    }
}
