<?php

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;
use Wave\Http\Livewire\Billing\Update;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->premiumPlan = Plan::create([
        'name' => 'Premium Plan',
        'description' => 'Premium subscription plan',
        'features' => 'Feature 1, Feature 2',
        'monthly_price' => '10.00',
        'yearly_price' => '100.00',
        'monthly_price_id' => 'price_monthly_test',
        'yearly_price_id' => 'price_yearly_test',
        'active' => true,
        'role_id' => Role::findByName('premium')->id,
    ]);

    // Ensure user has premium role
    $this->user->syncRoles([]);
    $this->user->assignRole('premium');
});

test('subscription cancel method changes user role to registered', function () {
    // Create an active subscription
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'paddle',
        'vendor_customer_id' => 'cust_test_'.uniqid(),
        'vendor_subscription_id' => 'sub_test_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Verify user has premium role
    expect($this->user->fresh()->hasRole('premium'))->toBeTrue();

    // Cancel the subscription
    $subscription->cancel();

    // Verify subscription is cancelled
    expect($subscription->fresh()->status)->toBe('cancelled');

    // Verify user role changed to registered (default role)
    $freshUser = $this->user->fresh();
    expect($freshUser->hasRole('registered'))->toBeTrue()
        ->and($freshUser->hasRole('premium'))->toBeFalse();
});

test('subscription cancellation removes billing roles and assigns default role', function () {
    Plan::create([
        'name' => 'Basic billing plan', 'features' => '', 'active' => false,
        'role_id' => Role::findByName('basic')->id,
    ]);
    // Give user multiple roles
    $this->user->assignRole('basic');
    $this->user->assignRole('premium');

    expect($this->user->fresh()->roles)->toHaveCount(2); // basic, premium

    // Create subscription
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_test_'.uniqid(),
        'cycle' => 'year',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Cancel subscription
    $subscription->cancel();

    // Verify only registered role remains
    $freshUser = $this->user->fresh();
    expect($freshUser->roles)->toHaveCount(1)
        ->and($freshUser->hasRole('registered'))->toBeTrue()
        ->and($freshUser->hasRole('basic'))->toBeFalse()
        ->and($freshUser->hasRole('premium'))->toBeFalse();
});

test('user subscription helper returns null after cancellation', function () {
    // Create subscription
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_stripe_'.uniqid(),
        'vendor_subscription_id' => 'sub_stripe_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Verify user has active subscription
    expect($this->user->fresh()->subscription)->not->toBeNull();

    // Cancel subscription
    $subscription->cancel();

    // Verify subscription returns null (no active subscriptions)
    expect($this->user->fresh()->subscription)->toBeNull();
});

test('subscriber helper returns false after cancellation', function () {
    // Create subscription
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_test_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Clear cache to ensure fresh query
    Cache::forget("user_subscriber_{$this->user->id}");

    // Verify user is subscriber
    expect($this->user->fresh()->subscriber())->toBeTrue();

    // Cancel subscription; cancel() must clear the cached status itself
    $this->user->subscription->cancel();

    // Verify user is no longer subscriber
    expect($this->user->fresh()->subscriber())->toBeFalse();
});

test('multiple subscriptions only cancel the specific one', function () {
    // Create two subscriptions (one active, one future)
    $subscription1 = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_old_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    $subscription2 = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_new_'.uniqid(),
        'cycle' => 'year',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Cancel first subscription
    $subscription1->cancel();

    // Verify first is cancelled, second is still active
    expect($subscription1->fresh()->status)->toBe('cancelled')
        ->and($subscription2->fresh()->status)->toBe('active');

    // User still has active subscription
    expect($this->user->fresh()->subscriber())->toBeTrue();
});

test('subscription settings page loads right after cancelling', function () {
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_subscription_id' => 'sub_stripe_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    $this->get('/settings/subscription')->assertOk()->assertSee('Premium Plan Monthly');

    $subscription->cancel();

    $this->get('/settings/subscription')->assertOk()->assertSee('No active subscriptions found');
});

