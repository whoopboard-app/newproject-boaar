<?php

namespace App\Mail;

use App\Models\TestimonialCampaign;
use App\Models\CampaignSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestimonialCampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $campaignSubscriber;
    public $template;
    public $trackingToken;

    /**
     * Create a new message instance.
     */
    public function __construct(TestimonialCampaign $campaign, CampaignSubscriber $campaignSubscriber)
    {
        $this->campaign = $campaign;
        $this->campaignSubscriber = $campaignSubscriber;
        $this->template = $campaign->template;
        $this->trackingToken = $campaignSubscriber->tracking_token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->template->email_subject ?? 'We\'d love to hear your feedback!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.testimonial-campaign',
            with: [
                'campaign' => $this->campaign,
                'template' => $this->template,
                'subscriber' => $this->campaignSubscriber->subscriber,
                'trackingToken' => $this->trackingToken,
                'testimonialUrl' => route('testimonials.campaign-click', ['trackingToken' => $this->trackingToken]),
                'trackingPixelUrl' => route('testimonials.campaign-open', ['trackingToken' => $this->trackingToken]),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
