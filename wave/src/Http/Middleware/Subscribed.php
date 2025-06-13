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
        if (Auth::check() && (auth()->user()->subscriber() || auth()->user()->hasRole('admin'))) {
            return $next($request);
        }

        return redirect()->route('billing');
    }
}
