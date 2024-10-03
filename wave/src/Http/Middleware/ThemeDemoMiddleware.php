<?php

namespace Wave\Http\Middleware;

use Closure;

class ThemeDemoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(isset($request->theme)){
            return redirect('/')->withCookie(cookie('theme', $request->theme, 60, null, null, false, false));
        }

        return $next($request);
    }
}