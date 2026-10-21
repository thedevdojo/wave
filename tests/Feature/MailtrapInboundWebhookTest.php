<?php

/**
 * Mailtrap Inbound Email Webhook Tests
 *
 * The webhook body only holds message identifiers, so the controller fetches
 * each message from the Mailtrap API and dispatches MailtrapInboundEmailReceived.
 */

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Wave\Events\MailtrapInboundEmailReceived;

beforeEach(function () {
    config([
        'wave.mailtrap_inbound.api_key' => 'test-api-key',
        'wave.mailtrap_inbound.webhook_secret' => 'test-webhook-secret',
    ]);

    Http::preventStrayRequests();
});

function mailtrapInboundBody(): string
{
    // The raw body as Mailtrap sends it, with "<" and ">" escaped as \u003c and \u003e.
    return '{"events":[{"event":"inbound.message_received","event_id":"00f995a8-6743-55a3-a2eb-bb53eb4704c7","timestamp":1791207458565,"inbox_id":4024,"message_id":"1878217152073113664","from":"Jane Doe \u003cjane@example.com\u003e"}]}';
}

function mailtrapInboundEvent(string $messageId, array $overrides = []): array
{
    return array_merge([
        'event' => 'inbound.message_received',
        'event_id' => 'event-'.$messageId,
        'timestamp' => 1791207458565,
        'inbox_id' => 4024,
        'message_id' => $messageId,
    ], $overrides);
}

function mailtrapInboundMessage(array $overrides = []): array
{
    return array_merge([
        'id' => '1878217152073113664',
        'inbox_id' => 4024,
        'from' => 'Jane Doe <jane@example.com>',
        'to' => ['support@example.inbound-mailtrap.io'],
        'subject' => 'Help with my account',
        'text_body' => 'Hello',
        'html_body' => '<p>Hello</p>',
        'attachments' => [],
        'raw_message_url' => 'https://storage.example.test/message.eml?X-Amz-Signature=download-secret',
    ], $overrides);
}

function mailtrapInboundSignature(string $body, string $secret = 'test-webhook-secret'): string
{
    return hash_hmac('sha256', $body, $secret);
}

function postMailtrapInbound(string $body, ?string $signature = null, bool $withSignature = true): TestResponse
{
    $server = ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];

    if ($withSignature) {
        $server['HTTP_MAILTRAP_SIGNATURE'] = $signature ?? mailtrapInboundSignature($body);
    }

    return test()->call('POST', '/webhook/mailtrap/inbound', [], [], [], $server, $body);
}

test('it fetches the message and dispatches an event', function () {
    Event::fake([MailtrapInboundEmailReceived::class]);
    Http::fake(['mailtrap.io/api/inbound/*' => Http::response(mailtrapInboundMessage())]);

    postMailtrapInbound(mailtrapInboundBody())
        ->assertOk()
        ->assertJson(['message' => 'Webhook handled successfully']);

    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && $request->url() === 'https://mailtrap.io/api/inbound/inboxes/4024/messages/1878217152073113664'
        && $request->hasHeader('Authorization', 'Bearer test-api-key'));

    Event::assertDispatched(MailtrapInboundEmailReceived::class, fn (MailtrapInboundEmailReceived $event) => $event->inboxId === 4024
        && $event->messageId === '1878217152073113664'
        && $event->message['subject'] === 'Help with my account'
        && $event->webhookEvent['event_id'] === '00f995a8-6743-55a3-a2eb-bb53eb4704c7');
})->group('mailtrap', 'webhooks');

