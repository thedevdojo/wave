<?php

namespace Wave\Http\Middleware;

use Closure;

class TrialEnded
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

        if( intval(setting('billing.trial_days', 0)) > 0 && !setting('billing.card_upfront') && auth()->user()->daysLeftOnTrial() < 1 ){
            if(auth()->user()->role->name == 'trial' && ($request->route()->getName() != 'wave.trial_over' && $request->route()->getName() != 'wave.settings') ){
                return redirect()->route('wave.trial_over');
            }
        }

        return $next($request);
    }
}
