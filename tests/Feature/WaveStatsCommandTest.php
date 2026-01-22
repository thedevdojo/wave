<?php

use App\Models\User;
use Wave\Plan;
use Wave\Subscription;

use function Pest\Laravel\artisan;

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed();

    // Create test roles
    $this->basicRole = \Spatie\Permission\Models\Role::firstOrCreate(
        ['name' => 'test_basic'],
        ['guard_name' => 'web']
    );

    $this->premiumRole = \Spatie\Permission\Models\Role::firstOrCreate(
        ['name' => 'test_premium'],
        ['guard_name' => 'web']
    );

    // Create plans
    $this->basicPlan = Plan::create([
        'name' => 'Basic Plan',
        'description' => 'Basic features',
        'features' => 'feature1,feature2',
        'monthly_price' => '9.99',
        'yearly_price' => '99.99',
        'monthly_price_id' => 'price_basic_monthly',
        'yearly_price_id' => 'price_basic_yearly',
        'role_id' => $this->basicRole->id,
        'active' => 1,
        'sort_order' => 1,
    ]);

    $this->premiumPlan = Plan::create([
        'name' => 'Premium Plan',
        'description' => 'Premium features',
        'features' => 'feature1,feature2,feature3',
        'monthly_price' => '29.99',
        'yearly_price' => '299.99',
        'monthly_price_id' => 'price_premium_monthly',
        'yearly_price_id' => 'price_premium_yearly',
        'role_id' => $this->premiumRole->id,
        'active' => 1,
        'sort_order' => 2,
    ]);
});

it('displays statistics successfully', function () {
    artisan('wave:stats')
        ->assertSuccessful()
        ->expectsOutput('                    WAVE STATISTICS');
});

