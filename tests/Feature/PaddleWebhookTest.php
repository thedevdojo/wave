<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Wave\Plan;
use Wave\Subscription;

function paddleSignature(string $body, ?int $timestamp = null, string $secret = 'pdl_test_secret'): string
{
    $timestamp ??= time();

    return "ts={$timestamp};h1=".hash_hmac('sha256', "{$timestamp}:{$body}", $secret);
}

function postPaddleWebhook(array $payload, ?string $signature = null)
{
    $body = json_encode($payload);
    $server = ['CONTENT_TYPE' => 'application/json'];

    if ($signature !== '') {
        $server['HTTP_PADDLE_SIGNATURE'] = $signature ?? paddleSignature($body);
    }

    return test()->call('POST', '/webhook/paddle', [], [], [], $server, $body);
}

function cancelledPayload(string $subscriptionId): array
{
    return [
        'event_id' => 'evt_01hv8x2acma0avvd3kv2dfzsep',
        'event_type' => 'subscription.canceled',
        'occurred_at' => now()->toIso8601String(),
        'data' => ['id' => $subscriptionId, 'status' => 'canceled'],
    ];
}

beforeEach(function () {
    config(['wave.paddle.webhook_secret' => 'pdl_test_secret']);

    $this->user = User::factory()->create();
    $this->user->syncRoles('premium');

    $plan = Plan::create([
        'name' => 'Premium',
        'description' => 'Premium plan',
        'features' => 'Feature 1',
        'monthly_price' => '10.00',
        'monthly_price_id' => 'pri_monthly',
        'active' => true,
        'role_id' => Role::findByName('premium')->id,
    ]);

    $this->subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $plan->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_01hv8x29kz0t586xy6zn1a62ny',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);
});

it('cancels the subscription on a signed subscription.canceled event', function () {
    postPaddleWebhook(cancelledPayload('sub_01hv8x29kz0t586xy6zn1a62ny'))->assertOk();

    expect($this->subscription->fresh()->status)->toBe('cancelled')
        ->and($this->user->fresh()->hasRole('registered'))->toBeTrue();
});

it('stops treating the user as a subscriber straight away', function () {
    expect($this->user->subscriber())->toBeTrue();

    postPaddleWebhook(cancelledPayload('sub_01hv8x29kz0t586xy6zn1a62ny'))->assertOk();

    expect($this->user->fresh()->subscriber())->toBeFalse();
});

it('acknowledges a redelivered cancellation', function () {
    $payload = cancelledPayload('sub_01hv8x29kz0t586xy6zn1a62ny');

    postPaddleWebhook($payload)->assertOk();
    postPaddleWebhook($payload)->assertOk();
});

it('acknowledges a cancellation for an unknown subscription', function () {
    postPaddleWebhook(cancelledPayload('sub_unknown'))->assertOk();

    expect($this->subscription->fresh()->status)->toBe('active');
});

it('acknowledges events it does not handle', function () {
    postPaddleWebhook([
        'event_type' => 'transaction.completed',
        'data' => ['id' => 'txn_01hv8wptq8987qeep44cyrewp9'],
    ])->assertOk();

    expect($this->subscription->fresh()->status)->toBe('active');
});

it('rejects a request without a signature', function () {
    postPaddleWebhook(cancelledPayload('sub_01hv8x29kz0t586xy6zn1a62ny'), '')->assertForbidden();

    expect($this->subscription->fresh()->status)->toBe('active');
});

it('rejects a request signed with the wrong secret', function () {
    $payload = cancelledPayload('sub_01hv8x29kz0t586xy6zn1a62ny');

    postPaddleWebhook($payload, paddleSignature(json_encode($payload), secret: 'wrong'))->assertForbidden();

    expect($this->subscription->fresh()->status)->toBe('active');
});

it('rejects a request whose body was changed after signing', function () {
    $signature = paddleSignature(json_encode(cancelledPayload('sub_other')));

    postPaddleWebhook(cancelledPayload('sub_01hv8x29kz0t586xy6zn1a62ny'), $signature)->assertForbidden();
});

it('rejects a stale signature', function () {
    $payload = cancelledPayload('sub_01hv8x29kz0t586xy6zn1a62ny');

    postPaddleWebhook($payload, paddleSignature(json_encode($payload), time() - 60))->assertForbidden();

    expect($this->subscription->fresh()->status)->toBe('active');
});

it('rejects a malformed signature header', function () {
    postPaddleWebhook(cancelledPayload('sub_01hv8x29kz0t586xy6zn1a62ny'), 'garbage')->assertForbidden();
});

it('ignores signature components it does not know', function () {
    $payload = cancelledPayload('sub_01hv8x29kz0t586xy6zn1a62ny');

    postPaddleWebhook($payload, paddleSignature(json_encode($payload)).';h2=abc')->assertOk();

    expect($this->subscription->fresh()->status)->toBe('cancelled');
});

it('rejects everything when no webhook secret is configured', function () {
    Log::spy();
    config(['wave.paddle.webhook_secret' => '']);
    $payload = cancelledPayload('sub_01hv8x29kz0t586xy6zn1a62ny');

    postPaddleWebhook($payload, paddleSignature(json_encode($payload), secret: ''))->assertForbidden();

    expect($this->subscription->fresh()->status)->toBe('active');
    Log::shouldHaveReceived('warning')->withArgs(fn ($message) => str_contains($message, 'PADDLE_WEBHOOK_SECRET'))->once();
});