test('it rejects an invalid signature', function (string $signature) {
    Event::fake([MailtrapInboundEmailReceived::class]);

    postMailtrapInbound(mailtrapInboundBody(), $signature)->assertStatus(401);

    Http::assertNothingSent();
    Event::assertNotDispatched(MailtrapInboundEmailReceived::class);
})->with([
    'empty' => fn () => '',
    'wrong secret' => fn () => mailtrapInboundSignature(mailtrapInboundBody(), 'wrong-secret'),
    'prefixed' => fn () => 'sha256='.mailtrapInboundSignature(mailtrapInboundBody()),
    'truncated' => fn () => substr(mailtrapInboundSignature(mailtrapInboundBody()), 0, 63),
    'signed re-encoded body' => fn () => mailtrapInboundSignature(json_encode(json_decode(mailtrapInboundBody(), true))),
])->group('mailtrap', 'webhooks');

test('it rejects a request without a signature header', function () {
    postMailtrapInbound(mailtrapInboundBody(), withSignature: false)->assertStatus(401);

    Http::assertNothingSent();
})->group('mailtrap', 'webhooks');

test('it returns 503 when the webhook secret is missing', function () {
    config(['wave.mailtrap_inbound.webhook_secret' => null]);

    postMailtrapInbound(mailtrapInboundBody(), mailtrapInboundSignature(mailtrapInboundBody(), ''))->assertStatus(503);

    Http::assertNothingSent();
})->group('mailtrap', 'webhooks');

test('it returns 503 when the api key is missing', function () {
    config(['wave.mailtrap_inbound.api_key' => '']);

    postMailtrapInbound(mailtrapInboundBody())->assertStatus(503);

    Http::assertNothingSent();
})->group('mailtrap', 'webhooks');

test('it rejects a body without an events list', function (string $body) {
    postMailtrapInbound($body)->assertStatus(422);

    Http::assertNothingSent();
})->with([
    'empty' => '',
    'invalid json' => '{"events":',
    'no events' => '{}',
    'null events' => '{"events":null}',
    'top-level list' => '[]',
    'events object' => '{"events":{"first":{"event":"inbound.message_received"}}}',
    'json lines' => '{"event":"inbound.message_received","inbox_id":4024,"message_id":"m1"}'."\n".'{"event":"inbound.message_received","inbox_id":4024,"message_id":"m2"}',
])->group('mailtrap', 'webhooks');

test('it ignores other events and events without identifiers', function (array $events) {
    Event::fake([MailtrapInboundEmailReceived::class]);

    postMailtrapInbound(json_encode(['events' => $events]))->assertOk();

    Http::assertNothingSent();
    Event::assertNotDispatched(MailtrapInboundEmailReceived::class);
})->with([
    'no events' => [[]],
    'sending event' => [[['event' => 'delivery', 'message_id' => 'm1', 'email' => 'user@example.com']]],
    'no message id' => [[mailtrapInboundEvent('m1', ['message_id' => null])]],
    'string inbox id' => [[mailtrapInboundEvent('m1', ['inbox_id' => '4024'])]],
    'not an object' => [['inbound.message_received']],
])->group('mailtrap', 'webhooks');

test('it returns 503 when the mailtrap api fails', function (int $status) {
    Event::fake([MailtrapInboundEmailReceived::class]);
    Exceptions::fake();
    Http::fake(['mailtrap.io/api/inbound/*' => Http::response(['error' => 'failed'], $status)]);

    postMailtrapInbound(mailtrapInboundBody())->assertStatus(503);

    Event::assertNotDispatched(MailtrapInboundEmailReceived::class);
    Exceptions::assertReported(RequestException::class);
})->with([401, 403, 404, 429, 500])->group('mailtrap', 'webhooks');

test('it returns 503 when the mailtrap api cannot be reached', function () {
    Event::fake([MailtrapInboundEmailReceived::class]);
    Exceptions::fake();
    Http::fake(['mailtrap.io/api/inbound/*' => Http::failedConnection()]);

    postMailtrapInbound(mailtrapInboundBody())->assertStatus(503);

    Event::assertNotDispatched(MailtrapInboundEmailReceived::class);
})->group('mailtrap', 'webhooks');

