<?php

namespace Wave\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Code modifed from: https://github.com/laravel/cashier-paddle/blob/2.x/src/Http/Middleware/VerifyWebhookSignature.php
 *
 * @see https://developer.paddle.com/webhook-reference/verifying-webhooks
 */
class VerifyPaddleWebhookSignature
{
    public const SIGNATURE_HEADER = 'Paddle-Signature';

    public const HASH_ALGORITHM_1 = 'h1';

    protected ?int $maximumVariance = 5;

    /**
     * Handle the incoming request.
     *
     *
     * @throws AccessDeniedHttpException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header(self::SIGNATURE_HEADER);

        if ($this->isInvalidSignature($request, $signature)) {
            throw new AccessDeniedHttpException('Invalid webhook signature.');
        }

        return $next($request);
    }

    /**
     * Validate signature.
     */
    protected function isInvalidSignature(Request $request, ?string $signature): bool
    {
        $secret = config('wave.paddle.webhook_secret');

        // Without a secret anyone could sign a payload, so fail closed.
        if (empty($secret)) {
            Log::warning('Rejected a Paddle webhook because PADDLE_WEBHOOK_SECRET is not set.');

            return true;
        }

        if (empty($signature)) {
            return true;
        }

        [$timestamp, $hashes] = $this->parseSignature($signature);

        if ($this->maximumVariance > 0 && time() > $timestamp + $this->maximumVariance) {
            return true;
        }

        $data = $request->getContent();

        foreach ($hashes as $hashAlgorithm => $possibleHashes) {
            $hash = match ($hashAlgorithm) {
                'h1' => hash_hmac('sha256', "{$timestamp}:{$data}", $secret),
            };

            foreach ($possibleHashes as $possibleHash) {
                if (hash_equals($hash, $possibleHash)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Parse the signature header.
     */
    public function parseSignature(string $header): array
    {
        $components = [
            'ts' => 0,
            'hashes' => [],
        ];

        foreach (explode(';', $header) as $part) {
            if (str_contains($part, '=')) {
                [$key, $value] = explode('=', $part, 2);

                // Paddle may add new keys; unknown ones are skipped.
                match ($key) {
                    'ts' => $components['ts'] = (int) $value,
                    'h1' => $components['hashes']['h1'][] = $value,
                    default => null,
                };
            }
        }

        return [
            $components['ts'],
            $components['hashes'],
        ];
    }
}
