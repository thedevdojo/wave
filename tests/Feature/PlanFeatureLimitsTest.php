<?php

/**
 * Plan Feature Limits Test Suite
 *
 * Tests the plan-based feature limits functionality including:
 * - Feature limit retrieval from plans
 * - Usage counting for features
 * - canUseFeature checks
 * - Admin bypass functionality
 * - Default limits for users without plans
 */

use App\Models\User;
use Spatie\Permission\Models\Role;
use Wave\ApiKey;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed();

    // Create test user
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    // Create test roles
    $this->freeRole = Role::firstOrCreate(
        ['name' => 'free'],
        ['guard_name' => 'web']
    );

    $this->proRole = Role::firstOrCreate(
        ['name' => 'pro'],
        ['guard_name' => 'web']
    );

    $this->adminRole = Role::firstOrCreate(
        ['name' => 'admin'],
        ['guard_name' => 'web']
    );

    // Create test plans with limits
    $this->freePlan = Plan::create([
        'name' => 'Free',
        'description' => 'Free plan for testing',
        'features' => ['Basic features'],
        'monthly_price' => '0.00',
        'active' => true,
        'role_id' => $this->freeRole->id,
        'limits' => [
            'api_keys' => 1,
        ],
    ]);

    $this->proPlan = Plan::create([
        'name' => 'Pro',
        'description' => 'Pro plan for testing',
        'features' => ['All features'],
        'monthly_price' => '10.00',
        'active' => true,
        'role_id' => $this->proRole->id,
        'limits' => [
            'api_keys' => 10,
        ],
    ]);

    $this->unlimitedPlan = Plan::create([
        'name' => 'Enterprise',
        'description' => 'Enterprise plan with no limits',
        'features' => ['Unlimited everything'],
        'monthly_price' => '100.00',
        'active' => true,
        'role_id' => $this->proRole->id,
        'limits' => [
            'api_keys' => -1,
        ],
    ]);
});

test('user can get feature limit from plan', function () {
    // Subscribe user to free plan
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->freePlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->featureLimit('api_keys'))->toBe(1);
});

test('user can get higher limit from pro plan', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->proPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->featureLimit('api_keys'))->toBe(10);
});

test('unlimited limit returns null', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->unlimitedPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->featureLimit('api_keys'))->toBeNull();
});

test('user without plan gets default limit', function () {
    config(['limits.defaults.api_keys' => 1]);

    expect($this->user->featureLimit('api_keys'))->toBe(1);
});

test('user without plan gets unlimited for undefined feature', function () {
    config(['limits.defaults' => []]);

    expect($this->user->featureLimit('undefined_feature'))->toBeNull();
});

test('admin bypasses all limits', function () {
    $this->user->assignRole('admin');
    config(['limits.admin_bypass' => true]);

    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->freePlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->featureLimit('api_keys'))->toBeNull();
});

test('admin bypass can be disabled', function () {
    $this->user->assignRole('admin');
    config(['limits.admin_bypass' => false]);

    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->freePlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->featureLimit('api_keys'))->toBe(1);
});

test('feature usage counts correctly', function () {
    // Create some API keys for the user
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 1', 'key' => 'test_key_1']);
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 2', 'key' => 'test_key_2']);

    expect($this->user->featureUsage('api_keys'))->toBe(2);
});

test('feature usage is cached within request', function () {
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 1', 'key' => 'test_key_1']);

    // First call
    $usage1 = $this->user->featureUsage('api_keys');

    // Add another key (but cache should return old value)
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 2', 'key' => 'test_key_2']);
    $usage2 = $this->user->featureUsage('api_keys');

    expect($usage1)->toBe(1)
        ->and($usage2)->toBe(1); // Still 1 due to cache

    // Clear cache and check again
    $this->user->clearFeatureUsageCache();
    $usage3 = $this->user->featureUsage('api_keys');

    expect($usage3)->toBe(2);
});

test('canUseFeature returns true when under limit', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->proPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Pro plan has limit of 10
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 1', 'key' => 'test_key_1']);

    expect($this->user->canUseFeature('api_keys'))->toBeTrue();
});

test('canUseFeature returns false when at limit', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->freePlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Free plan has limit of 1
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 1', 'key' => 'test_key_1']);

    expect($this->user->canUseFeature('api_keys'))->toBeFalse();
});

test('canUseFeature with amount parameter', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->proPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Pro plan has limit of 10
    for ($i = 1; $i <= 8; $i++) {
        ApiKey::create(['user_id' => $this->user->id, 'name' => "Key {$i}", 'key' => "test_key_{$i}"]);
    }
    $this->user->clearFeatureUsageCache();

    expect($this->user->canUseFeature('api_keys', 2))->toBeTrue()
        ->and($this->user->canUseFeature('api_keys', 3))->toBeFalse();
});

