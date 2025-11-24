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
        $testimonial->load('template', 'campaign');
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

        return view('testimonials.public-form', compact('template', 'trackingToken'));
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'type' => 'required|in:text,video',
            'text_content' => 'required_if:type,text',
            'video_url' => 'required_if:type,video|nullable|url',
            'rating' => 'nullable|integer|min:1|max:5',
            'avatar' => 'nullable|image|max:2048',
            'tracking_token' => 'nullable|string',
        ]);

        $validated['team_id'] = $template->team_id;
        $validated['template_id'] = $template->id;
        $validated['source'] = 'website';
        $validated['status'] = 'pending_review';

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
