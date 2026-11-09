<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    $this->plan = Plan::create([
        'name' => 'Stats Plan',
        'features' => '',
        'monthly_price' => '10',
        'yearly_price' => '100',
        'monthly_price_id' => 'price_stats_monthly',
        'role_id' => 1,
        'active' => 1,
    ]);

    $this->subscription = function (array $attributes = []) {
        $subscription = Subscription::create(array_merge([
            'billable_type' => 'user',
            'billable_id' => User::factory()->create()->id,
            'plan_id' => $this->plan->id,
            'vendor_slug' => 'stripe',
            'vendor_subscription_id' => 'sub_'.uniqid(),
            'cycle' => 'month',
            'status' => 'active',
            'seats' => 1,
        ], array_diff_key($attributes, array_flip(['created_at', 'updated_at']))));

        // Timestamps aren't fillable, so set them afterwards
        if (isset($attributes['created_at']) || isset($attributes['updated_at'])) {
            $subscription->timestamps = false;
            $subscription->forceFill(array_intersect_key($attributes, array_flip(['created_at', 'updated_at'])))->save();
        }

        return $subscription;
    };

    $this->stats = function (int $period = 30): array {
        Artisan::call('wave:stats', ['--json' => true, '--period' => $period]);

        return json_decode(Artisan::output(), true);
    };
});

it('counts verified users by their verified email', function () {
    User::factory()->count(3)->create(['email_verified_at' => now(), 'verified' => null]);
    User::factory()->count(2)->create(['email_verified_at' => null]);

    expect(($this->stats)()['users']['verified'])->toBe(3);
});

it('counts users on a trial without a subscription', function () {
    User::factory()->count(2)->create(['trial_ends_at' => now()->addDays(7)]);
    User::factory()->create(['trial_ends_at' => now()->subDay()]);
    ($this->subscription)(['billable_id' => User::factory()->create(['trial_ends_at' => now()->addDays(7)])->id]);

    expect(($this->stats)()['users']['on_trial'])->toBe(2);
});

it('only counts active subscriptions that are set to end as cancelled', function () {
    ($this->subscription)(['ends_at' => now()->addDays(10)]);
    ($this->subscription)(['status' => 'cancelled', 'ends_at' => now()->addDays(10)]);
    ($this->subscription)();

    expect(($this->stats)()['subscriptions']['cancelled'])->toBe(1);
});

it('counts new subscriptions even if they were cancelled since', function () {
    ($this->subscription)(['created_at' => now()->subDays(5)]);
    ($this->subscription)(['status' => 'cancelled', 'created_at' => now()->subDays(5)]);
    ($this->subscription)(['created_at' => now()->subDays(45)]);

    expect(($this->stats)()['subscriptions']['new'])->toBe(2);
});

it('measures churn against the subscriptions active at the start of the period', function () {
    foreach (range(1, 4) as $i) {
        ($this->subscription)(['created_at' => now()->subDays(60), 'updated_at' => now()->subDays(60)]);
    }
    ($this->subscription)(['status' => 'cancelled', 'created_at' => now()->subDays(60), 'updated_at' => now()->subDays(10)]);

    // Started and cancelled inside the period, so not part of the starting base
    ($this->subscription)(['status' => 'cancelled', 'created_at' => now()->subDays(20), 'updated_at' => now()->subDays(10)]);

    // Cancelled before the period started
    ($this->subscription)(['status' => 'cancelled', 'created_at' => now()->subDays(90), 'updated_at' => now()->subDays(40)]);

    // 1 of the 5 that were active 30 days ago
    expect(($this->stats)()['subscriptions']['churn_rate'])->toEqual(20);
});

it('has no growth rate when the previous period had nothing to compare with', function () {
    User::factory()->count(3)->create();

    expect(($this->stats)()['users']['growth_rate'])->toBeNull();
});

it('shows n/a for a growth rate it cannot calculate', function () {
    User::factory()->count(3)->create();

    $this->artisan('wave:stats')->expectsOutputToContain('n/a')->assertSuccessful();
});
