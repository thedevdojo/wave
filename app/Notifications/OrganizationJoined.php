<?php

namespace App\Notifications;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrganizationJoined extends Notification
{
    use Queueable;

    protected $user;
    protected $organization;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Organization $organization)
    {
        $this->user = $user;
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
            'icon' => $this->user->avatar(),
            'body' => 'has joined your organization',
            'title' => $this->organization->name,
            'link' => route('settings.organization'),
            'user' => [
                'name' => $this->user->name,
                'username' => $this->user->username,
            ],
        ];
    }
}
