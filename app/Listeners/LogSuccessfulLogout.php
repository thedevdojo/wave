<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Wave\ActivityLog;
use Wave\Support\AuthenticationEventLog;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        if (! config('activity.enabled', true) || ! $event->user) {
            return;
        }

        AuthenticationEventLog::once($event, function () use ($event) {
            ActivityLog::forUser($event->user, 'logout', 'User logged out', ['guard' => $event->guard]);
        });
    }
}
