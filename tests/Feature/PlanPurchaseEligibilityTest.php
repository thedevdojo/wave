<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Stripe\ApiRequestor;
use Tests\Support\FakeStripeClient;
use Wave\Http\Livewire\Billing\Checkout;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    config(['wave.billing_provider' => 'stripe', 'wave.stripe.secret_key' => 'sk_test_fake', 'wave.paddle.env' => 'sandbox']);
    $this->stripe = new FakeStripeClient;
    ApiRequestor::setHttpClient($this->stripe);
    Http::fake();
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->plan = Plan::create([
        'name' => 'Eligible plan', 'active' => true, 'features' => '',
        'monthly_price_id' => 'price_month', 'yearly_price_id' => 'price_year',
        'role_id' => Role::findByName('premium')->id,
    ]);
    $this->subscription = Subscription::create([
        'billable_type' => 'user', 'billable_id' => $this->user->id,
        'plan_id' => $this->plan->id, 'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_current', 'cycle' => 'month', 'status' => 'active', 'seats' => 1,
    ]);
});

afterEach(function () {
    ApiRequestor::setHttpClient(null);
});

function eligibilityCheckout(string $action)
{
    config(['wave.billing_provider' => $action === 'redirectToStripeCheckout' ? 'stripe' : 'paddle']);

    return Livewire::test(Checkout::class);
}

it('rejects inactive plans through direct checkout and switch actions', function (string $action) {
    $component = eligibilityCheckout($action);
    $this->plan->update(['active' => false]);

    $component->call($action, $this->plan->id)->assertNoRedirect()
        ->assertNotDispatched('paddle-checkout-ready')
        ->assertNotified('This plan or billing cycle is no longer available.');

    expect($this->stripe->requests)->toBeEmpty()
        ->and($this->subscription->fresh()->plan_id)->toBe($this->plan->id)
        ->and($this->subscription->fresh()->status)->toBe('active');
    Http::assertNothingSent();
})->with(['redirectToStripeCheckout', 'openPaddleCheckout', 'switchPlan']);

it('rejects unsupported client supplied billing cycles before contacting providers', function (string $action, mixed $cycle) {
    eligibilityCheckout($action)->set('billing_cycle_selected', $cycle)
        ->call($action, $this->plan->id)->assertNoRedirect()
        ->assertNotDispatched('paddle-checkout-ready')
        ->assertNotified('This plan or billing cycle is no longer available.');
    expect($this->stripe->requests)->toBeEmpty();
    Http::assertNothingSent();
})->with(['redirectToStripeCheckout', 'openPaddleCheckout', 'switchPlan'])->with([
    'invalid string' => ['weekly'], 'missing' => [null], 'array' => [['year']],
]);

it('rejects unavailable prices for the selected cycle', function (string $action, ?string $price) {
    $this->plan->update(['yearly_price_id' => $price]);
    eligibilityCheckout($action)->set('billing_cycle_selected', 'year')
        ->call($action, $this->plan->id)->assertNoRedirect()
        ->assertNotDispatched('paddle-checkout-ready')
        ->assertNotified('This plan or billing cycle is no longer available.');
    expect($this->stripe->requests)->toBeEmpty();
    Http::assertNothingSent();
})->with(['redirectToStripeCheckout', 'openPaddleCheckout', 'switchPlan'])->with([null, '', '   ']);

it('uses the current database price and cycle for a valid Stripe checkout', function (string $cycle, string $price) {
    $component = eligibilityCheckout('redirectToStripeCheckout');
    $this->plan->update([$cycle === 'month' ? 'monthly_price_id' : 'yearly_price_id' => $price]);
    $this->stripe->respond('post', '/v1/checkout/sessions', ['id' => 'cs_valid', 'object' => 'checkout.session', 'url' => 'https://checkout.stripe.com/cs_valid']);

    $component->set('billing_cycle_selected', $cycle)->call('redirectToStripeCheckout', $this->plan->id)
        ->assertRedirect('https://checkout.stripe.com/cs_valid');

    $requests = $this->stripe->requested('post', '/v1/checkout/sessions');
    expect($requests)->toHaveCount(1)
        ->and($requests[0]['params']['line_items'][0]['price'])->toBe($price)
        ->and($requests[0]['params']['metadata']['billing_cycle'])->toBe($cycle);
})->with(['monthly' => ['month', 'price_new_month'], 'yearly' => ['year', 'price_new_year']]);

it('opens Paddle checkout only after server validation', function () {
    eligibilityCheckout('openPaddleCheckout')->set('billing_cycle_selected', 'year')
        ->call('openPaddleCheckout', $this->plan->id)
        ->assertDispatched('paddle-checkout-ready', priceId: 'price_year');
    Http::assertNothingSent();
});

it('keeps subscribers on their retired plan while permitting a switch to an active plan', function () {
    $this->plan->update(['active' => false]);
    $replacement = Plan::create([
        'name' => 'Replacement', 'active' => true, 'features' => '',
        'monthly_price_id' => 'pri_replacement', 'role_id' => Role::findByName('premium')->id,
    ]);
    config(['wave.billing_provider' => 'paddle']);
    expect($this->user->subscriber())->toBeTrue()
        ->and($this->user->latestSubscription()->plan_id)->toBe($this->plan->id);
    Http::fake(['sandbox-api.paddle.com/subscriptions/sub_current' => Http::response(['data' => []])]);
    Livewire::test(Checkout::class, ['change' => true])->call('switchPlan', $replacement->id)
        ->assertRedirect('/settings/subscription');
    expect($this->subscription->fresh()->plan_id)->toBe($replacement->id);
});

it('rejects plan switches without a usable Paddle subscription', function (string $state) {
    match ($state) {
        'missing' => $this->subscription->delete(),
        'other provider' => $this->subscription->update(['vendor_slug' => 'stripe']),
        'missing id' => $this->subscription->update(['vendor_subscription_id' => null]),
    };
    eligibilityCheckout('switchPlan')->call('switchPlan', $this->plan->id)
        ->assertNoRedirect()->assertNotified('Your subscription could not be found. Please contact support.');
    Http::assertNothingSent();
})->with(['missing', 'other provider', 'missing id']);

it('rejects the other provider checkout actions', function (string $action, string $provider) {
    config(['wave.billing_provider' => $provider]);
    Livewire::test(Checkout::class)->call($action, $this->plan->id)->assertForbidden();
    expect($this->stripe->requests)->toBeEmpty();
    Http::assertNothingSent();
})->with([
    ['redirectToStripeCheckout', 'paddle'], ['openPaddleCheckout', 'stripe'], ['switchPlan', 'stripe'],
]);

it('routes the Paddle subscribe button through server-side eligibility validation', function () {
    eligibilityCheckout('openPaddleCheckout')
        ->assertSee('wire:click="openPaddleCheckout', false)
        ->assertSee('openCheckout($event.detail.priceId)', false)
        ->assertDontSee("openCheckout('price_month')", false);
});
