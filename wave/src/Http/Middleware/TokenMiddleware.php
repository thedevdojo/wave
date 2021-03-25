<?php

namespace Wave\Http\Middleware;

use Closure;
//use Illuminate\Support\Facades\Auth;
use Wave\ApiToken;
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($request->token && strlen($request->token) <= 60){
            $api_token = ApiToken::where('token', '=', $request->token)->first();
            if(isset($api_token->id)){
                $token = JWTAuth::fromUser($api_token->user);
            }

        } else {
            $this->auth->authenticate();
        }


        //Then process the next request if every tests passed.
        return $next($request);
    }
}
