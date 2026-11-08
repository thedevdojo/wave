<?php

use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Wave\Actions\Billing\Paddle\AddSubscriptionIdFromTransaction;
use Wave\Http\Livewire\Billing\Update;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    config(['wave.billing_provider' => 'paddle', 'wave.paddle.env' => 'sandbox']);
    Sleep::fake();

    $this->user = User::factory()->create();

    $this->plan = Plan::create([
        'name' => 'Premium',
        'description' => 'Premium plan',
        'features' => 'Feature 1',
        'monthly_price' => '10.00',
        'monthly_price_id' => 'pri_monthly',
        'active' => true,
        'role_id' => Role::findByName('premium')->id,
    ]);

    $this->subscribe = fn (array $attributes = []) => Subscription::create(array_merge([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->plan->id,
        'vendor_slug' => 'paddle',
        'vendor_transaction_id' => 'txn_checkout',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ], $attributes));
});

function paddleTransaction(?string $subscriptionId): array
{
    return ['data' => ['id' => 'txn_checkout', 'status' => 'completed', 'subscription_id' => $subscriptionId]];
}

it('stores the subscription id once paddle has attached it to the transaction', function () {
    $subscription = ($this->subscribe)();

    Http::fake(['sandbox-api.paddle.com/transactions/txn_checkout' => Http::sequence()
        ->push(paddleTransaction(null))
        ->push(paddleTransaction('sub_checkout')),
    ]);

    $result = app(AddSubscriptionIdFromTransaction::class)('txn_checkout');

    expect($result->id)->toBe($subscription->id)
        ->and($subscription->fresh()->vendor_subscription_id)->toBe('sub_checkout');

    Http::assertSentCount(2);
    Sleep::assertSleptTimes(1);
});

it('gives up without sleeping after the last attempt', function () {
    ($this->subscribe)();

    Http::fake(['sandbox-api.paddle.com/*' => Http::response(paddleTransaction(null))]);

    expect(app(AddSubscriptionIdFromTransaction::class)('txn_checkout'))->toBeNull();

    Http::assertSentCount(5);
    Sleep::assertSleptTimes(4);
});

it('returns null when there is no local subscription for the transaction', function () {
    Http::fake(['sandbox-api.paddle.com/*' => Http::response(paddleTransaction('sub_checkout'))]);

    expect(app(AddSubscriptionIdFromTransaction::class)('txn_checkout'))->toBeNull();
});

it('does not block the subscription settings page while waiting on paddle', function () {
    ($this->subscribe)();

    Http::fake(['sandbox-api.paddle.com/*' => Http::response(paddleTransaction(null))]);

    Livewire::actingAs($this->user)
        ->test(Update::class)
        ->assertSet('error_retrieving_data', true);

    Http::assertSentCount(1);
    Sleep::assertNeverSlept();
});

it('lists paddle invoices for cancelled subscriptions too', function () {
    ($this->subscribe)(['vendor_subscription_id' => 'sub_old', 'status' => 'cancelled']);
    ($this->subscribe)(['vendor_subscription_id' => 'sub_older', 'status' => 'cancelled']);

    Http::fake(['sandbox-api.paddle.com/transactions*' => Http::response(['data' => [[
        'id' => 'txn_old',
        'created_at' => '2026-01-15T10:00:00Z',
        'details' => ['totals' => ['subtotal' => '1000']],
    ]]])]);

    $this->actingAs($this->user)
        ->get('/settings/invoices')
        ->assertOk()
        ->assertSee('/settings/invoices/txn_old');

    Http::assertSent(fn (Request $request) => $request['subscription_id'] === 'sub_old,sub_older');
});
