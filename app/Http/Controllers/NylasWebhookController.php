<?php

namespace App\Http\Controllers;

use App\Models\NylasWebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NylasWebhookController extends Controller
{
    /**
     * Handle the Nylas webhook handshake and event payloads.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        // 1. Challenge Handshake Verification (GET request)
        if ($request->isMethod('get') && $request->has('challenge')) {
            $challenge = $request->query('challenge');
            Log::info('Nylas Webhook Handshake challenge received: ' . $challenge);

            return response($challenge, 200)
                ->header('Content-Type', 'text/plain');
        }

        // 2. Webhook Event Handling (POST request)
        $signature = $request->header('X-Nylas-Signature');
        $webhookSecret = config('services.nylas.webhook_secret') ?? env('NYLAS_WEBHOOK_SECRET');

        if (!$signature) {
            Log::warning('Nylas Webhook: Missing X-Nylas-Signature header.');
            return response()->json(['error' => 'Missing signature'], 401);
        }

        $rawBody = $request->getContent();

        // If webhook secret is configured, we verify the signature to prevent spoofing
        if ($webhookSecret) {
            $calculatedSignature = hash_hmac('sha256', $rawBody, $webhookSecret);

            if (!hash_equals($signature, $calculatedSignature)) {
                Log::warning('Nylas Webhook: Signature verification failed.', [
                    'received' => $signature,
                    'calculated' => $calculatedSignature
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        } else {
            Log::warning('Nylas Webhook: Webhook Secret is not configured, skipping verification.');
        }

        $payload = json_decode($rawBody, true);

        if (!$payload) {
            Log::warning('Nylas Webhook: Invalid JSON payload.');
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        // Nylas v3 webhook payload formats:
        // Individual cloud event or an array of cloud events.
        // Let's store the event(s) in our database.
        Log::info('Nylas Webhook event received', ['payload' => $payload]);

        if (isset($payload['type'])) {
            // Single event
            $this->logEvent($payload);
        } elseif (is_array($payload)) {
            // Array of events
            foreach ($payload as $event) {
                if (is_array($event) && isset($event['type'])) {
                    $this->logEvent($event);
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Store the webhook event in the database.
     *
     * @param array $event
     * @return void
     */
    protected function logEvent(array $event): void
    {
        $eventType = $event['type'] ?? 'unknown';
        $data = $event['data'] ?? [];
        $grantId = $data['grant_id'] ?? null;

        NylasWebhookEvent::create([
            'event_type' => $eventType,
            'grant_id' => $grantId,
            'payload' => $event,
        ]);
    }
}
