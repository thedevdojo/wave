<?php

namespace Wave\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Use cached admin check from User model
        if (! $user->isAdmin()) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
