<?php

namespace App\Notifications;

use App\Models\Feedback;
use App\Models\AppSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeedbackSubmissionNotification extends Notification
{
    use Queueable;

    protected $feedback;
    protected $settings;

    /**
     * Create a new notification instance.
     */
    public function __construct(Feedback $feedback, AppSettings $settings)
    {
        $this->feedback = $feedback;
        $this->settings = $settings;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verifyUrl = route('public.feedback.verify', ['token' => $this->feedback->verification_token]);
        $productName = $this->settings->product_name ?? config('app.name');

        return (new MailMessage)
            ->subject('Confirm Your Idea Submission - ' . $productName)
            ->greeting('Hello ' . $this->feedback->name . '!')
            ->line('Thank you for submitting your idea to ' . $productName . '.')
            ->line('')
            ->line('**Your Idea Summary:**')
            ->line($this->feedback->idea)
            ->line('')
            ->when($this->feedback->value_description, function ($message) {
                return $message->line('**Why this idea adds value:**')
                    ->line(\Illuminate\Support\Str::limit(strip_tags($this->feedback->value_description), 200));
            })
            ->line('')
            ->line('Please click the button below to confirm your submission. Your idea will be visible to others once confirmed.')
            ->action('Confirm My Submission', $verifyUrl)
            ->line('')
            ->line('Or copy and paste this link into your browser:')
            ->line($verifyUrl)
            ->line('')
            ->line('If you did not submit this idea, you can safely ignore this email.')
            ->salutation('Best regards, ' . $productName . ' Team');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [];
    }
}
