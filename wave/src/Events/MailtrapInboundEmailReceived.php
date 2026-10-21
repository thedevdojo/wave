<?php

namespace Wave\Events;

use GuzzleHttp\Exception\TransferException;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MailtrapInboundEmailReceived
{
    use Dispatchable;

    /**
     * @param  array<string, mixed>  $message  The message from the Mailtrap API: from, to, subject, text_body, html_body, attachments, raw_message_url...
     * @param  array<string, mixed>  $webhookEvent  The event from the webhook payload.
     */
    public function __construct(
        public int $inboxId,
        public string $messageId,
        public array $message,
        public array $webhookEvent = [],
    ) {}

    /**
     * Download the original message as an .eml string.
     *
     * The download URL expires one hour after Mailtrap created it.
     */
    public function rawMessage(): string
    {
        $url = $this->message['raw_message_url'] ?? null;
        $parts = is_string($url) ? parse_url($url) : false;

        if (! is_array($parts) || ($parts['scheme'] ?? '') !== 'https' || empty($parts['host'])
            || isset($parts['user']) || isset($parts['pass'])) {
            throw new RuntimeException('The Mailtrap raw message URL is missing or does not use HTTPS.');
        }

        try {
            // The signed URL is the credential, so the API key is not sent with this request.
            $response = Http::connectTimeout(5)->timeout(20)->withoutRedirecting()->get($url);
        } catch (ConnectionException|TransferException) {
            // The transport error contains the signed URL, so it is not passed on.
            throw new RuntimeException('The Mailtrap raw message download failed.');
        }

        if (! $response->successful()) {
            throw new RuntimeException('The Mailtrap raw message download returned HTTP '.$response->status().'.');
        }

        return $response->body();
    }
}
