<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Stripe\ApiRequestor;
use Tests\Support\FakeStripeClient;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    config([
        'wave.stripe.secret_key' => 'sk_test_fake',
        'wave.stripe.webhook_secret' => 'whsec_test',
    ]);

    $this->stripe = new FakeStripeClient;
    ApiRequestor::setHttpClient($this->stripe);

    $this->basicPlan = Plan::create([
        'name' => 'Basic',
        'features' => '',
        'monthly_price_id' => 'price_basic_month',
        'yearly_price_id' => 'price_basic_year',
        'active' => true,
        'role_id' => Role::findByName('basic')->id,
    ]);

    $this->premiumPlan = Plan::create([
        'name' => 'Premium',
        'features' => '',
        'monthly_price_id' => 'price_premium_month',
        'yearly_price_id' => 'price_premium_year',
        'active' => true,
        'role_id' => Role::findByName('premium')->id,
    ]);

    $this->user = User::factory()->create();
    $this->user->syncRoles(['registered']);
});

afterEach(function () {
    ApiRequestor::setHttpClient(null);
});

function sendStripeEvent(string $type, array $object, ?string $payload = null)
{
    $payload ??= json_encode([
        'id' => 'evt_'.uniqid(),
        'object' => 'event',
        'type' => $type,
        'data' => ['object' => $object],
    ]);

    $timestamp = time();
    $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", config('wave.stripe.webhook_secret'));

    return test()->call('POST', '/webhook/stripe', server: [
        'HTTP_STRIPE_SIGNATURE' => "t={$timestamp},v1={$signature}",
        'CONTENT_TYPE' => 'application/json',
    ], content: $payload);
}

function checkoutSession(array $overrides = []): array
{
    return array_merge([
        'id' => 'cs_test_1',
        'object' => 'checkout.session',
        'payment_status' => 'paid',
        'customer' => 'cus_1',
        'subscription' => 'sub_1',
        'metadata' => [
            'billable_type' => 'user',
            'billable_id' => (string) test()->user->id,
            'plan_id' => (string) test()->premiumPlan->id,
            'billing_cycle' => 'month',
        ],
    ], $overrides);
}

function stripeSubscription(array $overrides = []): array
{
    return array_merge([
        'id' => 'sub_1',
        'object' => 'subscription',
        'status' => 'active',
        'cancel_at' => null,
        'plan' => ['id' => 'price_premium_month', 'interval' => 'month'],
    ], $overrides);
}

function localStripeSubscription(Plan $plan, array $attributes = []): Subscription
{
    $user = test()->user;
    $user->syncRoles([$plan->role->name]);

    return Subscription::create(array_merge([
        'billable_type' => 'user',
        'billable_id' => $user->id,
        'plan_id' => $plan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_1',
        'vendor_subscription_id' => 'sub_1',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ], $attributes));
}

it('rejects a request with a bad signature', function () {
    $this->call('POST', '/webhook/stripe', server: ['HTTP_STRIPE_SIGNATURE' => 't=1,v1=bad'], content: '{}')
        ->assertStatus(400);
});

it('rejects a signed payload that is not valid JSON', function () {
    sendStripeEvent('', [], payload: 'not json')->assertStatus(400);
});

it('creates the subscription and assigns the plan role when checkout completes', function () {
    $this->stripe->respond('get', '/v1/checkout/sessions/cs_test_1', checkoutSession());

    sendStripeEvent('checkout.session.completed', ['id' => 'cs_test_1'])->assertOk();

    $subscription = Subscription::where('vendor_subscription_id', 'sub_1')->sole();
    expect($subscription->billable_id)->toBe($this->user->id)
        ->and($subscription->plan_id)->toBe($this->premiumPlan->id)
        ->and($subscription->vendor_customer_id)->toBe('cus_1')
        ->and($subscription->status)->toBe('active')
        ->and($this->user->fresh()->getRoleNames()->all())->toBe(['premium']);
});

it('does not create a second subscription when the event is delivered twice', function () {
    $this->stripe->respond('get', '/v1/checkout/sessions/cs_test_1', checkoutSession());

    sendStripeEvent('checkout.session.completed', ['id' => 'cs_test_1'])->assertOk();
    sendStripeEvent('checkout.session.completed', ['id' => 'cs_test_1'])->assertOk();

    expect(Subscription::count())->toBe(1);
});

it('fulfills an async payment once it succeeds', function () {
    $this->stripe
        ->respond('get', '/v1/checkout/sessions/cs_test_1', checkoutSession(['payment_status' => 'unpaid']))
        ->respond('get', '/v1/checkout/sessions/cs_test_1', checkoutSession());

    sendStripeEvent('checkout.session.completed', ['id' => 'cs_test_1'])->assertOk();
    expect(Subscription::count())->toBe(0);

    sendStripeEvent('checkout.session.async_payment_succeeded', ['id' => 'cs_test_1'])->assertOk();

    expect(Subscription::where('vendor_subscription_id', 'sub_1')->exists())->toBeTrue()
        ->and($this->user->fresh()->hasRole('premium'))->toBeTrue();
});

