<?php

namespace Wave\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Wave\ApiKey;

class TokenMiddleware
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->token && strlen($request->token) <= 60) {
            $apiKey = ApiKey::where('key', $request->token)->first();
            if (isset($apiKey->id)) {
                $token = JWTAuth::fromUser($apiKey->user);
            }

        } else {
            $this->auth->authenticate();
        }

        // Then process the next request if every tests passed.
        return $next($request);
    }
}
