<?php

namespace Wave\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! auth()->user()->hasRole('admin')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
