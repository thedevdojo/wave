<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        if (! config('activity.enabled', true) || ! $event->user) {
            return;
        }

        // Prevent duplicate logout logs
        $recentLogout = \App\Models\ActivityLog::where('user_id', $event->user->id)
            ->where('action', 'logout')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->exists();

        if (! $recentLogout) {
            \App\Models\ActivityLog::create([
                'user_id' => $event->user->id,
                'action' => 'logout',
                'description' => 'User logged out',
                'ip_address' => request()->header('CF-Connecting-IP') ?? request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}
