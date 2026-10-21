<?php

namespace Wave\Http\Controllers\InboundEmail\Webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;
use Wave\Events\MailtrapInboundEmailReceived;

/**
 * @see https://docs.mailtrap.io/inbound-email/receiving-emails
 */
class MailtrapWebhook extends Controller
{
    public const EVENT_TYPE = 'inbound.message_received';

    public const API_URL = 'https://mailtrap.io/api/inbound/inboxes';

    public function handler(Request $request): JsonResponse
    {
        $apiKey = config('wave.mailtrap_inbound.api_key');

        if (! is_string($apiKey) || trim($apiKey) === '') {
            return response()->json(['message' => 'Mailtrap inbound email is not configured.'], 503);
        }

        // The webhook must use the JSON payload format. JSON Lines bodies fail here.
        $payload = json_decode($request->getContent(), true);

        if (! is_array($payload) || ! is_array($payload['events'] ?? null) || ! array_is_list($payload['events'])) {
            return response()->json(['message' => 'The request must contain a JSON events array.'], 422);
        }

        $failed = false;

        foreach ($payload['events'] as $event) {
            if (! is_array($event) || ($event['event'] ?? null) !== self::EVENT_TYPE) {
                continue;
            }

            $inboxId = $event['inbox_id'] ?? null;
            $messageId = $event['message_id'] ?? null;

            if (! is_int($inboxId) || $inboxId < 1 || ! is_string($messageId) || trim($messageId) === '') {
                Log::warning('Skipped a Mailtrap inbound event without valid message identifiers.');

                continue;
            }

            // Mailtrap retries the whole batch after a failure, so skip messages that were already handled.
            $cacheKey = 'mailtrap_inbound_'.hash('sha256', $inboxId.':'.$messageId);

            if (Cache::has($cacheKey)) {
                continue;
            }

            try {
                $message = $this->fetchMessage($apiKey, $inboxId, $messageId);

                MailtrapInboundEmailReceived::dispatch($inboxId, $messageId, $message, $event);

                Cache::put($cacheKey, true, now()->addHours(24));
            } catch (Throwable $e) {
                report($e);
                $failed = true;
            }
        }

        // Any response other than 200 makes Mailtrap retry the webhook.
        if ($failed) {
            return response()->json(['message' => 'Some messages could not be processed.'], 503);
        }

        return response()->json(['message' => 'Webhook handled successfully'], 200);
    }

    /**
     * Get the message details from the Mailtrap API.
     */
    protected function fetchMessage(string $apiKey, int $inboxId, string $messageId): array
    {
        $message = Http::withToken($apiKey)
            ->acceptJson()
            ->connectTimeout(5)
            ->timeout(10)
            ->withoutRedirecting()
            ->get(self::API_URL.'/'.$inboxId.'/messages/'.rawurlencode($messageId))
            ->throw()
            ->json();

        if (! is_array($message)) {
            throw new RuntimeException('The Mailtrap API returned an invalid message.');
        }

        return $message;
    }
}
