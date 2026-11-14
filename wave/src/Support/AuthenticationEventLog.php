<?php

namespace Wave\Support;

use Closure;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use WeakMap;

class AuthenticationEventLog
{
    /** Deduplicate repeated handling of one event, never separate transitions. */
    public static function once(Login|Logout $event, Closure $write): void
    {
        $request = request();
        $key = 'wave.authentication_logged_events';
        $handled = $request->attributes->get($key);
        if (! $handled instanceof WeakMap) {
            $handled = new WeakMap;
            $request->attributes->set($key, $handled);
        }
        if (isset($handled[$event])) {
            return;
        }

        $write();
        // Failed writes remain retryable. No state survives the current request.
        $handled[$event] = true;
    }
}
