<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Wave\Http\Controllers\SubscriptionController;
use Wave\Http\Livewire\Billing\Checkout;
use Wave\Http\Livewire\Billing\Update;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    config(['wave.billing_provider' => 'paddle', 'wave.paddle.env' => 'sandbox']);

    $this->user = User::factory()->create();
    $this->user->syncRoles(['registered']);

    $this->plan = Plan::create([
        'name' => 'Premium',
        'features' => '',
        'monthly_price_id' => 'pri_monthly',
        'active' => true,
        'role_id' => Role::findByName('premium')->id,
    ]);
});

function paddleCheckoutTransaction(array $overrides = []): array
{
    return ['data' => array_replace_recursive([
        'id' => 'txn_checkout',
        'status' => 'completed',
        'customer_id' => 'ctm_1',
        'subscription_id' => 'sub_1',
        'custom_data' => ['user_id' => (string) test()->user->id],
        'items' => [['price' => ['id' => 'pri_monthly']]],
    ], $overrides)];
}

function verifyPaddleCheckout(string $transactionId = 'txn_checkout')
{
    return Livewire::actingAs(test()->user)->test(Checkout::class)->call('verifyPaddleTransaction', $transactionId);
}

it('creates the subscription for a paid transaction started by the user', function () {
    Http::fake(['sandbox-api.paddle.com/transactions/txn_checkout' => Http::response(paddleCheckoutTransaction())]);

    verifyPaddleCheckout();

    $subscription = Subscription::sole();
    expect($subscription->billable_id)->toBe($this->user->id)
        ->and($subscription->vendor_subscription_id)->toBe('sub_1')
        ->and($this->user->fresh()->getRoleNames()->all())->toBe(['premium']);
});

it('rejects a transaction that is not paid yet', function () {
    Http::fake(['sandbox-api.paddle.com/*' => Http::response(paddleCheckoutTransaction(['status' => 'ready']))]);

    verifyPaddleCheckout();

    expect(Subscription::count())->toBe(0)
        ->and($this->user->fresh()->getRoleNames()->all())->toBe(['registered']);
});

it('rejects a transaction started by another user', function (?array $customData) {
    Http::fake(['sandbox-api.paddle.com/*' => Http::response(paddleCheckoutTransaction(['custom_data' => $customData]))]);

    verifyPaddleCheckout();

    expect(Subscription::count())->toBe(0)
        ->and($this->user->fresh()->getRoleNames()->all())->toBe(['registered']);
})->with([
    'someone else' => [['user_id' => '999999']],
    'no custom data' => [null],
]);

it('rejects a transaction that was already used', function () {
    Http::fake(['sandbox-api.paddle.com/*' => Http::response(paddleCheckoutTransaction())]);

    verifyPaddleCheckout();
    verifyPaddleCheckout();

    expect(Subscription::count())->toBe(1);
});

it('rejects a malformed transaction id without calling paddle', function () {
    Http::fake();

    verifyPaddleCheckout('txn_1/../../customers');

    Http::assertNothingSent();
    expect(Subscription::count())->toBe(0);
});

it('does not let the client change the paddle url', function (string $component) {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->plan->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_1',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);
    Http::fake(['sandbox-api.paddle.com/subscriptions/sub_1' => Http::response(['data' => [
        'current_billing_period' => ['ends_at' => '2026-12-31T00:00:00Z'],
        'management_urls' => ['cancel' => 'https://paddle.test/cancel', 'update_payment_method' => 'https://paddle.test/update'],
    ]])]);

    Livewire::actingAs($this->user)->test($component)->set('paddle_url', 'https://example.com');
})->with(['checkout' => [Checkout::class], 'update' => [Update::class]])->throws(CannotUpdateLockedPropertyException::class);

it('only redirects to invoices for the user\'s own subscriptions', function () {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->plan->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_mine',
        'cycle' => 'month',
        'status' => 'cancelled',
        'seats' => 1,
    ]);

    Http::fake([
        'sandbox-api.paddle.com/transactions/txn_mine/invoice' => Http::response(['data' => ['url' => 'https://paddle.test/invoice.pdf']]),
        'sandbox-api.paddle.com/transactions/txn_mine' => Http::response(['data' => ['subscription_id' => 'sub_mine']]),
        'sandbox-api.paddle.com/transactions/txn_theirs' => Http::response(['data' => ['subscription_id' => 'sub_theirs']]),
    ]);

    $request = Request::create('/settings/invoices/txn_mine');
    $request->setUserResolver(fn () => $this->user);

    expect(app(SubscriptionController::class)->invoice($request, 'txn_mine')->getTargetUrl())->toBe('https://paddle.test/invoice.pdf');

    expect(fn () => app(SubscriptionController::class)->invoice($request, 'txn_theirs'))->toThrow(NotFoundHttpException::class);
});

it('returns a 404 when paddle has no invoice for the transaction', function (array $invoiceResponse) {
    Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->plan->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_mine',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    Http::fake([
        'sandbox-api.paddle.com/transactions/txn_mine/invoice' => Http::response(...$invoiceResponse),
        'sandbox-api.paddle.com/transactions/txn_mine' => Http::response(['data' => ['subscription_id' => 'sub_mine']]),
    ]);

    $request = Request::create('/settings/invoices/txn_mine');
    $request->setUserResolver(fn () => $this->user);

    expect(fn () => app(SubscriptionController::class)->invoice($request, 'txn_mine'))->toThrow(NotFoundHttpException::class);
})->with([
    'not billed yet' => [[['error' => ['code' => 'transaction_not_billed']], 400]],
    'paddle error' => [['', 500]],
    'no url in the response' => [[['data' => []], 200]],
]);
