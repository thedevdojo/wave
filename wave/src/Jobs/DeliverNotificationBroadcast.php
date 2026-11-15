<?php

namespace Wave\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Uuid;
use Wave\NotificationBroadcast;
use Wave\Notifications\SiteNotification;

class DeliverNotificationBroadcast implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public string $broadcastId) {}

    public function handle(): void
    {
        $broadcast = NotificationBroadcast::findOrFail($this->broadcastId);
        if ($broadcast->sent_at) {
            return;
        }

        $userModel = config('auth.providers.users.model');
        // Existing users only; model scopes also exclude deleted accounts.
        $userModel::query()->where('created_at', '<=', $broadcast->created_at)->chunkById(200, function ($users) use ($broadcast) {
            $notification = new SiteNotification($broadcast);
            $data = json_encode($notification->toArray($users->first()), JSON_THROW_ON_ERROR);
            $rows = [];
            foreach ($users as $user) {
                $rows[] = [
                    // Stable per-recipient IDs make retries safe, including after a user reads a delivery.
                    'id' => (string) Uuid::v5(Uuid::fromString($broadcast->id), $user->getMorphClass().':'.$user->getKey()),
                    'type' => SiteNotification::class,
                    'notifiable_type' => $user->getMorphClass(),
                    'notifiable_id' => $user->getKey(),
                    'data' => $data,
                    'read_at' => null,
                    'created_at' => $broadcast->created_at,
                    'updated_at' => now(),
                ];
            }
            DB::table('notifications')->insertOrIgnore($rows);
        });
        $broadcast->update(['sent_at' => now()]);
    }
}
