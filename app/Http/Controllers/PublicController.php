<?php

namespace App\Http\Controllers;

use App\Models\AppSettings;
use App\Models\Feedback;
use App\Models\FeedbackCategory;
use App\Models\Roadmap;
use App\Models\Changelog;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display public feedback board
     */
    public function home($uniqueUrl)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get active feedback categories for this team
        $categories = FeedbackCategory::where('team_id', $settings->team_id)
            ->where('is_active', true)
            ->withCount(['feedbacks' => function ($query) use ($settings) {
                $query->where('team_id', $settings->team_id)
                    ->where('is_public', true);
            }])
            ->orderBy('name')
            ->get();

        // Get active roadmap statuses for this team
        $roadmaps = Roadmap::where('team_id', $settings->team_id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get public feedbacks grouped by category
        $feedbacks = Feedback::with(['category', 'roadmap'])
            ->where('team_id', $settings->team_id)
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('feedback_category_id');

        return view('public.home', compact('settings', 'categories', 'roadmaps', 'feedbacks'));
    }

    /**
     * Display public roadmap
     */
    public function roadmap($uniqueUrl)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get active roadmap statuses for this team
        $roadmaps = Roadmap::where('team_id', $settings->team_id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get public feedbacks grouped by roadmap status
        $feedbacks = Feedback::with(['category', 'roadmap'])
            ->where('team_id', $settings->team_id)
            ->where('is_public', true)
            ->where('show_in_roadmap', true)
            ->whereIn('roadmap_id', $roadmaps->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('roadmap_id');

        return view('public.roadmap', compact('settings', 'roadmaps', 'feedbacks'));
    }

    /**
     * Display public changelog
     */
    public function changelog($uniqueUrl)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get active categories with changelog counts
        $categories = \App\Models\Category::where('team_id', $settings->team_id)
            ->where('status', 'active')
            ->withCount(['changelogs' => function ($query) use ($settings) {
                $query->where('team_id', $settings->team_id)
                    ->where('status', 'Published');
            }])
            ->orderBy('name')
            ->get();

        // Get published changelogs for this team
        $changelogs = Changelog::with('category')
            ->where('team_id', $settings->team_id)
            ->where('status', 'Published')
            ->orderBy('published_date', 'desc')
            ->paginate(10);

        return view('public.changelog', compact('settings', 'changelogs', 'categories'));
    }

    /**
     * Display single changelog
     */
    public function showChangelog($uniqueUrl, $changelogId)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get the specific changelog
        $changelog = Changelog::with('category')
            ->where('team_id', $settings->team_id)
            ->where('status', 'Published')
            ->where('id', $changelogId)
            ->firstOrFail();

        return view('public.changelog-detail', compact('settings', 'changelog'));
    }
}
