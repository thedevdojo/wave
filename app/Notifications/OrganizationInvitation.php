<?php

namespace App\Notifications;

use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrganizationInvitation extends Notification
{
    use Queueable;

    protected $invitation;
    protected $admin;
    protected $organization;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invitation $invitation, User $admin, Organization $organization)
    {
        $this->invitation = $invitation;
        $this->admin = $admin;
        $this->organization = $organization;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'icon' => $this->admin->avatar(),
            'body' => 'has invited you to join their organization',
            'title' => $this->organization->name,
            'link' => route('organizations.invitation', ['id' => $this->invitation->id]),
            'user' => [
                'name' => $this->admin->name,
                'username' => $this->admin->username,
            ],
        ];
    }
}
