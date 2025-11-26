<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\TestimonialTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    /**
     * Display a listing of testimonials and templates.
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'testimonials');

        // Get testimonials with template and campaign relationships
        $testimonials = Testimonial::with('template', 'campaign')
            ->latest()
            ->paginate(20);

        // Get templates with submission counts
        $templates = TestimonialTemplate::withCount('testimonials')
            ->latest()
            ->get();

        // Get campaigns with metrics
        $campaigns = \App\Models\TestimonialCampaign::with('template')
            ->withCount('testimonials')
            ->latest()
            ->get();

        return view('testimonials.index', compact('testimonials', 'templates', 'campaigns', 'activeTab'));
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create()
    {
        $templates = TestimonialTemplate::active()->get();
        return view('testimonials.form', compact('templates'));
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'type' => 'required|in:text,video',
            'text_content' => 'required_if:type,text',
            'video_url' => 'required_if:type,video|nullable|url',
            'rating' => 'nullable|integer|min:1|max:5',
            'source' => 'required|in:email,website,script,manual',
            'status' => 'required|in:published,pending_review,under_review,draft,active,inactive',
            'template_id' => 'nullable|exists:testimonial_templates,id',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $validated['team_id'] = Auth::user()->current_team_id;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('testimonials/avatars', 'public');
        }

        Testimonial::create($validated);

        return redirect()->route('testimonials.index')
            ->with('success', 'Testimonial created successfully!');
    }

    /**
     * Display the specified testimonial.
     */
    public function show(Testimonial $testimonial)
    {
        $testimonial->load('template', 'campaign', 'campaignSubscriber.subscriber.segments');

        // If this is a video testimonial with Mux, check/update status
        if ($testimonial->type === 'video' && $testimonial->mux_upload_id && $testimonial->mux_status !== 'ready') {
            $muxService = app(\App\Services\MuxService::class);
            $muxService->updateTestimonialFromUpload($testimonial);
            $testimonial->refresh();
        }

        return view('testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit(Testimonial $testimonial)
    {
        $templates = TestimonialTemplate::active()->get();
        return view('testimonials.form', compact('testimonial', 'templates'));
    }

    /**
     * Update the specified testimonial.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        // For Mux videos, video_url is not required
        $videoUrlRule = 'nullable|url';
        if ($request->input('type') === 'video' && !$testimonial->mux_playback_id) {
            $videoUrlRule = 'required|url';
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'type' => 'required|in:text,video',
            'text_content' => 'required_if:type,text',
            'video_url' => $videoUrlRule,
            'rating' => 'nullable|integer|min:1|max:5',
            'source' => 'required|in:email,website,script,manual',
            'status' => 'required|in:published,pending_review,under_review,draft,active,inactive',
            'template_id' => 'nullable|exists:testimonial_templates,id',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('testimonials/avatars', 'public');
        }

        $testimonial->update($validated);

        return redirect()->route('testimonials.show', $testimonial)
            ->with('success', 'Testimonial updated successfully!');
    }

    /**
     * Remove the specified testimonial.
     */
    public function destroy(Testimonial $testimonial)
    {
        // Delete avatar if exists
        if ($testimonial->avatar) {
            Storage::disk('public')->delete($testimonial->avatar);
        }

        $testimonial->delete();

        return redirect()->route('testimonials.index')
            ->with('success', 'Testimonial deleted successfully!');
    }

    /**
     * Public form to submit testimonial
     */
    public function publicForm($uniqueUrl, Request $request)
    {
        // Handle test preview URL
        if ($uniqueUrl === 'test-preview') {
            $templateData = session('test_template');

            if (!$templateData) {
                return view('testimonials.test-preview-message');
            }

            // Create a template object from session data
            $template = new TestimonialTemplate($templateData);
            $template->exists = false; // Mark as not persisted

            return view('testimonials.public-form', compact('template'));
        }

        $template = TestimonialTemplate::where('unique_url', $uniqueUrl)
            ->where('status', 'active')
            ->firstOrFail();

        // Get tracking token if provided
        $trackingToken = $request->query('tracking_token');

        // Get subscriber email from tracking token for prepopulation
        $subscriberEmail = null;
        $subscriberName = null;
        if ($trackingToken) {
            $campaignSubscriber = \App\Models\CampaignSubscriber::where('tracking_token', $trackingToken)
                ->with('subscriber')
                ->first();
            if ($campaignSubscriber && $campaignSubscriber->subscriber) {
                $subscriberEmail = $campaignSubscriber->subscriber->email;
                $subscriberName = $campaignSubscriber->subscriber->full_name;
            }
        }

        return view('testimonials.public-form', compact('template', 'trackingToken', 'subscriberEmail', 'subscriberName'));
    }

    /**
     * Store testimonial from public form
     */
    public function publicStore(Request $request, $uniqueUrl)
    {
        // Handle test preview submissions
        if ($uniqueUrl === 'test-preview') {
            $templateData = session('test_template');

            if (!$templateData) {
                return redirect()->back()->with('error', 'Test session expired. Please send a new test email.');
            }

            $template = new TestimonialTemplate($templateData);

            // For test preview, just show the thank you page without saving
            if ($template->enable_thankyou) {
                return view('testimonials.thank-you', compact('template'));
            }

            return view('testimonials.test-thank-you');
        }

        $template = TestimonialTemplate::where('unique_url', $uniqueUrl)
            ->where('status', 'active')
            ->firstOrFail();

        // Log incoming request data for debugging
        \Log::info('Testimonial submission request', [
            'type' => $request->input('type'),
            'mux_upload_id' => $request->input('mux_upload_id'),
            'has_text_content' => !empty($request->input('text_content')),
            'all_data' => $request->except(['avatar']),
        ]);

        // Build validation rules - video_url is only required if type is video AND no mux_upload_id
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'type' => 'required|in:text,video',
            'text_content' => 'nullable|string', // Made nullable, we'll validate manually
            'video_url' => 'nullable|url',
            'mux_upload_id' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'avatar' => 'nullable|image|max:2048',
            'tracking_token' => 'nullable|string',
        ];

        // Custom validation: if type is video, either video_url or mux_upload_id must be present
        $validated = $request->validate($rules);

        // Additional validation based on type
        if ($validated['type'] === 'video') {
            if (empty($validated['video_url']) && empty($validated['mux_upload_id'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['video' => 'Please record a video or provide a video URL.']);
            }
        } elseif ($validated['type'] === 'text') {
            if (empty($validated['text_content'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['text_content' => 'Please enter your testimonial.']);
            }
        }

        $validated['team_id'] = $template->team_id;
        $validated['template_id'] = $template->id;
        $validated['source'] = 'website';
        $validated['status'] = 'pending_review';

        // Handle Mux video upload
        if ($validated['type'] === 'video' && !empty($validated['mux_upload_id'])) {
            $validated['mux_status'] = 'processing';
        }

        // Handle tracking token for campaign testimonials
        $campaignSubscriber = null;
        if (isset($validated['tracking_token'])) {
            $campaignSubscriber = \App\Models\CampaignSubscriber::where('tracking_token', $validated['tracking_token'])->first();
            if ($campaignSubscriber) {
                $validated['campaign_id'] = $campaignSubscriber->campaign_id;
                $validated['source'] = 'email';
            }
        }
        unset($validated['tracking_token']);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('testimonials/avatars', 'public');
        }

        $testimonial = Testimonial::create($validated);

        // If Mux video, start polling for status
        if ($testimonial->mux_upload_id) {
            // The MuxService will update the testimonial when the video is ready
            // This happens via webhook or polling from the admin interface
        }

        // Update campaign subscriber if applicable
        if ($campaignSubscriber) {
            $campaignSubscriber->markTestimonialSubmitted($testimonial->id);
        }

        // Show custom thank you page if enabled
        if ($template->enable_thankyou) {
            return view('testimonials.thank-you', compact('template'));
        }

        return redirect()->back()->with('success', $template->thank_you_message ?? 'Thank you for your testimonial!');
    }
}
