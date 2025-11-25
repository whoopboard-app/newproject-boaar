<?php

namespace App\Notifications;

use App\Models\VoteOtp;
use App\Models\AppSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VoteOtpNotification extends Notification
{
    use Queueable;

    protected $voteOtp;
    protected $settings;

    /**
     * Create a new notification instance.
     */
    public function __construct(VoteOtp $voteOtp, AppSettings $settings)
    {
        $this->voteOtp = $voteOtp;
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
        $productName = $this->settings->product_name ?? config('app.name');
        $otpCode = $this->voteOtp->otp_code;

        return (new MailMessage)
            ->subject('Your Verification Code - ' . $productName)
            ->greeting('Hello!')
            ->line('You are trying to vote on ' . $productName . '.')
            ->line('Please use the following verification code to complete your vote:')
            ->line('')
            ->line('**' . $otpCode . '**')
            ->line('')
            ->line('This code will expire in 10 minutes.')
            ->line('')
            ->line('If you did not request this code, you can safely ignore this email.')
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
