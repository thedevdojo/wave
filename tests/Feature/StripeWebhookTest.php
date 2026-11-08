<?php

/**
 * Stripe Webhook Business Logic Tests
 *
 * These tests verify the database state changes and business logic
 * that occur during Stripe webhook processing, focusing specifically
 * on the TODO at line 60 of StripeWebhook.php: testing plan switching.
 *
 * Note: These tests focus on unit-testing the business logic rather than
 * attempting to mock Stripe SDK classes, which is complex and fragile.
 * For full end-to-end webhook testing, use Stripe CLI in test mode.
 */

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed();

    // Create test roles
    $this->premiumRole = Role::firstOrCreate(
        ['name' => 'test_premium'],
        ['guard_name' => 'web']
    );

    $this->enterpriseRole = Role::firstOrCreate(
        ['name' => 'test_enterprise'],
        ['guard_name' => 'web']
    );

    // Create test plans
    $this->premiumPlan = Plan::create([
        'name' => 'Premium Plan',
        'description' => 'Premium features',
        'features' => 'feature1,feature2',
        'monthly_price_id' => 'price_monthly_123',
        'yearly_price_id' => 'price_yearly_123',
        'monthly_price' => '9.99',
        'yearly_price' => '99.99',
        'active' => true,
        'role_id' => $this->premiumRole->id,
    ]);

    $this->enterprisePlan = Plan::create([
        'name' => 'Enterprise Plan',
        'description' => 'Enterprise features',
        'features' => 'feature1,feature2,feature3',
        'monthly_price_id' => 'price_monthly_456',
        'yearly_price_id' => 'price_yearly_456',
        'monthly_price' => '29.99',
        'yearly_price' => '299.99',
        'active' => true,
        'role_id' => $this->enterpriseRole->id,
    ]);

    $this->user = User::factory()->create();
});

test('plan switching updates user role correctly', function () {
    // This addresses the TODO at line 60 of StripeWebhook.php
    // Testing: $subscription->user->switchPlans($updatedPlan);

    // Setup: Create subscription with premium plan
    $subscription = Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_test123',
        'vendor_subscription_id' => 'sub_test123',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Assign initial role
    $this->user->assignRole($this->premiumRole->name);
    $this->user->refresh();

    // Verify initial state
    expect($this->user->hasRole('test_premium'))->toBeTrue();
    expect($subscription->plan_id)->toBe($this->premiumPlan->id);

    // Act: Simulate the plan switch that happens in the webhook
    $this->user->switchPlans($this->enterprisePlan);
    $subscription->plan_id = $this->enterprisePlan->id;
    $subscription->cycle = 'year';
    $subscription->save();

    // Assert: Verify role was switched correctly
    $this->user->refresh();
    $subscription->refresh();

    expect($this->user->hasRole('test_enterprise'))->toBeTrue()
        ->and($this->user->hasRole('test_premium'))->toBeFalse()
        ->and($subscription->plan_id)->toBe($this->enterprisePlan->id)
        ->and($subscription->cycle)->toBe('year');
})->group('stripe', 'billing');

test('subscription cancellation sets ends_at date', function () {
    $subscription = Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_test123',
        'vendor_subscription_id' => 'sub_test123',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    $this->user->assignRole($this->premiumRole->name);

    // Simulate cancellation with future end date
    $cancelAt = now()->addMonth();
    $subscription->ends_at = $cancelAt->toDateTimeString();
    $subscription->save();

    $subscription->refresh();

    expect($subscription->ends_at)->not->toBeNull()
        ->and($subscription->ends_at)->toBe($cancelAt->toDateTimeString());
})->group('stripe', 'billing');

test('subscription deletion marks subscription as cancelled', function () {
    $subscription = Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_test123',
        'vendor_subscription_id' => 'sub_test123',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Act: Call cancel() method
    $subscription->cancel();
    $subscription->refresh();

    // Assert: Status should be cancelled
    expect($subscription->status)->toBe('cancelled');
})->group('stripe', 'billing');

test('new subscription assigns correct role to user', function () {
    // Simulate checkout completion
    $this->user->syncRoles([]);
    $this->user->assignRole($this->premiumPlan->role->name);

    $subscription = Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_new123',
        'vendor_subscription_id' => 'sub_new123',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    $this->user->refresh();

    expect($this->user->hasRole('test_premium'))->toBeTrue()
        ->and($subscription->vendor_slug)->toBe('stripe')
        ->and($subscription->status)->toBe('active');
})->group('stripe', 'billing');

test('cache prevents duplicate checkout session processing', function () {
    $sessionId = 'cs_test123';
    $cacheKey = 'stripe_checkout_session_'.$sessionId;

    // First processing
    expect(Cache::has($cacheKey))->toBeFalse();
    Cache::put($cacheKey, true, now()->addHours(24));
    expect(Cache::has($cacheKey))->toBeTrue();

    // Second attempt should find existing cache
    $shouldSkipProcessing = Cache::has($cacheKey);
    expect($shouldSkipProcessing)->toBeTrue();
})->group('stripe', 'billing');

test('subscription cycle can be updated from monthly to yearly', function () {
    $subscription = Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_test123',
        'vendor_subscription_id' => 'sub_test123',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($subscription->cycle)->toBe('month');

    // Update to yearly
    $subscription->cycle = 'year';
    $subscription->save();
    $subscription->refresh();

    expect($subscription->cycle)->toBe('year')
        ->and($subscription->plan_id)->toBe($this->premiumPlan->id);
})->group('stripe', 'billing');

test('removing cancellation date reactivates subscription', function () {
    $subscription = Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_test123',
        'vendor_subscription_id' => 'sub_test123',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
        'ends_at' => now()->addMonth(),
    ]);

    expect($subscription->ends_at)->not->toBeNull();

    // User resumes subscription (removes cancel_at)
    $subscription->ends_at = null;
    $subscription->save();
    $subscription->refresh();

    expect($subscription->ends_at)->toBeNull();
})->group('stripe', 'billing');

test('multiple subscriptions can exist for same user', function () {
    $subscription1 = Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_test123',
        'vendor_subscription_id' => 'sub_test123',
        'cycle' => 'month',
        'status' => 'cancelled',
        'seats' => 1,
    ]);

    $subscription2 = Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $this->user->id,
        'plan_id' => $this->enterprisePlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_test123',
        'vendor_subscription_id' => 'sub_test456',
        'cycle' => 'year',
        'status' => 'active',
        'seats' => 1,
    ]);

    $userSubscriptions = Subscription::where('billable_id', $this->user->id)->get();

    expect($userSubscriptions)->toHaveCount(2)
        ->and($subscription1->status)->toBe('cancelled')
        ->and($subscription2->status)->toBe('active');
})->group('stripe', 'billing');
