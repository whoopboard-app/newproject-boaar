<?php

namespace App\Mail;

use App\Models\AppSettings;
use App\Models\SiteAccessInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SiteAccessVerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    public $invite;
    public $settings;

    /**
     * Create a new message instance.
     */
    public function __construct(SiteAccessInvite $invite, AppSettings $settings)
    {
        $this->invite = $invite;
        $this->settings = $settings;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $productName = $this->settings->product_name ?? 'Our Site';

        return new Envelope(
            subject: "Your verification code for {$productName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.site-access-verification',
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
