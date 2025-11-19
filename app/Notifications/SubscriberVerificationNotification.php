<?php

namespace App\Notifications;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriberVerificationNotification extends Notification
{
    use Queueable;

    protected $subscriber;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
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
        $verifyUrl = route('subscriber.verify', ['token' => $this->subscriber->verification_token]);
        $unsubscribeUrl = route('subscriber.unsubscribe', ['token' => $this->subscriber->verification_token]);

        return (new MailMessage)
            ->subject('Confirm Your Subscription to ' . config('app.name'))
            ->greeting('Hello ' . $this->subscriber->full_name . '!')
            ->line('Thank you for subscribing to our newsletter.')
            ->line('Please click the button below to confirm your subscription.')
            ->action('Confirm Subscription', $verifyUrl)
            ->line('If you did not subscribe, you can safely ignore this email.')
            ->salutation('Best regards, ' . config('app.name'))
            ->view('emails.subscriber-verification', [
                'subscriber' => $this->subscriber,
                'verifyUrl' => $verifyUrl,
                'unsubscribeUrl' => $unsubscribeUrl,
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [];
    }
}
