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
        if (! auth()->user()->hasRole('admin')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
