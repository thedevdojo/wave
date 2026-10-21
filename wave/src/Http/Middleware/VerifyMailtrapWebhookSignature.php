<?php

namespace Wave\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @see https://docs.mailtrap.io/inbound-email/webhooks
 */
class VerifyMailtrapWebhookSignature
{
    public const SIGNATURE_HEADER = 'Mailtrap-Signature';

    /**
     * Handle the incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('wave.mailtrap_inbound.webhook_secret');

        if (! is_string($secret) || trim($secret) === '') {
            return response()->json(['message' => 'The Mailtrap webhook secret is not configured.'], 503);
        }

        // Mailtrap signs the raw request body with HMAC-SHA256 and sends the hex digest.
        $signature = $request->header(self::SIGNATURE_HEADER);

        if (! is_string($signature) || strlen($signature) !== 64
            || ! hash_equals(hash_hmac('sha256', $request->getContent(), $secret), $signature)) {
            return response()->json(['message' => 'Invalid webhook signature.'], 401);
        }

        return $next($request);
    }
}