it('fulfills the checkout when Stripe retries after a failed attempt', function () {
    $this->stripe
        ->respond('get', '/v1/checkout/sessions/cs_test_1', ['error' => ['message' => 'Server error']], 500)
        ->respond('get', '/v1/checkout/sessions/cs_test_1', checkoutSession());

    sendStripeEvent('checkout.session.completed', ['id' => 'cs_test_1'])->assertServerError();
    sendStripeEvent('checkout.session.completed', ['id' => 'cs_test_1'])->assertOk();

    expect(Subscription::where('vendor_subscription_id', 'sub_1')->exists())->toBeTrue()
        ->and($this->user->fresh()->hasRole('premium'))->toBeTrue();
});

it('leaves the user untouched when fulfillment fails part way', function () {
    // A role from another guard can't be assigned to a web user, so this throws.
    $this->premiumPlan->update(['role_id' => Role::create(['name' => 'api_only', 'guard_name' => 'api'])->id]);
    $this->stripe->respond('get', '/v1/checkout/sessions/cs_test_1', checkoutSession());

    sendStripeEvent('checkout.session.completed', ['id' => 'cs_test_1'])->assertServerError();

    expect(Subscription::count())->toBe(0)
        ->and($this->user->fresh()->getRoleNames()->all())->toBe(['registered']);
});

it('switches plans when the subscription price changes', function () {
    $subscription = localStripeSubscription($this->basicPlan);

    sendStripeEvent('customer.subscription.updated', stripeSubscription([
        'plan' => ['id' => 'price_premium_year', 'interval' => 'year'],
    ]))->assertOk();

    $subscription->refresh();
    expect($subscription->plan_id)->toBe($this->premiumPlan->id)
        ->and($subscription->cycle)->toBe('year')
        ->and($this->user->fresh()->getRoleNames()->all())->toBe(['premium']);
});

it('records a scheduled cancellation and clears it on resume', function () {
    $subscription = localStripeSubscription($this->premiumPlan);
    $cancelAt = now()->addMonth()->startOfSecond();

    sendStripeEvent('customer.subscription.updated', stripeSubscription(['cancel_at' => $cancelAt->timestamp]))->assertOk();
    expect($subscription->fresh()->ends_at->equalTo($cancelAt))->toBeTrue();

    sendStripeEvent('customer.subscription.updated', stripeSubscription())->assertOk();
    expect($subscription->fresh()->ends_at)->toBeNull();
});

it('still applies the update when the price is not mapped to a plan', function () {
    Log::spy();
    $subscription = localStripeSubscription($this->premiumPlan);
    $cancelAt = now()->addMonth()->startOfSecond();

    sendStripeEvent('customer.subscription.updated', stripeSubscription([
        'cancel_at' => $cancelAt->timestamp,
        'plan' => ['id' => 'price_unknown', 'interval' => 'month'],
    ]))->assertOk();

    $subscription->refresh();
    expect($subscription->plan_id)->toBe($this->premiumPlan->id)
        ->and($subscription->ends_at->equalTo($cancelAt))->toBeTrue()
        ->and($this->user->fresh()->hasRole('premium'))->toBeTrue();
    Log::shouldHaveReceived('warning')->once();
});

it('cancels the subscription when Stripe reports a terminal status', function (string $status) {
    $subscription = localStripeSubscription($this->premiumPlan);

    sendStripeEvent('customer.subscription.updated', stripeSubscription(['status' => $status]))->assertOk();

    expect($subscription->fresh()->status)->toBe('cancelled')
        ->and($this->user->fresh()->getRoleNames()->all())->toBe(['registered']);
})->with(['canceled', 'incomplete_expired']);

it('keeps access while Stripe retries a past due payment', function () {
    $subscription = localStripeSubscription($this->premiumPlan);

    sendStripeEvent('customer.subscription.updated', stripeSubscription(['status' => 'past_due']))->assertOk();

    expect($subscription->fresh()->status)->toBe('active')
        ->and($this->user->fresh()->hasRole('premium'))->toBeTrue();
});

it('cancels the subscription when it is deleted in Stripe', function () {
    $subscription = localStripeSubscription($this->premiumPlan);

    sendStripeEvent('customer.subscription.deleted', stripeSubscription(['status' => 'canceled']))->assertOk();

    expect($subscription->fresh()->status)->toBe('cancelled')
        ->and($this->user->fresh()->getRoleNames()->all())->toBe(['registered']);
});

it('ignores events for a subscription that is already cancelled', function (string $type) {
    localStripeSubscription($this->basicPlan, ['status' => 'cancelled']);
    localStripeSubscription($this->premiumPlan, ['vendor_subscription_id' => 'sub_2']);

    sendStripeEvent($type, stripeSubscription([
        'plan' => ['id' => 'price_basic_month', 'interval' => 'month'],
    ]))->assertOk();

    expect($this->user->fresh()->getRoleNames()->all())->toBe(['premium']);
})->with(['customer.subscription.updated', 'customer.subscription.deleted']);
