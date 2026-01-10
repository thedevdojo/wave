<?php

/**
 * Plan Switching Test Suite
 *
 * Tests the critical plan switching functionality including:
 * - Role changes during upgrades/downgrades
 * - Subscription plan updates
 * - Billing cycle changes
 * - Edge cases (same plan, invalid plans, multiple subscriptions)
 */

use App\Models\User;
use Spatie\Permission\Models\Role;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed();

    // Create test user
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    // Create test roles
    $this->basicRole = Role::firstOrCreate(
        ['name' => 'basic'],
        ['guard_name' => 'web']
    );

    $this->premiumRole = Role::firstOrCreate(
        ['name' => 'premium'],
        ['guard_name' => 'web']
    );

    $this->proRole = Role::firstOrCreate(
        ['name' => 'pro'],
        ['guard_name' => 'web']
    );

    // Create test plans
    $this->basicPlan = Plan::create([
        'name' => 'Basic',
        'description' => 'Basic plan for testing',
        'features' => 'Feature 1, Feature 2',
        'monthly_price' => '5.00',
        'yearly_price' => '50.00',
        'monthly_price_id' => 'price_basic_monthly',
        'yearly_price_id' => 'price_basic_yearly',
        'active' => true,
        'role_id' => $this->basicRole->id,
    ]);

    $this->premiumPlan = Plan::create([
        'name' => 'Premium',
        'description' => 'Premium plan for testing',
        'features' => 'Feature 1, Feature 2, Feature 3',
        'monthly_price' => '10.00',
        'yearly_price' => '100.00',
        'monthly_price_id' => 'price_premium_monthly',
        'yearly_price_id' => 'price_premium_yearly',
        'active' => true,
        'role_id' => $this->premiumRole->id,
    ]);

    $this->proPlan = Plan::create([
        'name' => 'Pro',
        'description' => 'Pro plan for testing',
        'features' => 'Feature 1, Feature 2, Feature 3, Feature 4',
        'monthly_price' => '20.00',
        'yearly_price' => '200.00',
        'monthly_price_id' => 'price_pro_monthly',
        'yearly_price_id' => 'price_pro_yearly',
        'active' => true,
        'role_id' => $this->proRole->id,
    ]);

    // Assign initial role
    $this->user->assignRole('basic');
});

test('user can upgrade from basic to premium plan', function () {
    // Create basic subscription
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->basicPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Verify initial state
    expect($this->user->hasRole('basic'))->toBeTrue()
        ->and($this->user->hasRole('premium'))->toBeFalse()
        ->and($subscription->plan_id)->toBe($this->basicPlan->id);

    // Switch to premium plan
    $this->user->switchPlans($this->premiumPlan);

    // Verify role changed
    $freshUser = $this->user->fresh();
    expect($freshUser->hasRole('premium'))->toBeTrue()
        ->and($freshUser->hasRole('basic'))->toBeFalse()
        ->and($freshUser->roles)->toHaveCount(1);
});

test('user can downgrade from pro to basic plan', function () {
    // Assign pro role
    $this->user->syncRoles([]);
    $this->user->assignRole('pro');

    // Create pro subscription
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->proPlan->id,
        'vendor_slug' => 'paddle',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'year',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->fresh()->hasRole('pro'))->toBeTrue();

    // Downgrade to basic plan
    $this->user->switchPlans($this->basicPlan);

    // Verify downgrade
    $freshUser = $this->user->fresh();
    expect($freshUser->hasRole('basic'))->toBeTrue()
        ->and($freshUser->hasRole('pro'))->toBeFalse();
});

test('switching plans removes all previous roles', function () {
    // Give user multiple roles (shouldn't happen, but test it)
    $this->user->syncRoles([]);
    $this->user->assignRole('basic');
    $this->user->assignRole('premium');

    expect($this->user->fresh()->roles)->toHaveCount(2);

    // Switch to pro plan
    $this->user->switchPlans($this->proPlan);

    // Verify only pro role remains
    $freshUser = $this->user->fresh();
    expect($freshUser->roles)->toHaveCount(1)
        ->and($freshUser->hasRole('pro'))->toBeTrue()
        ->and($freshUser->hasRole('basic'))->toBeFalse()
        ->and($freshUser->hasRole('premium'))->toBeFalse();
});

test('plan method returns correct current plan', function () {
    // Create subscription with premium plan
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    $currentPlan = $this->user->plan();

    expect($currentPlan)->not->toBeNull()
        ->and($currentPlan->id)->toBe($this->premiumPlan->id)
        ->and($currentPlan->name)->toBe('Premium');
});

test('planInterval returns correct billing cycle', function () {
    // Test monthly
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->planInterval())->toBe('Monthly');

    // Create new subscription with yearly cycle
    $this->user->subscription->update(['status' => 'cancelled']);
    
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->proPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'year',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->fresh()->planInterval())->toBe('Yearly');
});

test('latestSubscription returns most recent active subscription', function () {
    // Create older subscription
    $oldSubscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->basicPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
        'created_at' => now()->subDays(30),
    ]);

    sleep(1); // Ensure different timestamps

    // Create newer subscription
    $newSubscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'year',
        'status' => 'active',
        'seats' => 1,
    ]);

    $latest = $this->user->latestSubscription();

    expect($latest)->not->toBeNull()
        ->and($latest->id)->toBe($newSubscription->id)
        ->and($latest->plan_id)->toBe($this->premiumPlan->id);
});

test('subscription relationship returns active subscription', function () {
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'paddle',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    $userSubscription = $this->user->subscription;

    expect($userSubscription)->not->toBeNull()
        ->and($userSubscription->id)->toBe($subscription->id)
        ->and($userSubscription->status)->toBe('active');
});

test('switching to same plan updates role correctly', function () {
    $this->user->syncRoles([]);
    $this->user->assignRole('premium');

    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Switch to same plan (e.g., changing billing cycle)
    $this->user->switchPlans($this->premiumPlan);

    $freshUser = $this->user->fresh();
    expect($freshUser->hasRole('premium'))->toBeTrue()
        ->and($freshUser->roles)->toHaveCount(1);
});

test('plan relationship on subscription works correctly', function () {
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    $plan = $subscription->plan;

    expect($plan)->not->toBeNull()
        ->and($plan->id)->toBe($this->premiumPlan->id)
        ->and($plan->name)->toBe('Premium');
});

test('user relationship on subscription works correctly', function () {
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    $user = $subscription->user;

    expect($user)->not->toBeNull()
        ->and($user->id)->toBe($this->user->id)
        ->and($user->email)->toBe($this->user->email);
});

test('cancelled subscriptions are not returned by subscription relationship', function () {
    // Create cancelled subscription
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->basicPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'month',
        'status' => 'cancelled',
        'seats' => 1,
    ]);

    expect($this->user->subscription)->toBeNull();
});

test('switching plans with different billing cycles maintains role integrity', function () {
    // Monthly subscription
    $monthlySubscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->basicPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_' . uniqid(),
        'vendor_subscription_id' => 'sub_' . uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->planInterval())->toBe('Monthly');

    // Switch to yearly premium
    $this->user->switchPlans($this->premiumPlan);
    $monthlySubscription->update([
        'plan_id' => $this->premiumPlan->id,
        'cycle' => 'year',
    ]);

    $freshUser = $this->user->fresh();
    expect($freshUser->hasRole('premium'))->toBeTrue()
        ->and($freshUser->planInterval())->toBe('Yearly');
});
