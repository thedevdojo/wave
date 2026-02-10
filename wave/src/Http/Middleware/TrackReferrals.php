<?php

namespace Wave\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Wave\Referral;

class TrackReferrals
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if there's a referral code in the query string
        if ($request->has('ref')) {
            $code = $request->query('ref');

            // Validate and find the referral
            $referral = Referral::where('code', $code)
                ->where('status', 'active')
                ->first();

            if ($referral) {
                // Store referral code in cookie for 30 days
                cookie()->queue(
                    cookie('referral_code', $code, 60 * 24 * 30, null, null, false, false)
                );

                // Increment click count
                $referral->incrementClicks();
            }
        }

        return $next($request);
    }
}
