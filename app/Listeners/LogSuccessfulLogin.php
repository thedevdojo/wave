<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Wave\ActivityLog;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        if (! config('activity.enabled', true) || ! $event->user) {
            return;
        }

        ActivityLog::forUser($event->user, 'login', 'User logged in successfully', ['guard' => $event->guard]);
    }
}
