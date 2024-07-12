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
    public function handle(\Illuminate\Http\Request $request, Closure $next)
    {
        // if we are not on the install route
        if($request->path() != 'install'){

            try {
                $user = User::first();
            } catch (\Illuminate\Database\QueryException $e) {
                
                return redirect()->route('wave.install');
                
            }

            if(User::first() === null){
                return redirect()->route('wave.install');
            }
        }

        return $next($request);
    }
}
