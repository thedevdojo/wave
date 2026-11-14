<?php

use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Stripe\ApiRequestor;
use Tests\Support\FakeStripeClient;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    config(['wave.stripe.secret_key' => 'sk_test_fake', 'wave.paddle.api_key' => 'fake', 'wave.paddle.env' => 'sandbox']);
    $this->stripe = new FakeStripeClient;
    ApiRequestor::setHttpClient($this->stripe);
    Http::preventStrayRequests();
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
    $this->user = User::factory()->create();
    $this->plan = Plan::create(['name' => 'Deletion plan', 'active' => true, 'features' => '', 'role_id' => Role::findByName('premium')->id]);
    $this->subscribe = fn (string $id, string $vendor = 'stripe', string $status = 'active') => Subscription::create([
        'billable_type' => 'user', 'billable_id' => $this->user->id, 'plan_id' => $this->plan->id,
        'vendor_slug' => $vendor, 'vendor_subscription_id' => $id,
        'cycle' => 'month', 'status' => $status, 'seats' => 1,
    ]);
});

afterEach(function () {
    ApiRequestor::setHttpClient(null);
});

function adminDeleteUser($test, string $surface)
{
    return match ($surface) {
        'table' => Livewire::test(ListUsers::class)->callTableAction('delete', $test->user),
        'edit' => Livewire::test(EditUser::class, ['record' => $test->user->id])->callAction('delete'),
        'bulk' => Livewire::test(ListUsers::class)->callTableBulkAction('delete', [$test->user]),
    };
}

it('cancels Stripe billing before deleting through every admin surface', function (string $surface) {
    $subscription = ($this->subscribe)('sub_delete');
    $this->stripe->respond('delete', '/v1/subscriptions/sub_delete', ['id' => 'sub_delete', 'object' => 'subscription', 'status' => 'canceled']);

    adminDeleteUser($this, $surface)->assertNotified();

    expect(User::withTrashed()->findOrFail($this->user->id)->trashed())->toBeTrue()
        ->and($subscription->fresh()->status)->toBe('cancelled')
        ->and($this->stripe->requested('delete', '/v1/subscriptions/sub_delete'))->toHaveCount(1);
})->with(['table', 'edit', 'bulk']);

it('retains the account and shows failure when Stripe cancellation fails', function (string $surface) {
    $subscription = ($this->subscribe)('sub_fail');
    $this->stripe->respond('delete', '/v1/subscriptions/sub_fail', ['error' => ['type' => 'api_error', 'message' => 'Provider unavailable']], 500);

    adminDeleteUser($this, $surface)->assertNotified($surface === 'bulk' ? null : 'User was not deleted');
    expect(User::find($this->user->id))->not->toBeNull()
        ->and($subscription->fresh()->status)->toBe('active');
})->with(['table', 'edit', 'bulk']);

it('cancels Paddle billing immediately before deletion', function () {
    $subscription = ($this->subscribe)('sub_paddle', 'paddle');
    Http::fake(['sandbox-api.paddle.com/subscriptions/sub_paddle/cancel' => Http::response(['data' => ['status' => 'canceled']])]);

    adminDeleteUser($this, 'table');

    Http::assertSent(fn ($request) => $request->method() === 'POST'
        && $request['effective_from'] === 'immediately');
    expect($subscription->fresh()->status)->toBe('cancelled')
        ->and(User::find($this->user->id))->toBeNull();
});

it('retains users when Paddle refuses cancellation', function () {
    $subscription = ($this->subscribe)('sub_paddle', 'paddle');
    Http::fake([
        'sandbox-api.paddle.com/subscriptions/sub_paddle/cancel' => Http::response([], 400),
        'sandbox-api.paddle.com/subscriptions/sub_paddle' => Http::response(['data' => ['status' => 'active']]),
    ]);
    adminDeleteUser($this, 'edit')->assertNotified('User was not deleted');
    expect(User::find($this->user->id))->not->toBeNull()
        ->and($subscription->fresh()->status)->toBe('active');
});

it('can delete a user with no active billing without provider calls', function () {
    ($this->subscribe)('sub_cancelled', status: 'cancelled');
    adminDeleteUser($this, 'table');
    expect(User::find($this->user->id))->toBeNull()
        ->and($this->stripe->requests)->toBeEmpty();
    Http::assertNothingSent();
});

it('retains an account when an active subscription has no provider id', function () {
    $subscription = ($this->subscribe)('sub_missing');
    $subscription->update(['vendor_subscription_id' => null]);
    adminDeleteUser($this, 'table')->assertNotified('User was not deleted');
    expect(User::find($this->user->id))->not->toBeNull()
        ->and($subscription->fresh()->status)->toBe('active')
        ->and($this->stripe->requests)->toBeEmpty();
});

it('retains failed bulk records while deleting successful ones', function () {
    $failed = ($this->subscribe)('sub_fail');
    $other = User::factory()->create();
    $this->stripe->respond('delete', '/v1/subscriptions/sub_fail', ['error' => ['type' => 'api_error', 'message' => 'Provider unavailable']], 500);

    Livewire::test(ListUsers::class)->callTableBulkAction('delete', [$this->user, $other])->assertNotified();

    expect(User::find($this->user->id))->not->toBeNull()
        ->and(User::find($other->id))->toBeNull()
        ->and($failed->fresh()->status)->toBe('active');
});

it('keeps successful provider cancellations persisted when another subscription fails and can retry', function () {
    $first = ($this->subscribe)('sub_first');
    $second = ($this->subscribe)('sub_second');
    $this->stripe->respond('delete', '/v1/subscriptions/sub_first', ['id' => 'sub_first', 'object' => 'subscription', 'status' => 'canceled']);
    $this->stripe->respond('delete', '/v1/subscriptions/sub_second', ['error' => ['type' => 'api_error', 'message' => 'Try later']], 500);
    $this->stripe->respond('delete', '/v1/subscriptions/sub_second', ['id' => 'sub_second', 'object' => 'subscription', 'status' => 'canceled']);

    adminDeleteUser($this, 'table')->assertNotified('User was not deleted');
    expect(User::find($this->user->id))->not->toBeNull()
        ->and($first->fresh()->status)->toBe('cancelled')
        ->and($second->fresh()->status)->toBe('active');

    adminDeleteUser($this, 'table');
    expect(User::find($this->user->id))->toBeNull()
        ->and($second->fresh()->status)->toBe('cancelled')
        ->and($this->stripe->requested('delete', '/v1/subscriptions/sub_first'))->toHaveCount(1);
});

it('allows deletion when Stripe confirms the subscription is already gone', function () {
    $subscription = ($this->subscribe)('sub_gone');
    $this->stripe->respond('delete', '/v1/subscriptions/sub_gone', ['error' => ['type' => 'invalid_request_error', 'message' => 'No such subscription']], 404);
    adminDeleteUser($this, 'table')->assertNotified();
    expect(User::find($this->user->id))->toBeNull()
        ->and($subscription->fresh()->status)->toBe('cancelled');
});

it('does not permit a non-admin to delete accounts through the admin page', function () {
    $this->actingAs($this->user);
    $this->get(UserResource::getUrl('index'))->assertForbidden();
    $this->get(UserResource::getUrl('edit', ['record' => $this->admin->id]))->assertForbidden();
    expect(User::find($this->user->id))->not->toBeNull()
        ->and($this->stripe->requests)->toBeEmpty();
});
