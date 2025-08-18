<?php

namespace Wave\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
//use Illuminate\Support\Facades\Auth;
use Wave\ApiKey;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Contracts\Auth\Factory as Auth;

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
     * @param Request $request
     * @param Closure $next
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


        //Then process the next request if every tests passed.
        return $next($request);
    }
}