<?php

use App\Models\User;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    // Use an existing plan from the seeder (premium plan has role_id 4)
    $this->premiumPlan = Plan::where('role_id', 4)->first();
    
    if (!$this->premiumPlan) {
        // Fallback: create a basic plan structure if no plan exists
        $this->premiumPlan = Plan::create([
            'name' => 'Premium Plan',
            'description' => 'Premium subscription plan',
            'features' => 'Feature 1, Feature 2',
            'monthly_price' => '10.00',
            'yearly_price' => '100.00',
            'monthly_price_id' => 'price_monthly_test',
            'yearly_price_id' => 'price_yearly_test',
            'active' => true,
            'role_id' => 4, // premium role
        ]);
    }

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
        'vendor_customer_id' => 'cust_test_' . uniqid(),
        'vendor_subscription_id' => 'sub_test_' . uniqid(),
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

test('subscription cancellation clears all roles and assigns default role', function () {
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
        'vendor_subscription_id' => 'sub_test_' . uniqid(),
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
        'vendor_customer_id' => 'cus_stripe_' . uniqid(),
        'vendor_subscription_id' => 'sub_stripe_' . uniqid(),
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
        'vendor_subscription_id' => 'sub_test_' . uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Clear cache to ensure fresh query
    \Illuminate\Support\Facades\Cache::forget("user_subscriber_{$this->user->id}");

    // Verify user is subscriber
    expect($this->user->fresh()->subscriber())->toBeTrue();

    // Cancel subscription
    $this->user->subscription->cancel();

    // Clear cache again
    \Illuminate\Support\Facades\Cache::forget("user_subscriber_{$this->user->id}");

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
        'vendor_subscription_id' => 'sub_old_' . uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    $subscription2 = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_new_' . uniqid(),
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
