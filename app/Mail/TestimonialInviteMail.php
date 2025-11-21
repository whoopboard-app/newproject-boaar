<?php

namespace App\Mail;

use App\Models\TestimonialTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestimonialInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $isTest;

    /**
     * Create a new message instance.
     */
    public function __construct(TestimonialTemplate $template, $isTest = false)
    {
        $this->template = $template;
        $this->isTest = $isTest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->template->email_subject ?? 'We\'d love to hear your feedback!';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.testimonial-invite',
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
