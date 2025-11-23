<?php

namespace App\Http\Controllers;

use App\Models\TestimonialCampaign;
use App\Models\TestimonialTemplate;
use App\Models\UserSegment;
use App\Models\Subscriber;
use App\Models\CampaignSubscriber;
use App\Mail\TestimonialCampaignMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TestimonialCampaignController extends Controller
{
    /**
     * Display a listing of campaigns.
     */
    public function index()
    {
        $campaigns = TestimonialCampaign::with('template')
            ->where('team_id', Auth::user()->current_team_id)
            ->latest()
            ->get();

        return response()->json($campaigns);
    }

    /**
     * Store a newly created campaign.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'objective' => 'nullable|string',
            'status' => 'required|in:active,inactive,draft',
            'template_id' => 'required|exists:testimonial_templates,id',
            'segment_ids' => 'required|array|min:1',
            'segment_ids.*' => 'exists:user_segments,id',
            'delivery_type' => 'required|in:instant,scheduled',
            'scheduled_at' => 'required_if:delivery_type,scheduled|nullable|date|after:now',
        ]);

        $validated['team_id'] = Auth::user()->current_team_id;

        // Create the campaign
        $campaign = TestimonialCampaign::create($validated);

        // Get subscribers from selected segments
        $subscriberIds = [];
        foreach ($validated['segment_ids'] as $segmentId) {
            $segment = UserSegment::find($segmentId);
            $segmentSubscriberIds = $segment->subscribers()->subscribed()->pluck('subscribers.id')->toArray();
            $subscriberIds = array_merge($subscriberIds, $segmentSubscriberIds);
        }

        // Remove duplicates
        $subscriberIds = array_unique($subscriberIds);

        // Create campaign subscriber records
        foreach ($subscriberIds as $subscriberId) {
            CampaignSubscriber::create([
                'campaign_id' => $campaign->id,
                'subscriber_id' => $subscriberId,
            ]);
        }

        // If instant delivery and active status, send emails immediately
        if ($validated['delivery_type'] === 'instant' && $validated['status'] === 'active') {
            $this->sendCampaignEmails($campaign);
        }

        return response()->json([
            'message' => 'Campaign created successfully!',
            'campaign' => $campaign->load('template', 'campaignSubscribers.subscriber'),
        ], 201);
    }

    /**
     * Display the specified campaign.
     */
    public function show(TestimonialCampaign $campaign)
    {
        $campaign->load('template', 'campaignSubscribers.subscriber', 'testimonials');
        return response()->json($campaign);
    }

    /**
     * Update the specified campaign.
     */
    public function update(Request $request, TestimonialCampaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'objective' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive,draft',
            'template_id' => 'sometimes|exists:testimonial_templates,id',
            'delivery_type' => 'sometimes|in:instant,scheduled',
            'scheduled_at' => 'required_if:delivery_type,scheduled|nullable|date|after:now',
        ]);

        $campaign->update($validated);

        // If campaign is activated and hasn't been sent yet, send it
        if (isset($validated['status']) && $validated['status'] === 'active' && !$campaign->sent_at) {
            if ($campaign->delivery_type === 'instant' ||
                ($campaign->delivery_type === 'scheduled' && $campaign->scheduled_at && $campaign->scheduled_at->isPast())) {
                $this->sendCampaignEmails($campaign);
            }
        }

        return response()->json([
            'message' => 'Campaign updated successfully!',
            'campaign' => $campaign->load('template'),
        ]);
    }

    /**
     * Remove the specified campaign.
     */
    public function destroy(TestimonialCampaign $campaign)
    {
        $campaign->delete();

        return response()->json([
            'message' => 'Campaign deleted successfully!',
        ]);
    }

    /**
     * Send campaign emails to all subscribers
     */
    protected function sendCampaignEmails(TestimonialCampaign $campaign)
    {
        $campaignSubscribers = $campaign->campaignSubscribers()->with('subscriber')->get();

        foreach ($campaignSubscribers as $campaignSubscriber) {
            if (!$campaignSubscriber->email_sent) {
                try {
                    // Send email
                    Mail::to($campaignSubscriber->subscriber->email)->send(
                        new TestimonialCampaignMail($campaign, $campaignSubscriber)
                    );

                    // Mark as sent
                    $campaignSubscriber->markAsSent();
                } catch (\Exception $e) {
                    // Log error but continue with other emails
                    \Log::error('Failed to send campaign email: ' . $e->getMessage());
                }
            }
        }

        // Mark campaign as sent and update metrics
        $campaign->markAsSent();
        $campaign->updateMetrics();
    }

    /**
     * Track email open
     */
    public function trackOpen($trackingToken)
    {
        $campaignSubscriber = CampaignSubscriber::where('tracking_token', $trackingToken)->first();

        if ($campaignSubscriber) {
            $campaignSubscriber->markAsOpened();
        }

        // Return a 1x1 transparent pixel
        return response(base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'))
            ->header('Content-Type', 'image/gif');
    }

    /**
     * Track link click
     */
    public function trackClick($trackingToken)
    {
        $campaignSubscriber = CampaignSubscriber::where('tracking_token', $trackingToken)->first();

        if ($campaignSubscriber) {
            $campaignSubscriber->markAsClicked();

            // Redirect to the testimonial form
            $template = $campaignSubscriber->campaign->template;
            return redirect()->route('testimonials.public-form', [
                'uniqueUrl' => $template->unique_url,
                'tracking_token' => $trackingToken,
            ]);
        }

        return redirect('/');
    }

    /**
     * Get campaign statistics
     */
    public function statistics(TestimonialCampaign $campaign)
    {
        $stats = [
            'total_subscribers' => $campaign->campaignSubscribers()->count(),
            'emails_sent' => $campaign->campaignSubscribers()->where('email_sent', true)->count(),
            'emails_opened' => $campaign->campaignSubscribers()->where('email_opened', true)->count(),
            'links_clicked' => $campaign->campaignSubscribers()->where('link_clicked', true)->count(),
            'testimonials_submitted' => $campaign->campaignSubscribers()->where('testimonial_submitted', true)->count(),
            'average_rating' => $campaign->testimonials()->avg('rating'),
            'open_rate' => 0,
            'click_rate' => 0,
            'conversion_rate' => 0,
        ];

        if ($stats['emails_sent'] > 0) {
            $stats['open_rate'] = round(($stats['emails_opened'] / $stats['emails_sent']) * 100, 2);
            $stats['click_rate'] = round(($stats['links_clicked'] / $stats['emails_sent']) * 100, 2);
            $stats['conversion_rate'] = round(($stats['testimonials_submitted'] / $stats['emails_sent']) * 100, 2);
        }

        return response()->json($stats);
    }
}
