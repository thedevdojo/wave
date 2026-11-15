<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Wave\ActivityLog;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        if (! config('activity.enabled', true) || ! $event->user) {
            return;
        }

        ActivityLog::forUser($event->user, 'logout', 'User logged out', ['guard' => $event->guard]);
    }
}
