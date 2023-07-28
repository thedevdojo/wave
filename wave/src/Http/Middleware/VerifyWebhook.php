<?php

namespace Wave\Http\Middleware;

use Closure;
use InvalidArgumentException;

class VerifyWebhook
{
    /**
     * Handle an incoming webhook request.
     *
     * @see https://developer.paddle.com/webhook-reference/ZG9jOjI1MzUzOTg2-verifying-webhooks
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $signature = $request->get('p_signature');
        $fields = $request->except('p_signature');

        ksort($fields);

        foreach ($fields as $k => $v) {
            if (!in_array(gettype($v), array('object', 'array'))) {
                $fields[$k] = "$v";
            }
        }

        if (openssl_verify(
            serialize($fields),
            base64_decode($signature),
            openssl_get_publickey(config('wave.paddle.public_key')),
            OPENSSL_ALGO_SHA1
        ) !== 1) {
            throw new InvalidArgumentException('Webhook signature is invalid.');
        }

        return $next($request);
    }
}