test('featureRemaining returns correct value', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->proPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 1', 'key' => 'test_key_1']);
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 2', 'key' => 'test_key_2']);

    expect($this->user->featureRemaining('api_keys'))->toBe(8);
});

test('featureRemaining returns null for unlimited', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->unlimitedPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->featureRemaining('api_keys'))->toBeNull();
});

test('featureLimitReached returns correct boolean', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->freePlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->featureLimitReached('api_keys'))->toBeFalse();

    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 1', 'key' => 'test_key_1']);
    $this->user->clearFeatureUsageCache();

    expect($this->user->featureLimitReached('api_keys'))->toBeTrue();
});

test('allFeatureLimits returns plan limits array', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->proPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    $limits = $this->user->allFeatureLimits();

    expect($limits)->toBe(['api_keys' => 10]);
});

test('allFeatureLimits returns defaults for user without plan', function () {
    config(['limits.defaults' => ['api_keys' => 1]]);

    $limits = $this->user->allFeatureLimits();

    expect($limits)->toBe(['api_keys' => 1]);
});

test('plan model getLimit helper works', function () {
    expect($this->freePlan->getLimit('api_keys'))->toBe(1)
        ->and($this->proPlan->getLimit('api_keys'))->toBe(10)
        ->and($this->unlimitedPlan->getLimit('api_keys'))->toBeNull();
});

test('plan model hasLimit helper works', function () {
    expect($this->freePlan->hasLimit('api_keys'))->toBeTrue()
        ->and($this->freePlan->hasLimit('undefined'))->toBeFalse();
});

test('zero limit disables feature', function () {
    $disabledPlan = Plan::create([
        'name' => 'Disabled',
        'description' => 'Plan with disabled feature',
        'features' => ['Limited features'],
        'monthly_price' => '0.00',
        'active' => true,
        'role_id' => $this->freeRole->id,
        'limits' => [
            'api_keys' => 0,
        ],
    ]);

    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $disabledPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->featureLimit('api_keys'))->toBe(0)
        ->and($this->user->canUseFeature('api_keys'))->toBeFalse();
});

test('undefined feature in plan returns unlimited', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->freePlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->featureLimit('undefined_feature'))->toBeNull()
        ->and($this->user->canUseFeature('undefined_feature'))->toBeTrue();
});

test('featureUsagePercent returns correct percentage', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->proPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Pro plan has limit of 10
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 1', 'key' => 'test_key_1']);
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 2', 'key' => 'test_key_2']);
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 3', 'key' => 'test_key_3']);

    expect($this->user->featureUsagePercent('api_keys'))->toBe(30.0);
});

test('featureUsagePercent returns null for unlimited', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->unlimitedPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    expect($this->user->featureUsagePercent('api_keys'))->toBeNull();
});

test('featureUsagePercent caps at 100', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->freePlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Free plan has limit of 1, create 2
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 1', 'key' => 'test_key_1']);
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 2', 'key' => 'test_key_2']);

    expect($this->user->featureUsagePercent('api_keys'))->toBe(100.0);
});

test('featureNearLimit returns true when approaching limit', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->proPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Pro plan has limit of 10, create 8 (80%)
    for ($i = 1; $i <= 8; $i++) {
        ApiKey::create(['user_id' => $this->user->id, 'name' => "Key {$i}", 'key' => "test_key_{$i}"]);
    }

    expect($this->user->featureNearLimit('api_keys'))->toBeTrue()
        ->and($this->user->featureNearLimit('api_keys', 0.9))->toBeFalse();
});

test('featureNearLimit returns false when well under limit', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->proPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Pro plan has limit of 10, create 2 (20%)
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 1', 'key' => 'test_key_1']);
    ApiKey::create(['user_id' => $this->user->id, 'name' => 'Key 2', 'key' => 'test_key_2']);

    expect($this->user->featureNearLimit('api_keys'))->toBeFalse();
});

test('featureNearLimit returns false for unlimited', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->unlimitedPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    for ($i = 1; $i <= 100; $i++) {
        ApiKey::create(['user_id' => $this->user->id, 'name' => "Key {$i}", 'key' => "test_key_{$i}"]);
    }
    $this->user->clearFeatureUsageCache();

    expect($this->user->featureNearLimit('api_keys'))->toBeFalse();
});

test('featureNearLimit with custom threshold', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->proPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Pro plan has limit of 10, create 5 (50%)
    for ($i = 1; $i <= 5; $i++) {
        ApiKey::create(['user_id' => $this->user->id, 'name' => "Key {$i}", 'key' => "test_key_{$i}"]);
    }

    expect($this->user->featureNearLimit('api_keys', 0.5))->toBeTrue()
        ->and($this->user->featureNearLimit('api_keys', 0.6))->toBeFalse();
});
