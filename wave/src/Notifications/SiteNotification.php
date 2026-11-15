<?php

namespace Wave\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Wave\NotificationBroadcast;

class SiteNotification extends Notification
{
    public function __construct(public NotificationBroadcast $broadcast) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'broadcast_id' => $this->broadcast->id,
            'title' => $this->broadcast->title,
            'category' => $this->broadcast->category,
            'body' => Str::sanitizeHtml($this->broadcast->body),
            'format' => 'html',
            'user' => ['name' => config('wave.notifications.team_name', config('app.name').' Team')],
            'icon' => url(config('wave.notifications.team_avatar', '/storage/demo/default.png')),
        ];
    }
}
