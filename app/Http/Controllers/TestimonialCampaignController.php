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
            'exclude_testimonial_givers' => 'nullable|boolean',
        ]);

        $validated['team_id'] = Auth::user()->current_team_id;

        // Store exclude_testimonial_givers separately as it's not a campaign field
        $excludeTestimonialGivers = $validated['exclude_testimonial_givers'] ?? false;
        unset($validated['exclude_testimonial_givers']);

        // Create the campaign
        $campaign = TestimonialCampaign::create($validated);

        // Get subscribers from selected segments
        $subscriberIds = [];
        foreach ($validated['segment_ids'] as $segmentId) {
            $segment = UserSegment::find($segmentId);
            if ($segment) {
                $segmentSubscriberIds = $segment->subscribers()->subscribed()->pluck('subscribers.id')->toArray();
                $subscriberIds = array_merge($subscriberIds, $segmentSubscriberIds);
            }
        }

        // Remove duplicates (ensure each subscriber only gets one email)
        $subscriberIds = array_unique($subscriberIds);
        \Log::info('Campaign subscribers before exclusion: ' . count($subscriberIds));

        // If exclude_testimonial_givers is checked, filter out subscribers who already gave testimonials
        if ($excludeTestimonialGivers) {
            // Get all emails from subscribers in the list
            $subscriberEmails = Subscriber::whereIn('id', $subscriberIds)
                ->pluck('email', 'id')
                ->toArray();

            // Get emails that have already submitted testimonials
            $testimonialEmails = \App\Models\Testimonial::where('team_id', Auth::user()->current_team_id)
                ->whereNotNull('email')
                ->whereIn('email', array_values($subscriberEmails))
                ->pluck('email')
                ->toArray();

            \Log::info('Testimonial emails to exclude: ' . count($testimonialEmails));

            // Filter out subscribers whose emails match testimonial emails
            $subscriberIds = array_filter($subscriberIds, function($subscriberId) use ($subscriberEmails, $testimonialEmails) {
                return !in_array($subscriberEmails[$subscriberId], $testimonialEmails);
            });

            // Re-index array
            $subscriberIds = array_values($subscriberIds);
        }

        \Log::info('Final campaign subscribers: ' . count($subscriberIds));

        // Create campaign subscriber records
        foreach ($subscriberIds as $subscriberId) {
            CampaignSubscriber::create([
                'campaign_id' => $campaign->id,
                'subscriber_id' => $subscriberId,
            ]);
        }

        \Log::info('Campaign subscriber records created: ' . CampaignSubscriber::where('campaign_id', $campaign->id)->count());

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
     * Display the campaign view page with details.
     */
    public function view(TestimonialCampaign $campaign)
    {
        $campaign->load('template', 'campaignSubscribers.subscriber', 'testimonials');

        // Get statistics
        $stats = [
            'total_subscribers' => $campaign->campaignSubscribers()->count(),
            'emails_sent' => $campaign->campaignSubscribers()->where('email_sent', true)->count(),
            'emails_failed' => $campaign->campaignSubscribers()->where('email_failed', true)->count(),
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

        // Get email logs
        $sentEmails = $campaign->campaignSubscribers()->where('email_sent', true)->with('subscriber')->get();
        $failedEmails = $campaign->campaignSubscribers()->where('email_failed', true)->with('subscriber')->get();
        $scheduledEmails = $campaign->campaignSubscribers()
            ->where('email_sent', false)
            ->where('email_failed', false)
            ->with('subscriber')
            ->get();

        return view('testimonial-campaigns.view', compact('campaign', 'stats', 'sentEmails', 'failedEmails', 'scheduledEmails'));
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
     * Pause/Resume a campaign
     */
    public function pause(TestimonialCampaign $campaign)
    {
        // Toggle between active and inactive
        $newStatus = $campaign->status === 'active' ? 'inactive' : 'active';
        $campaign->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Campaign ' . ($newStatus === 'active' ? 'resumed' : 'paused') . ' successfully!');
    }

    /**
     * Update the specified campaign.
     */
    public function update(Request $request, TestimonialCampaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'objective' => 'nullable|string',
            'template_id' => 'sometimes|exists:testimonial_templates,id',
            'delivery_type' => 'sometimes|in:instant,scheduled',
            'scheduled_at' => 'required_if:delivery_type,scheduled|nullable|date|after:now',
            'segment_ids' => 'sometimes|array',
            'segment_ids.*' => 'exists:user_segments,id',
        ]);

        // Handle segment additions if provided
        if (isset($validated['segment_ids'])) {
            $newSegmentIds = $validated['segment_ids'];

            // Get existing subscriber IDs in this campaign
            $existingSubscriberIds = $campaign->campaignSubscribers->pluck('subscriber_id')->unique()->toArray();

            // Get all subscribers from the new segment list
            $allSubscriberIds = [];
            foreach ($newSegmentIds as $segmentId) {
                $segment = UserSegment::find($segmentId);
                if ($segment) {
                    $segmentSubscriberIds = $segment->subscribers()->subscribed()->pluck('subscribers.id')->toArray();
                    $allSubscriberIds = array_merge($allSubscriberIds, $segmentSubscriberIds);
                }
            }

            // Remove duplicates
            $allSubscriberIds = array_unique($allSubscriberIds);

            // Find new subscribers (those not already in the campaign)
            $newSubscriberIds = array_diff($allSubscriberIds, $existingSubscriberIds);

            \Log::info('Campaign update - Existing subscribers: ' . count($existingSubscriberIds));
            \Log::info('Campaign update - All subscribers from segments: ' . count($allSubscriberIds));
            \Log::info('Campaign update - New subscribers to add: ' . count($newSubscriberIds));

            // Create campaign subscriber records for new subscribers
            foreach ($newSubscriberIds as $subscriberId) {
                CampaignSubscriber::create([
                    'campaign_id' => $campaign->id,
                    'subscriber_id' => $subscriberId,
                ]);
            }

            // If campaign is active and delivery is instant, send emails to new subscribers only
            if ($campaign->status === 'active' && $campaign->delivery_type === 'instant' && count($newSubscriberIds) > 0) {
                $this->sendCampaignEmailsToSubscribers($campaign, $newSubscriberIds);
            }

            unset($validated['segment_ids']);
        }

        $campaign->update($validated);

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
        \Log::info('Starting to send emails for campaign ' . $campaign->id . ' to ' . $campaignSubscribers->count() . ' subscribers');

        $sentCount = 0;
        $errorCount = 0;

        foreach ($campaignSubscribers as $index => $campaignSubscriber) {
            if (!$campaignSubscriber->email_sent) {
                try {
                    \Log::info('Sending email to: ' . $campaignSubscriber->subscriber->email);

                    // Increment send attempts
                    $campaignSubscriber->send_attempts = $campaignSubscriber->send_attempts + 1;
                    $campaignSubscriber->save();

                    // Send email
                    Mail::to($campaignSubscriber->subscriber->email)->send(
                        new TestimonialCampaignMail($campaign, $campaignSubscriber)
                    );

                    // Mark as sent
                    $campaignSubscriber->markAsSent();
                    $sentCount++;

                    \Log::info('Email sent successfully to: ' . $campaignSubscriber->subscriber->email);

                    // Add delay between emails to avoid rate limiting
                    // Skip delay for the last email
                    if ($index < $campaignSubscribers->count() - 1) {
                        sleep(2); // 2 second delay between emails
                    }
                } catch (\Exception $e) {
                    // Log error and mark as failed
                    $errorCount++;
                    $errorMessage = $e->getMessage();
                    \Log::error('Failed to send campaign email to ' . $campaignSubscriber->subscriber->email . ': ' . $errorMessage);

                    // Mark email as failed with reason
                    $campaignSubscriber->markAsFailed($errorMessage);

                    // If rate limit error, add extra delay before next email
                    if (strpos($errorMessage, 'Too many emails') !== false || strpos($errorMessage, '550 5.7.0') !== false) {
                        \Log::info('Rate limit detected, adding extra delay...');
                        sleep(3); // Extra 3 second delay for rate limit errors
                    }
                }
            }
        }

        \Log::info('Campaign email sending complete. Sent: ' . $sentCount . ', Errors: ' . $errorCount);

        // Mark campaign as sent and update metrics
        $campaign->markAsSent();
        $campaign->updateMetrics();
    }

    /**
     * Send campaign emails to specific subscribers
     */
    protected function sendCampaignEmailsToSubscribers(TestimonialCampaign $campaign, array $subscriberIds)
    {
        $campaignSubscribers = $campaign->campaignSubscribers()
            ->whereIn('subscriber_id', $subscriberIds)
            ->with('subscriber')
            ->get();

        \Log::info('Sending emails for campaign ' . $campaign->id . ' to ' . $campaignSubscribers->count() . ' new subscribers');

        $sentCount = 0;
        $errorCount = 0;

        foreach ($campaignSubscribers as $index => $campaignSubscriber) {
            if (!$campaignSubscriber->email_sent) {
                try {
                    \Log::info('Sending email to: ' . $campaignSubscriber->subscriber->email);

                    // Increment send attempts
                    $campaignSubscriber->send_attempts = $campaignSubscriber->send_attempts + 1;
                    $campaignSubscriber->save();

                    // Send email
                    Mail::to($campaignSubscriber->subscriber->email)->send(
                        new TestimonialCampaignMail($campaign, $campaignSubscriber)
                    );

                    // Mark as sent
                    $campaignSubscriber->markAsSent();
                    $sentCount++;

                    \Log::info('Email sent successfully to: ' . $campaignSubscriber->subscriber->email);

                    // Add delay between emails
                    if ($index < $campaignSubscribers->count() - 1) {
                        sleep(2);
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    $errorMessage = $e->getMessage();
                    \Log::error('Failed to send campaign email to ' . $campaignSubscriber->subscriber->email . ': ' . $errorMessage);

                    $campaignSubscriber->markAsFailed($errorMessage);

                    if (strpos($errorMessage, 'Too many emails') !== false || strpos($errorMessage, '550 5.7.0') !== false) {
                        \Log::info('Rate limit detected, adding extra delay...');
                        sleep(3);
                    }
                }
            }
        }

        \Log::info('Campaign email sending to new subscribers complete. Sent: ' . $sentCount . ', Errors: ' . $errorCount);

        // Update campaign metrics
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

            // Redirect to the testimonial form with tracking token for email prepopulation
            $template = $campaignSubscriber->campaign->template;
            return redirect()->route('testimonials.public.form', [
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
        // Get sent emails with subscriber details
        $sentEmails = $campaign->campaignSubscribers()
            ->where('email_sent', true)
            ->with('subscriber')
            ->get()
            ->map(function ($cs) {
                return [
                    'id' => $cs->id,
                    'email' => $cs->subscriber->email,
                    'name' => $cs->subscriber->full_name,
                    'sent_at' => $cs->sent_at ? $cs->sent_at->format('M d, Y H:i') : null,
                    'email_opened' => $cs->email_opened,
                    'link_clicked' => $cs->link_clicked,
                    'testimonial_submitted' => $cs->testimonial_submitted,
                ];
            });

        // Get failed emails with subscriber details and failure reason
        $failedEmails = $campaign->campaignSubscribers()
            ->where('email_failed', true)
            ->with('subscriber')
            ->get()
            ->map(function ($cs) {
                return [
                    'id' => $cs->id,
                    'email' => $cs->subscriber->email,
                    'name' => $cs->subscriber->full_name,
                    'failure_reason' => $cs->failure_reason,
                    'send_attempts' => $cs->send_attempts,
                ];
            });

        $stats = [
            'total_subscribers' => $campaign->campaignSubscribers()->count(),
            'emails_sent' => $campaign->campaignSubscribers()->where('email_sent', true)->count(),
            'emails_failed' => $campaign->campaignSubscribers()->where('email_failed', true)->count(),
            'emails_opened' => $campaign->campaignSubscribers()->where('email_opened', true)->count(),
            'links_clicked' => $campaign->campaignSubscribers()->where('link_clicked', true)->count(),
            'testimonials_submitted' => $campaign->campaignSubscribers()->where('testimonial_submitted', true)->count(),
            'average_rating' => $campaign->testimonials()->avg('rating'),
            'open_rate' => 0,
            'click_rate' => 0,
            'conversion_rate' => 0,
            'sent_emails' => $sentEmails,
            'failed_emails' => $failedEmails,
        ];

        if ($stats['emails_sent'] > 0) {
            $stats['open_rate'] = round(($stats['emails_opened'] / $stats['emails_sent']) * 100, 2);
            $stats['click_rate'] = round(($stats['links_clicked'] / $stats['emails_sent']) * 100, 2);
            $stats['conversion_rate'] = round(($stats['testimonials_submitted'] / $stats['emails_sent']) * 100, 2);
        }

        return response()->json($stats);
    }

    /**
     * Resend emails to failed subscribers
     */
    public function resendFailedEmails(Request $request, TestimonialCampaign $campaign)
    {
        $validated = $request->validate([
            'subscriber_ids' => 'required|array|min:1',
            'subscriber_ids.*' => 'exists:campaign_subscribers,id',
        ]);

        $subscriberIds = $validated['subscriber_ids'];

        // Get the failed campaign subscribers
        $campaignSubscribers = $campaign->campaignSubscribers()
            ->whereIn('id', $subscriberIds)
            ->where('email_failed', true)
            ->with('subscriber')
            ->get();

        if ($campaignSubscribers->isEmpty()) {
            return response()->json([
                'message' => 'No failed emails found to resend.',
            ], 400);
        }

        \Log::info('Resending emails for campaign ' . $campaign->id . ' to ' . $campaignSubscribers->count() . ' subscribers');

        $sentCount = 0;
        $errorCount = 0;

        foreach ($campaignSubscribers as $index => $campaignSubscriber) {
            try {
                \Log::info('Resending email to: ' . $campaignSubscriber->subscriber->email);

                // Increment send attempts
                $campaignSubscriber->send_attempts = $campaignSubscriber->send_attempts + 1;
                $campaignSubscriber->save();

                // Send email
                Mail::to($campaignSubscriber->subscriber->email)->send(
                    new TestimonialCampaignMail($campaign, $campaignSubscriber)
                );

                // Mark as sent
                $campaignSubscriber->markAsSent();
                $sentCount++;

                \Log::info('Email resent successfully to: ' . $campaignSubscriber->subscriber->email);

                // Add delay between emails to avoid rate limiting
                if ($index < $campaignSubscribers->count() - 1) {
                    sleep(2);
                }
            } catch (\Exception $e) {
                // Log error and mark as failed
                $errorCount++;
                $errorMessage = $e->getMessage();
                \Log::error('Failed to resend email to ' . $campaignSubscriber->subscriber->email . ': ' . $errorMessage);

                // Mark email as failed with reason
                $campaignSubscriber->markAsFailed($errorMessage);

                // If rate limit error, add extra delay
                if (strpos($errorMessage, 'Too many emails') !== false || strpos($errorMessage, '550 5.7.0') !== false) {
                    \Log::info('Rate limit detected, adding extra delay...');
                    sleep(3);
                }
            }
        }

        \Log::info('Email resending complete. Sent: ' . $sentCount . ', Errors: ' . $errorCount);

        // Update campaign metrics
        $campaign->updateMetrics();

        return response()->json([
            'message' => "Resend complete. Successfully sent: {$sentCount}, Failed: {$errorCount}",
            'sent' => $sentCount,
            'failed' => $errorCount,
        ]);
    }
}