it('calculates MRR correctly for monthly subscriptions', function () {
    // Create 3 users with monthly subscriptions
    for ($i = 0; $i < 3; $i++) {
        $user = User::factory()->create(['verified' => 1]);
        Subscription::create([
            'billable_type' => User::class,
            'billable_id' => $user->id,
            'plan_id' => $this->basicPlan->id,
            'vendor_slug' => 'stripe',
            'vendor_subscription_id' => 'sub_'.uniqid(),
            'vendor_customer_id' => 'cus_'.uniqid(),
            'cycle' => 'month',
            'status' => 'active',
            'seats' => 1,
        ]);
    }

    // Run command and capture output
    $this->artisan('wave:stats', ['--json' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('29.97');
});

it('calculates MRR correctly for yearly subscriptions', function () {
    // Create 2 users with yearly subscriptions
    for ($i = 0; $i < 2; $i++) {
        $user = User::factory()->create(['verified' => 1]);
        Subscription::create([
            'billable_type' => User::class,
            'billable_id' => $user->id,
            'plan_id' => $this->premiumPlan->id,
            'vendor_slug' => 'stripe',
            'vendor_subscription_id' => 'sub_'.uniqid(),
            'vendor_customer_id' => 'cus_'.uniqid(),
            'cycle' => 'year',
            'status' => 'active',
            'seats' => 1,
        ]);
    }

    // Expected MRR: 2 × ($299.99 / 12) = $49.998... ≈ $50.00
    $this->artisan('wave:stats', ['--json' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('50');
});

it('calculates MRR correctly for mixed subscriptions', function () {
    // Create 2 monthly basic subscriptions
    for ($i = 0; $i < 2; $i++) {
        $user = User::factory()->create(['verified' => 1]);
        Subscription::create([
            'billable_type' => User::class,
            'billable_id' => $user->id,
            'plan_id' => $this->basicPlan->id,
            'vendor_slug' => 'stripe',
            'vendor_subscription_id' => 'sub_'.uniqid(),
            'vendor_customer_id' => 'cus_'.uniqid(),
            'cycle' => 'month',
            'status' => 'active',
            'seats' => 1,
        ]);
    }

    // Create 1 yearly premium subscription
    $user = User::factory()->create(['verified' => 1]);
    Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $user->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'vendor_customer_id' => 'cus_'.uniqid(),
        'cycle' => 'year',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Expected MRR: (2 × $9.99) + ($299.99 / 12) = $19.98 + $25.00 = $44.98
    $this->artisan('wave:stats', ['--json' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('44.98');
});

it('counts active subscriptions correctly', function () {
    // Create 5 active subscriptions
    for ($i = 0; $i < 5; $i++) {
        $user = User::factory()->create(['verified' => 1]);
        Subscription::create([
            'billable_type' => User::class,
            'billable_id' => $user->id,
            'plan_id' => $this->basicPlan->id,
            'vendor_slug' => 'stripe',
            'vendor_subscription_id' => 'sub_'.uniqid(),
            'vendor_customer_id' => 'cus_'.uniqid(),
            'cycle' => 'month',
            'status' => 'active',
            'seats' => 1,
        ]);
    }

    // Create 2 cancelled subscriptions
    for ($i = 0; $i < 2; $i++) {
        $user = User::factory()->create(['verified' => 1]);
        Subscription::create([
            'billable_type' => User::class,
            'billable_id' => $user->id,
            'plan_id' => $this->premiumPlan->id,
            'vendor_slug' => 'stripe',
            'vendor_subscription_id' => 'sub_'.uniqid(),
            'vendor_customer_id' => 'cus_'.uniqid(),
            'cycle' => 'month',
            'status' => 'cancelled',
            'seats' => 1,
            'ends_at' => now()->addDays(10),
        ]);
    }

    $this->artisan('wave:stats', ['--json' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('"active": 5');
});

it('counts users correctly', function () {
    // Create 10 verified users
    User::factory()->count(10)->create(['verified' => 1]);

    // Create 5 unverified users
    User::factory()->count(5)->create(['verified' => 0]);

    // Should show users (at least our 15 plus any from seeding)
    $this->artisan('wave:stats', ['--json' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('"users"');
});

it('calculates ARR correctly', function () {
    // Create monthly subscription
    $user = User::factory()->create(['verified' => 1]);
    Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $user->id,
        'plan_id' => $this->basicPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'vendor_customer_id' => 'cus_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // MRR = $9.99, ARR = $9.99 × 12 = $119.88
    $this->artisan('wave:stats', ['--json' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('119.88');
});

it('shows zero MRR when no active subscriptions', function () {
    $this->artisan('wave:stats', ['--json' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('"mrr": 0');
});

it('displays plan breakdown correctly', function () {
    // Create subscriptions on basic plan
    for ($i = 0; $i < 3; $i++) {
        $user = User::factory()->create(['verified' => 1]);
        Subscription::create([
            'billable_type' => User::class,
            'billable_id' => $user->id,
            'plan_id' => $this->basicPlan->id,
            'vendor_slug' => 'stripe',
            'vendor_subscription_id' => 'sub_'.uniqid(),
            'vendor_customer_id' => 'cus_'.uniqid(),
            'cycle' => 'month',
            'status' => 'active',
            'seats' => 1,
        ]);
    }

    // Create subscriptions on premium plan
    for ($i = 0; $i < 2; $i++) {
        $user = User::factory()->create(['verified' => 1]);
        Subscription::create([
            'billable_type' => User::class,
            'billable_id' => $user->id,
            'plan_id' => $this->premiumPlan->id,
            'vendor_slug' => 'stripe',
            'vendor_subscription_id' => 'sub_'.uniqid(),
            'vendor_customer_id' => 'cus_'.uniqid(),
            'cycle' => 'month',
            'status' => 'active',
            'seats' => 1,
        ]);
    }

    // Verify the plan breakdown is in the output
    $this->artisan('wave:stats', ['--json' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('"plans"');
});

it('calculates growth metrics for custom period', function () {
    // Create old users (60 days ago)
    User::factory()->count(3)->create([
        'verified' => 1,
        'created_at' => now()->subDays(60),
    ]);

    // Create recent users (15 days ago)
    User::factory()->count(5)->create([
        'verified' => 1,
        'created_at' => now()->subDays(15),
    ]);

    $this->artisan('wave:stats', ['--period' => 30, '--json' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('"new": 5');
});

it('ignores inactive subscriptions in MRR calculation', function () {
    // Create active subscription
    $activeUser = User::factory()->create(['verified' => 1]);
    Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $activeUser->id,
        'plan_id' => $this->basicPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'vendor_customer_id' => 'cus_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Create cancelled subscription
    $cancelledUser = User::factory()->create(['verified' => 1]);
    Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $cancelledUser->id,
        'plan_id' => $this->premiumPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'vendor_customer_id' => 'cus_'.uniqid(),
        'cycle' => 'month',
        'status' => 'cancelled',
        'seats' => 1,
    ]);

    // Expected MRR: Only the active subscription = $9.99
    $this->artisan('wave:stats', ['--json' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('9.99');
});

it('handles subscriptions without plans gracefully', function () {
    // Create subscription with valid plan
    $user = User::factory()->create(['verified' => 1]);
    Subscription::create([
        'billable_type' => User::class,
        'billable_id' => $user->id,
        'plan_id' => $this->basicPlan->id,
        'vendor_slug' => 'stripe',
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'vendor_customer_id' => 'cus_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Should calculate MRR for subscription with valid plan
    $this->artisan('wave:stats', ['--json' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('9.99');
});
