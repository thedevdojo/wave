<?php

namespace Wave\Listeners;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ThrottlePasswordLogins
{
    /** Check before either the shared form or SessionGuard validates credentials. */
    public function attempting(Attempting $event): void
    {
        if (! $this->passwordAttempt($event)) {
            return;
        }

        $seconds = 0;
        foreach ([
            $this->accountKey($event->credentials['email']) => max(1, (int) config('wave.auth.login_max_attempts', 5)),
            $this->ipKey() => max(1, (int) config('wave.auth.login_ip_max_attempts', 50)),
        ] as $key => $maximum) {
            if (RateLimiter::tooManyAttempts($key, $maximum)) {
                $seconds = max($seconds, RateLimiter::availableIn($key), 1);
            }
        }
        if ($seconds > 0) {
            event(new Lockout(request()));
            throw ValidationException::withMessages([
                'password' => trans('auth.throttle', ['seconds' => $seconds, 'minutes' => (int) ceil($seconds / 60)]),
            ]);
        }
    }

    public function failed(Failed $event): void
    {
        if (! $this->passwordAttempt($event)) {
            return;
        }
        $decay = max(1, (int) config('wave.auth.login_decay_seconds', 60));
        RateLimiter::hit($this->accountKey($event->credentials['email']), $decay);
        RateLimiter::hit($this->ipKey(), $decay);
    }

    public function login(Login $event): void
    {
        if ($event->guard === 'web' && is_string($event->user->email)) {
            RateLimiter::clear($this->accountKey($event->user->email));
        }
        // Retain the IP-wide failure budget so successes cannot reset password spraying.
    }

    private function passwordAttempt(Attempting|Failed $event): bool
    {
        return $event->guard === 'web'
            && is_string($event->credentials['email'] ?? null)
            && array_key_exists('password', $event->credentials);
    }

    private function accountKey(string $email): string
    {
        return 'wave-password-login:account:'.hash('sha256', Str::transliterate(Str::lower(trim($email))).'|'.request()->ip());
    }

    private function ipKey(): string
    {
        return 'wave-password-login:ip:'.hash('sha256', (string) request()->ip());
    }
}
