<?php

namespace App\Notifications;

use App\Models\PublicUser;
use App\Models\AppSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MagicLinkNotification extends Notification
{
    use Queueable;

    protected $publicUser;
    protected $settings;
    protected $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(PublicUser $publicUser, AppSettings $settings, $token)
    {
        $this->publicUser = $publicUser;
        $this->settings = $settings;
        $this->token = $token;
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
        $magicLinkUrl = route('public.auth.verify', ['token' => $this->token]);
        $productName = $this->settings->product_name ?? config('app.name');

        return (new MailMessage)
            ->subject('Your Magic Link to ' . $productName)
            ->greeting('Hello!')
            ->line('You requested to log in to ' . $productName . '.')
            ->line('Click the button below to access your account. This link will expire in 15 minutes.')
            ->action('Log In to ' . $productName, $magicLinkUrl)
            ->line('Or copy and paste this link into your browser:')
            ->line($magicLinkUrl)
            ->line('If you did not request this link, you can safely ignore this email.')
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
