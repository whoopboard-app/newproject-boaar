<?php

namespace App\Notifications;

use App\Models\TeamInvitation;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamInvitationNotification extends Notification
{

    public $invitation;

    /**
     * Create a new notification instance.
     */
    public function __construct(TeamInvitation $invitation)
    {
        $this->invitation = $invitation;
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
        $inviter = $this->invitation->inviter;
        $team = $this->invitation->team;
        $role = ucfirst(str_replace('_', ' ', $this->invitation->role));
        $expiresAt = $this->invitation->expires_at->format('F j, Y');

        $acceptUrl = route('team.invitation.accept', $this->invitation->token);

        return (new MailMessage)
            ->subject('You\'ve been invited to join ' . $team->name)
            ->greeting('Hello!')
            ->line($inviter->name . ' has invited you to join **' . $team->name . '**.')
            ->line('You have been assigned the role of **' . $role . '**.')
            ->line('')
            ->line('Click the button below to accept this invitation and create your account (or join with your existing account if you already have one).')
            ->action('Accept Invitation', $acceptUrl)
            ->line('')
            ->line('This invitation will expire on **' . $expiresAt . '**.')
            ->line('If you did not expect this invitation, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'team_id' => $this->invitation->team_id,
            'team_name' => $this->invitation->team->name,
            'inviter_name' => $this->invitation->inviter->name,
            'role' => $this->invitation->role,
        ];
    }
}
