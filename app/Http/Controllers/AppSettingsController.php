<?php

namespace App\Http\Controllers;

use App\Models\AppSettings;
use App\Models\RatingSettings;
use App\Models\SiteAccessInvite;
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

        $settings = AppSettings::firstOrNew(['team_id' => Auth::user()->current_team_id]);
        $siteAccessInvites = SiteAccessInvite::where('team_id', Auth::user()->current_team_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('settings.general', compact('settings', 'siteAccessInvites'));
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
            'subdomain_url' => 'required|string|max:63|regex:/^[a-z0-9-]+$/|unique:app_settings,subdomain_url,' . Auth::user()->current_team_id . ',team_id',
            'block_search_indexing' => 'nullable|boolean',
            'site_visibility' => 'nullable|in:public,private',
            'maintenance_mode' => 'nullable|boolean',
            'maintenance_scheduled_at' => 'nullable|date',
            'maintenance_ends_at' => 'nullable|date|after_or_equal:maintenance_scheduled_at',
            'maintenance_message' => 'nullable|string|max:500',
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

        // Auto-generate unique_url from subdomain for backwards compatibility
        $settings->unique_url = $validated['subdomain_url'];

        $settings->product_name = $validated['product_name'];
        $settings->website_url = $validated['website_url'];
        $settings->subdomain_url = $validated['subdomain_url'];
        $settings->block_search_indexing = $request->has('block_search_indexing');
        $settings->site_visibility = $validated['site_visibility'] ?? 'public';
        $settings->maintenance_mode = $request->has('maintenance_mode');
        $settings->maintenance_scheduled_at = $validated['maintenance_scheduled_at'] ?? null;
        $settings->maintenance_ends_at = $validated['maintenance_ends_at'] ?? null;
        $settings->maintenance_message = $validated['maintenance_message'] ?? null;
        $settings->team_id = Auth::user()->current_team_id;

        $settings->save();

        return redirect()->route('settings.general')
            ->with('success', 'General settings updated successfully!');
    }

    /**
     * Add a site access invite
     */
    public function addSiteAccessInvite(Request $request)
    {
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access app settings.');
        }

        $validated = $request->validate([
            'emails' => 'required|string',
        ]);

        $emails = array_filter(array_map('trim', preg_split('/[,\n]+/', $validated['emails'])));
        $teamId = Auth::user()->current_team_id;
        $added = 0;
        $skipped = 0;

        foreach ($emails as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }

            $existing = SiteAccessInvite::where('team_id', $teamId)
                ->where('email', strtolower($email))
                ->first();

            if ($existing) {
                $skipped++;
                continue;
            }

            SiteAccessInvite::create([
                'team_id' => $teamId,
                'email' => strtolower($email),
            ]);
            $added++;
        }

        $message = "{$added} email(s) added to access list.";
        if ($skipped > 0) {
            $message .= " {$skipped} skipped (invalid or already exists).";
        }

        return redirect()->route('settings.general')
            ->with('success', $message);
    }

    /**
     * Remove a site access invite
     */
    public function removeSiteAccessInvite(SiteAccessInvite $invite)
    {
        if (!Auth::user()->canAccessAppSettings()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access app settings.');
        }

        if ($invite->team_id !== Auth::user()->current_team_id) {
            return redirect()->route('settings.general')
                ->with('error', 'Access denied.');
        }

        $invite->delete();

        return redirect()->route('settings.general')
            ->with('success', 'Access invite removed successfully.');
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
