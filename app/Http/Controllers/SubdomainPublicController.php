<?php

namespace App\Http\Controllers;

use App\Models\AppSettings;
use App\Models\Feedback;
use App\Models\FeedbackCategory;
use App\Models\FeedbackSettings;
use App\Models\PublicUser;
use App\Models\Roadmap;
use App\Models\RoadmapItem;
use App\Models\Changelog;
use App\Models\Category;
use App\Models\Testimonial;
use App\Models\KnowledgeBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SubdomainPublicController extends Controller
{
    /**
     * Get team settings from request attributes (set by SubdomainRouting middleware).
     */
    protected function getTeamSettings(Request $request): ?AppSettings
    {
        return $request->attributes->get('team_settings');
    }

    /**
     * Ensure we have team settings, abort if not.
     */
    protected function requireTeamSettings(Request $request): AppSettings
    {
        $settings = $this->getTeamSettings($request);

        if (!$settings) {
            abort(404, 'Team not found');
        }

        return $settings;
    }

    /**
     * Display public home page for subdomain.
     */
    public function home(Request $request)
    {
        $settings = $this->requireTeamSettings($request);

        // Get feedback settings for this team
        $feedbackSettings = FeedbackSettings::forTeam($settings->team_id);

        // Check if user is logged in
        $isLoggedIn = Session::has('public_user_id');
        $publicUser = $isLoggedIn ? PublicUser::find(Session::get('public_user_id')) : null;

        // Get active feedback categories for this team
        $categories = FeedbackCategory::where('team_id', $settings->team_id)
            ->where('is_active', true)
            ->withCount(['feedbacks' => function ($query) use ($settings) {
                $query->where('team_id', $settings->team_id)
                    ->where('is_public', true)
                    ->where('is_verified', true);
            }])
            ->orderBy('name')
            ->get();

        // Get active roadmap statuses for this team
        $roadmaps = Roadmap::where('team_id', $settings->team_id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get public feedbacks grouped by category (only verified ones)
        $feedbacks = Feedback::with(['category', 'roadmap', 'votes'])
            ->where('team_id', $settings->team_id)
            ->where('is_public', true)
            ->where('is_verified', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('feedback_category_id');

        return view('public.home', compact(
            'settings',
            'categories',
            'roadmaps',
            'feedbacks',
            'feedbackSettings',
            'isLoggedIn',
            'publicUser'
        ));
    }

    /**
     * Display public roadmap for subdomain.
     */
    public function roadmap(Request $request)
    {
        $settings = $this->requireTeamSettings($request);

        // Get active roadmap statuses for this team (only roadmap workflow)
        $roadmaps = Roadmap::where('team_id', $settings->team_id)
            ->where('workflow_type', 'roadmap workflow')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get roadmap items grouped by roadmap status
        $roadmapItems = RoadmapItem::with(['feedback.category'])
            ->where('team_id', $settings->team_id)
            ->whereIn('roadmap_status_id', $roadmaps->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('roadmap_status_id');

        return view('public.roadmap', compact('settings', 'roadmaps', 'roadmapItems'));
    }

    /**
     * Display public changelog for subdomain.
     */
    public function changelog(Request $request)
    {
        $settings = $this->requireTeamSettings($request);

        // Get active categories with changelog counts
        $categories = Category::where('team_id', $settings->team_id)
            ->where('status', 'active')
            ->withCount(['changelogs' => function ($query) use ($settings) {
                $query->where('team_id', $settings->team_id)
                    ->where('status', 'published');
            }])
            ->orderBy('name')
            ->get();

        // Get published changelogs
        $changelogs = Changelog::where('team_id', $settings->team_id)
            ->where('status', 'published')
            ->orderBy('publish_date', 'desc')
            ->paginate(10);

        return view('public.changelog', compact('settings', 'categories', 'changelogs'));
    }

    /**
     * Display public testimonials for subdomain.
     */
    public function testimonials(Request $request)
    {
        $settings = $this->requireTeamSettings($request);

        // Get approved testimonials
        $testimonials = Testimonial::where('team_id', $settings->team_id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('public.testimonials', compact('settings', 'testimonials'));
    }

    /**
     * Display public knowledge base for subdomain.
     */
    public function knowledge(Request $request)
    {
        $settings = $this->requireTeamSettings($request);

        // Get knowledge boards
        $knowledgeBoards = KnowledgeBoard::where('team_id', $settings->team_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('public.knowledge', compact('settings', 'knowledgeBoards'));
    }
}
