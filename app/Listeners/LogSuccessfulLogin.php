<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Wave\ActivityLog;
use Wave\Support\AuthenticationEventLog;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        if (! config('activity.enabled', true) || ! $event->user) {
            return;
        }

        AuthenticationEventLog::once($event, function () use ($event) {
            ActivityLog::forUser($event->user, 'login', 'User logged in successfully', ['guard' => $event->guard]);
        });
    }
}