test('it handles a repeated delivery only once', function () {
    Event::fake([MailtrapInboundEmailReceived::class]);
    Http::fake(['mailtrap.io/api/inbound/*' => Http::response(mailtrapInboundMessage())]);

    postMailtrapInbound(mailtrapInboundBody())->assertOk();
    postMailtrapInbound(mailtrapInboundBody())->assertOk();

    Http::assertSentCount(1);
    Event::assertDispatchedTimes(MailtrapInboundEmailReceived::class, 1);
})->group('mailtrap', 'webhooks');

test('a retried batch fetches only the messages that failed', function () {
    Event::fake([MailtrapInboundEmailReceived::class]);
    Exceptions::fake();
    Http::fake([
        'mailtrap.io/api/inbound/inboxes/4024/messages/m1' => Http::response(mailtrapInboundMessage(['id' => 'm1'])),
        'mailtrap.io/api/inbound/inboxes/4024/messages/m2' => Http::sequence()
            ->push(['error' => 'failed'], 500)
            ->push(mailtrapInboundMessage(['id' => 'm2'])),
    ]);
    $body = json_encode(['events' => [mailtrapInboundEvent('m1'), mailtrapInboundEvent('m2')]]);

    postMailtrapInbound($body)->assertStatus(503);
    postMailtrapInbound($body)->assertOk();

    Http::assertSentCount(3);
    Event::assertDispatchedTimes(MailtrapInboundEmailReceived::class, 2);
})->group('mailtrap', 'webhooks');

test('a failed listener makes mailtrap retry the message', function () {
    Exceptions::fake();
    Http::fake(['mailtrap.io/api/inbound/*' => Http::response(mailtrapInboundMessage())]);
    Event::listen(MailtrapInboundEmailReceived::class, fn () => throw new RuntimeException('Listener failed.'));

    postMailtrapInbound(mailtrapInboundBody())->assertStatus(503);
    postMailtrapInbound(mailtrapInboundBody())->assertStatus(503);

    Http::assertSentCount(2);
    Exceptions::assertReported(fn (RuntimeException $e) => $e->getMessage() === 'Listener failed.');
})->group('mailtrap', 'webhooks');

test('the raw message is downloaded without the api key', function () {
    Http::fake(['storage.example.test/*' => Http::response('raw message')]);

    $event = new MailtrapInboundEmailReceived(4024, '1878217152073113664', mailtrapInboundMessage());

    expect($event->rawMessage())->toBe('raw message');
    Http::assertSent(fn (Request $request) => ! $request->hasHeader('Authorization'));
})->group('mailtrap', 'webhooks');

test('the raw message is only downloaded over https', function (?string $url) {
    Http::fake(['*' => Http::response('raw message')]);

    $event = new MailtrapInboundEmailReceived(4024, 'm1', mailtrapInboundMessage(['raw_message_url' => $url]));

    expect(fn () => $event->rawMessage())->toThrow(RuntimeException::class);
    Http::assertNothingSent();
})->with([
    'missing' => null,
    'http' => 'http://storage.example.test/message.eml',
    'credentials' => 'https://user:pass@storage.example.test/message.eml',
])->group('mailtrap', 'webhooks');

test('a failed raw message download does not expose the signed url', function () {
    Http::fake(['storage.example.test/*' => Http::failedConnection()]);

    $event = new MailtrapInboundEmailReceived(4024, 'm1', mailtrapInboundMessage());

    expect(fn () => $event->rawMessage())->toThrow(function (RuntimeException $e) {
        expect($e->getMessage())->not->toContain('download-secret');
        expect($e->getPrevious())->toBeNull();
    });
})->group('mailtrap', 'webhooks');

test('the webhook is excluded from csrf protection', function () {
    expect(app(ValidateCsrfToken::class)->getExcludedPaths())->toContain('/webhook/mailtrap/inbound');
})->group('mailtrap', 'webhooks');
