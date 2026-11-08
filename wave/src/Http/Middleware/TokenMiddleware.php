<?php

namespace Wave\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Wave\ApiKey;

class TokenMiddleware
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Authenticate the request on the api guard, either with an API key
     * passed as ?token= or with a JWT.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $guard = $this->auth->guard('api');

        if ($request->token && strlen($request->token) <= 60) {
            $user = ApiKey::where('key', $request->token)->first()?->user;

            if (! $user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $guard->setUser($user);
        } else {
            $guard->authenticate();
        }

        $this->auth->shouldUse('api');

        return $next($request);
    }
}
