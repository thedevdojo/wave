<?php

namespace Wave\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
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
