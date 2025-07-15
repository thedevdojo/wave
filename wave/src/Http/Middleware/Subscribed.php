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
        $user = auth()->user();
        
        if (Auth::check() && ($user->subscriber() || $user->isAdmin())) {
            return $next($request);
        }

        return redirect()->route('billing');
    }
}