test('cancelling a paddle subscription immediately cancels it with paddle and locally', function () {
    config(['wave.billing_provider' => 'paddle', 'wave.paddle.env' => 'sandbox']);

    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_paddle_123',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    Http::fake([
        'sandbox-api.paddle.com/subscriptions/sub_paddle_123/cancel' => Http::response(['data' => ['status' => 'canceled']]),
        'sandbox-api.paddle.com/subscriptions/sub_paddle_123' => Http::response(['data' => [
            'current_billing_period' => ['ends_at' => '2026-12-31T00:00:00Z'],
            'management_urls' => ['cancel' => 'https://example.com/cancel', 'update_payment_method' => 'https://example.com/update'],
        ]]),
    ]);

    Livewire::test(Update::class)
        ->call('cancelImmediately')
        ->assertRedirect('/settings/subscription');

    Http::assertSent(fn ($request) => $request->method() === 'POST'
        && $request->url() === 'https://sandbox-api.paddle.com/subscriptions/sub_paddle_123/cancel'
        && $request['effective_from'] === 'immediately');
    expect($subscription->fresh()->status)->toBe('cancelled');
    expect($this->user->fresh()->hasRole('registered'))->toBeTrue();
});

test('cancelling immediately after the subscription already ended redirects back', function () {
    config(['wave.billing_provider' => 'paddle', 'wave.paddle.env' => 'sandbox']);

    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_paddle_123',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    Http::fake([
        'sandbox-api.paddle.com/*' => Http::response(['data' => [
            'current_billing_period' => ['ends_at' => '2026-12-31T00:00:00Z'],
            'management_urls' => ['cancel' => 'https://example.com/cancel', 'update_payment_method' => 'https://example.com/update'],
        ]]),
    ]);

    $component = Livewire::test(Update::class);

    // Ended by a webhook while the page was open.
    $subscription->update(['status' => 'cancelled']);
    $this->user->unsetRelation('subscription');

    $component->call('cancelImmediately')->assertRedirect('/settings/subscription');

    Http::assertNotSent(fn ($request) => str_ends_with($request->url(), '/cancel'));
});

test('cancellation derives access from the newest remaining active subscription', function (bool $cancelNewest) {
    $basicPlan = Plan::create([
        'name' => 'Remaining Basic', 'features' => '', 'active' => false,
        'role_id' => Role::findByName('basic')->id,
    ]);
    $old = Subscription::create([
        'billable_type' => 'user', 'billable_id' => $this->user->id,
        'plan_id' => $basicPlan->id, 'vendor_slug' => 'stripe',
        'vendor_subscription_id' => 'sub_old', 'cycle' => 'month', 'status' => 'active',
    ]);
    $new = Subscription::create([
        'billable_type' => 'user', 'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id, 'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_new', 'cycle' => 'month', 'status' => 'active',
    ]);
    $this->user->assignRole('admin');
    expect($this->user->subscriber())->toBeTrue();
    // Both rows share a timestamp: creation order must still select the newer row.
    expect($this->user->latestSubscription()->id)->toBe($new->id)
        ->and($this->user->subscription()->first()->id)->toBe($new->id);

    $cancelled = $cancelNewest ? $new : $old;
    $remaining = $cancelNewest ? $old : $new;
    $expectedRole = $cancelNewest ? 'basic' : 'premium';
    $cancelled->cancel();
    $cancelled->cancel(); // A repeated local cancellation must preserve the same access.

    $user = $this->user->fresh();
    expect($cancelled->fresh()->status)->toBe('cancelled')
        ->and($remaining->fresh()->status)->toBe('active')
        ->and($user->subscriber())->toBeTrue()
        ->and($user->latestSubscription()->id)->toBe($remaining->id)
        ->and($user->getRoleNames()->sort()->values()->all())->toBe(['admin', $expectedRole]);
})->with([false, true]);

test('ending the final subscription preserves non-billing roles', function () {
    $this->user->assignRole('admin');
    $subscription = Subscription::create([
        'billable_type' => 'user', 'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id, 'vendor_slug' => 'stripe',
        'vendor_subscription_id' => 'sub_final', 'cycle' => 'month', 'status' => 'active',
    ]);
    $subscription->cancel();
    expect($this->user->fresh()->getRoleNames()->sort()->values()->all())->toBe(['admin', 'registered'])
        ->and($this->user->fresh()->subscriber())->toBeFalse();
});

test('cancellation rolls back when access reconciliation fails', function () {
    $subscription = Subscription::create([
        'billable_type' => 'user', 'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id, 'vendor_slug' => 'stripe',
        'vendor_subscription_id' => 'sub_rollback', 'cycle' => 'month', 'status' => 'active',
    ]);
    config(['wave.default_user_role' => 'missing-default-role']);

    expect(fn () => $subscription->cancel())->toThrow(RoleDoesNotExist::class);
    expect($subscription->fresh()->status)->toBe('active')
        ->and($this->user->fresh()->getRoleNames()->all())->toBe(['premium']);
});
