<?php

namespace Wave\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login'));
        }

        $user = auth()->user();

        if ($user->subscriber() || $user->isAdmin()) {
            return $next($request);
        }

        return redirect()->route('billing');
    }
}
