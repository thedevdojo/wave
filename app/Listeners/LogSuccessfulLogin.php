<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Wave\ActivityLog;

class LogSuccessfulLogin
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
    public function handle(Login $event): void
    {
        if (! config('activity.enabled', true) || ! $event->user) {
            return;
        }

        // Prevent duplicate login logs within the same session
        $recentLogin = ActivityLog::where('user_id', $event->user->id)
            ->where('action', 'login')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->exists();

        if (! $recentLogin) {
            ActivityLog::log('login', 'User logged in successfully');
        }
    }
}
