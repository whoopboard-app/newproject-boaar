<?php

namespace App\Notifications;

use App\Models\Feedback;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeedbackLoginAccessNotification extends Notification
{
    protected $feedback;
    protected $temporaryPassword;

    /**
     * Create a new notification instance.
     */
    public function __construct(Feedback $feedback, $temporaryPassword = null)
    {
        $this->feedback = $feedback;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name', 'Whoopboard');

        return (new MailMessage)
            ->subject('Login Access Granted - ' . $appName)
            ->greeting('Hello ' . $this->feedback->name . '!')
            ->line('Great news! Your feedback has been received and login access has been granted to you.')
            ->line('**Your Feedback:** ' . $this->feedback->idea)
            ->line('You can now login to our platform to track your feedback and engage with our team.')
            ->line('**Login Credentials:**')
            ->line('Email: ' . $this->feedback->email)
            ->line('Temporary Password: ' . ($this->temporaryPassword ?? 'Please check your email for password reset link'))
            ->action('Login to Dashboard', url('/login'))
            ->line('We recommend changing your password after your first login.')
            ->line('Thank you for your valuable feedback!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'feedback_id' => $this->feedback->id,
            'email' => $this->feedback->email,
        ];
    }
}
