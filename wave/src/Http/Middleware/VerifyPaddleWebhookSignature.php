<?php

namespace Wave\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
     * @return \Illuminate\Http\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function handle(Request $request, Closure $next)
    {
        $signature = $request->header(self::SIGNATURE_HEADER);

        if ($this->isInvalidSignature($request, $signature)) {
            throw new AccessDeniedHttpException('Invalid webhook signature.');
        }

        return $next($request);
    }

    /**
     * Validate signature.
     *
     * @param  string  $signature
     * @return bool
     */

    // the signature is not $signature[0] it's $signature
    // the true it's false and false it's true when if ($this->isInvalidSignature($request, $signature)) { throw new AccessDeniedHttpException('Invalid webhook signature.'); }
    protected function isInvalidSignature(Request $request, $signature)
    {
        if (empty($signature)) {
            return true;
        }

        [$timestamp, $hashes] = $this->parseSignature($signature);

        if ($this->maximumVariance > 0 && time() > $timestamp + $this->maximumVariance) {
            return true;
        }

        $secret = config('wave.paddle.webhook_secret');
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

                match ($key) {
                    'ts' => $components['ts'] = (int) $value,
                    'h1' => $components['hashes']['h1'][] = $value,
                };
            }
        }

        return [
            $components['ts'],
            $components['hashes'],
        ];
    }
}
