<?php

namespace App\Mail;

use App\Models\BackofficeAccessCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BackofficeAccessCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $accessCode;

    /**
     * Create a new message instance.
     */
    public function __construct(BackofficeAccessCode $accessCode)
    {
        $this->accessCode = $accessCode;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Backoffice Access Code - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.backoffice-access-code',
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
