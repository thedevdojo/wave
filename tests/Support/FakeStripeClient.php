<?php

namespace Tests\Support;

use Stripe\HttpClient\ClientInterface;

/**
 * Stands in for Stripe's HTTP client so API calls get canned responses.
 */
class FakeStripeClient implements ClientInterface
{
    public array $requests = [];

    private array $responses = [];

    /**
     * Queue a response for "METHOD /v1/path". The last one queued keeps repeating.
     */
    public function respond(string $method, string $path, array $body, int $status = 200): static
    {
        $this->responses[strtolower($method).' '.$path][] = [$status, $body];

        return $this;
    }

    public function request($method, $absUrl, $headers, $params, $hasFile, $apiMode = 'v1', $maxNetworkRetries = null)
    {
        $key = strtolower($method).' '.parse_url($absUrl, PHP_URL_PATH);
        $this->requests[] = ['key' => $key, 'params' => $params];

        $queue = &$this->responses[$key];
        [$status, $body] = match (true) {
            empty($queue) => [404, ['error' => ['message' => "No fake response for {$key}"]]],
            count($queue) > 1 => array_shift($queue),
            default => $queue[0],
        };

        return [json_encode($body), $status, []];
    }

    public function requested(string $method, string $path): array
    {
        return array_values(array_filter($this->requests, fn ($r) => $r['key'] === strtolower($method).' '.$path));
    }
}
