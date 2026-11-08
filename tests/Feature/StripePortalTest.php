<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Stripe\ApiRequestor;
use Tests\Support\FakeStripeClient;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    config(['wave.stripe.secret_key' => 'sk_test_fake']);

    $this->stripe = new FakeStripeClient;
    ApiRequestor::setHttpClient($this->stripe);

    $this->user = User::factory()->create();
});

afterEach(function () {
    ApiRequestor::setHttpClient(null);
});

it('sends guests to the login page', function () {
    $this->get('/stripe/portal')->assertRedirect(route('login'));
});

it('sends users without a subscription back to the subscription page', function () {
    $this->actingAs($this->user)->get('/stripe/portal')->assertRedirect('/settings/subscription');

    expect($this->stripe->requests)->toBeEmpty();
});

it('opens a portal session for the subscribed customer', function () {
    $plan = Plan::create([
        'name' => 'Premium',
        'features' => '',
        'monthly_price_id' => 'price_premium_month',
        'active' => true,
        'role_id' => Role::findByName('premium')->id,
    ]);

    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $plan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_1',
        'vendor_subscription_id' => 'sub_1',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    $this->stripe->respond('post', '/v1/billing_portal/sessions', [
        'id' => 'bps_1',
        'object' => 'billing_portal.session',
        'url' => 'https://billing.stripe.com/session/test',
    ]);

    $this->actingAs($this->user)->get('/stripe/portal')
        ->assertRedirect('https://billing.stripe.com/session/test');

    $sessions = $this->stripe->requested('post', '/v1/billing_portal/sessions');
    expect($sessions)->toHaveCount(1)
        ->and($sessions[0]['params']['customer'])->toBe('cus_1')
        ->and($this->stripe->requested('post', '/v1/billing_portal/configurations'))->toBeEmpty();
});
