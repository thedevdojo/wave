<?php

namespace Wave\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class ThemeDemoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (isset($request->theme)) {
            return redirect()->to('/')->withCookie(cookie('theme', $request->theme, 60, null, null, false, false));
        }

        return $next($request);
    }
}
