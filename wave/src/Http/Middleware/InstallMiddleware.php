<?php

namespace Wave\Http\Middleware;

use Closure;
use Wave\User;

class InstallMiddleware
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

        // If there are no users in the database we need to run the install script
        if(User::all()->count() < 1){
            if( $request->route()->getName() != 'wave.install' ){
                return redirect()->route('wave.install');
            }
        }
        return $next($request);
    }
}
