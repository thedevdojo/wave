<?php

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Stripe\ApiRequestor;
use Tests\Support\FakeStripeClient;
use Wave\Page;
use Wave\Plan;
use Wave\Post;
use Wave\ProfileKeyValue;
use Wave\Subscription;

beforeEach(function () {
    $this->user = User::factory()->create();

    config(['wave.stripe.secret_key' => 'sk_test_fake']);
    $this->stripe = new FakeStripeClient;
    ApiRequestor::setHttpClient($this->stripe);
});

afterEach(function () {
    ApiRequestor::setHttpClient(null);
});

function subscriptionFor(User $user, string $vendor, array $attributes = []): Subscription
{
    $plan = Plan::create([
        'name' => 'Premium',
        'description' => 'Premium plan',
        'features' => 'Feature 1',
        'monthly_price' => '10.00',
        'monthly_price_id' => 'price_'.Str::random(8),
        'active' => true,
        'role_id' => Role::findByName('premium')->id,
    ]);

    return Subscription::create(array_merge([
        'billable_type' => 'user',
        'billable_id' => $user->id,
        'plan_id' => $plan->id,
        'vendor_slug' => $vendor,
        'vendor_customer_id' => 'cus_123',
        'vendor_subscription_id' => 'sub_'.Str::random(8),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ], $attributes));
}

const STRIPE_SUBSCRIPTION = '/v1/subscriptions/sub_123';

function stripeSubscriptionStatus(string $status = 'canceled'): array
{
    return ['id' => 'sub_123', 'object' => 'subscription', 'status' => $status];
}

function stripeApiError(string $type = 'invalid_request_error'): array
{
    return ['error' => ['type' => $type, 'message' => 'Something went wrong']];
}

it('can schedule account deletion', function () {
    $this->actingAs($this->user);

    // Initially no deletion scheduled
    expect($this->user->deletion_scheduled_at)->toBeNull();

    // Schedule deletion (simulate Livewire component action)
    $this->user->deletion_scheduled_at = now()->addDays(30);
    $this->user->save();

    $this->user->refresh();

    expect($this->user->deletion_scheduled_at)->not->toBeNull();
    expect($this->user->deletion_scheduled_at->isFuture())->toBeTrue();
});

it('can cancel scheduled account deletion', function () {
    $this->actingAs($this->user);

    // Schedule deletion first
    $this->user->deletion_scheduled_at = now()->addDays(30);
    $this->user->save();

    expect($this->user->deletion_scheduled_at)->not->toBeNull();

    // Cancel deletion
    $this->user->deletion_scheduled_at = null;
    $this->user->save();

    $this->user->refresh();

    expect($this->user->deletion_scheduled_at)->toBeNull();
});

it('deletion page requires authentication', function () {
    $response = $this->get(route('settings.deletion'));

    $response->assertRedirect(route('login'));
});

it('authenticated user can access deletion page', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('settings.deletion'));

    $response->assertOk();
    $response->assertSee('Account Deletion');
    $response->assertSee('Delete Your Account');
});

it('shows scheduled deletion warning when deletion is scheduled', function () {
    $this->actingAs($this->user);

    // Schedule deletion
    $this->user->deletion_scheduled_at = now()->addDays(15);
    $this->user->save();

    $response = $this->get(route('settings.deletion'));

    $response->assertOk();
    $response->assertSee('Account Deletion Scheduled');
    $response->assertSee('Cancel Deletion');
});

it('shows deletion form when no deletion is scheduled', function () {
    $this->actingAs($this->user);

    // Ensure no deletion scheduled
    $this->user->deletion_scheduled_at = null;
    $this->user->save();

    $response = $this->get(route('settings.deletion'));

    $response->assertOk();
    $response->assertSee('Schedule Account Deletion');
    $response->assertSee('Confirm Your Password');
});

it('calculates days remaining correctly', function () {
    $this->actingAs($this->user);

    // Schedule deletion 10 days from now
    $this->user->deletion_scheduled_at = now()->addDays(10);
    $this->user->save();

    $daysRemaining = ceil(now()->diffInDays($this->user->deletion_scheduled_at, false));

    expect($daysRemaining)->toBe(10.0);
});

it('uses soft deletes for user model', function () {
    // Create a fresh user without any dependencies
    $freshUser = User::factory()->create();
    $userId = $freshUser->id;

    // Soft delete the user
    $freshUser->delete();

    // User should not be found in normal queries
    $foundUser = User::find($userId);
    expect($foundUser)->toBeNull();

    // But should be found with trashed
    $trashedUser = User::withTrashed()->find($userId);
    expect($trashedUser)->not->toBeNull();
    expect($trashedUser->deleted_at)->not->toBeNull();
});

it('password must be correct to schedule deletion', function () {
    $this->actingAs($this->user);

    // Verify password check would fail with wrong password
    $wrongPassword = 'wrongpassword';
    $isCorrect = Hash::check($wrongPassword, $this->user->password);

    expect($isCorrect)->toBeFalse();
});

it('deletion scheduled date is properly formatted', function () {
    $this->actingAs($this->user);

    $scheduledDate = now()->addDays(30);
    $this->user->deletion_scheduled_at = $scheduledDate;
    $this->user->save();

    $this->user->refresh();

    $formattedDate = $this->user->deletion_scheduled_at->format('F j, Y');

    expect($formattedDate)->toBeString();
    expect($formattedDate)->toContain($scheduledDate->format('Y'));
});

it('multiple users can have different deletion schedules', function () {
    $user1 = $this->user;
    $user2 = User::factory()->create();

    // Schedule deletion for user1
    $user1->deletion_scheduled_at = now()->addDays(20);
    $user1->save();

    // Schedule deletion for user2 with different date
    $user2->deletion_scheduled_at = now()->addDays(10);
    $user2->save();

    expect($user1->deletion_scheduled_at)->not->toEqual($user2->deletion_scheduled_at);
    expect($user1->deletion_scheduled_at->isAfter($user2->deletion_scheduled_at))->toBeTrue();
});

it('can check if deletion is scheduled', function () {
    $this->actingAs($this->user);

    // Not scheduled
    $this->user->deletion_scheduled_at = null;
    $this->user->save();
    expect($this->user->deletion_scheduled_at)->toBeNull();

    // Schedule it
    $this->user->deletion_scheduled_at = now()->addDays(30);
    $this->user->save();
    $this->user->refresh();
    expect($this->user->deletion_scheduled_at)->not->toBeNull();
});

it('processes expired deletions with artisan command', function () {
    // Create users with different deletion schedules
    $userToDelete = User::factory()->create([
        'deletion_scheduled_at' => now()->subDay(), // Past date
    ]);

    $userNotToDelete = User::factory()->create([
        'deletion_scheduled_at' => now()->addDays(10), // Future date
    ]);

    $userWithoutDeletion = User::factory()->create([
        'deletion_scheduled_at' => null,
    ]);

    // Run the command
    $this->artisan('accounts:process-deletions')
        ->expectsOutput('Processing scheduled account deletions...')
        ->expectsOutput("Deleted account: {$userToDelete->email}")
        ->expectsOutput('Successfully deleted 1 account(s).')
        ->assertExitCode(0);

    // Verify the user was deleted
    expect(User::withTrashed()->find($userToDelete->id))->toBeNull();

    // Verify other users still exist
    expect(User::find($userNotToDelete->id))->not->toBeNull();
    expect(User::find($userWithoutDeletion->id))->not->toBeNull();
});

it('rounds days remaining to avoid decimals', function () {
    $this->actingAs($this->user);

    // Schedule deletion for 29.999 days from now
    $this->user->deletion_scheduled_at = now()->addHours(719.9);
    $this->user->save();

    $daysRemaining = (int) ceil(now()->diffInDays($this->user->deletion_scheduled_at, false));

    // Should round to 30, not show 29.999940712002
    expect($daysRemaining)->toBe(30);
});

it('deletes a user who authored posts and pages and keeps the content', function () {
    $user = User::factory()->create(['deletion_scheduled_at' => now()->subDay()]);
    $post = Post::factory()->create(['author_id' => $user->id]);
    $page = Page::create([
        'author_id' => $user->id,
        'title' => 'About',
        'body' => 'About us',
        'slug' => 'about-us',
        'status' => 'ACTIVE',
    ]);

    $this->artisan('accounts:process-deletions')
        ->expectsOutput("Deleted account: {$user->email}")
        ->assertExitCode(0);

    expect(User::withTrashed()->find($user->id))->toBeNull();
    expect($post->fresh())->not->toBeNull();
    expect($post->fresh()->author_id)->toBeNull();
    expect($page->fresh())->not->toBeNull();
    expect($page->fresh()->author_id)->toBeNull();
});

it('cancels an active stripe subscription before deleting the account', function () {
    $this->stripe->respond('delete', STRIPE_SUBSCRIPTION, stripeSubscriptionStatus());
    $user = User::factory()->create(['deletion_scheduled_at' => now()->subDay()]);
    $subscription = subscriptionFor($user, 'stripe', ['vendor_subscription_id' => 'sub_123']);

    $this->artisan('accounts:process-deletions')
        ->expectsOutput("Deleted account: {$user->email}")
        ->assertExitCode(0);

    expect($this->stripe->requests)->toHaveCount(1);
    expect($this->stripe->requested('delete', STRIPE_SUBSCRIPTION))->toHaveCount(1);
    expect(User::withTrashed()->find($user->id))->toBeNull();
    expect(Subscription::find($subscription->id))->toBeNull();
});

it('cancels an active paddle subscription before deleting the account', function () {
    config(['wave.paddle.env' => 'sandbox']);
    Http::fake(['sandbox-api.paddle.com/*' => Http::response(['data' => ['status' => 'canceled']])]);
    $user = User::factory()->create(['deletion_scheduled_at' => now()->subDay()]);
    subscriptionFor($user, 'paddle', ['vendor_subscription_id' => 'sub_123']);

    $this->artisan('accounts:process-deletions')
        ->expectsOutput("Deleted account: {$user->email}")
        ->assertExitCode(0);

    Http::assertSent(fn ($request) => $request->url() === 'https://sandbox-api.paddle.com/subscriptions/sub_123/cancel'
        && $request['effective_from'] === 'immediately');
    expect(User::withTrashed()->find($user->id))->toBeNull();
});

it('does not cancel subscriptions that are already cancelled', function () {
    $user = User::factory()->create(['deletion_scheduled_at' => now()->subDay()]);
    subscriptionFor($user, 'stripe', ['status' => 'cancelled']);

    $this->artisan('accounts:process-deletions')->assertExitCode(0);

    expect($this->stripe->requests)->toBeEmpty();
    expect(User::withTrashed()->find($user->id))->toBeNull();
});

it('keeps the account when the subscription cannot be cancelled', function (string $vendor) {
    Log::spy();
    $this->stripe->respond('delete', STRIPE_SUBSCRIPTION, stripeApiError('api_error'), 500);
    Http::fake(['*' => Http::response([], 500)]);
    $user = User::factory()->create(['deletion_scheduled_at' => now()->subDay()]);
    $subscription = subscriptionFor($user, $vendor, ['vendor_subscription_id' => 'sub_123']);

    $this->artisan('accounts:process-deletions')
        ->expectsOutputToContain("Failed to delete account {$user->email}")
        ->expectsOutput('Successfully deleted 0 account(s).')
        ->assertExitCode(0);

    expect(User::find($user->id))->not->toBeNull();
    expect($subscription->fresh()->status)->toBe('active');
    Log::shouldHaveReceived('error')->withArgs(fn ($message) => str_contains($message, $user->email))->once();
})->with(['stripe', 'paddle']);

it('removes the profile fields, notifications and subscriptions of a deleted account', function () {
    $user = User::factory()->create(['deletion_scheduled_at' => now()->subDay()]);
    $other = User::factory()->create();

    foreach ([$user, $other] as $owner) {
        $owner->setProfileKeyValue('about', 'Hello');
        $owner->notifications()->create([
            'id' => Str::uuid()->toString(),
            'type' => 'test',
            'data' => ['message' => 'Hi'],
        ]);
        subscriptionFor($owner, 'stripe', ['status' => 'cancelled']);
    }

    $this->artisan('accounts:process-deletions')->assertExitCode(0);

    expect(ProfileKeyValue::where('keyvalue_id', $user->id)->count())->toBe(0);
    expect(DatabaseNotification::where('notifiable_id', $user->id)->count())->toBe(0);
    expect(Subscription::where('billable_id', $user->id)->count())->toBe(0);

    expect($other->profileKeyValues()->count())->toBe(1);
    expect($other->notifications()->count())->toBe(1);
    expect($other->subscriptions()->count())->toBe(1);
});

it('deletes the account when the stripe subscription was already cancelled on stripe', function () {
    $this->stripe
        ->respond('delete', STRIPE_SUBSCRIPTION, stripeApiError(), 400)
        ->respond('get', STRIPE_SUBSCRIPTION, stripeSubscriptionStatus('canceled'));
    $user = User::factory()->create(['deletion_scheduled_at' => now()->subDay()]);
    subscriptionFor($user, 'stripe', ['vendor_subscription_id' => 'sub_123']);

    $this->artisan('accounts:process-deletions')
        ->expectsOutput("Deleted account: {$user->email}")
        ->assertExitCode(0);

    expect(array_column($this->stripe->requests, 'key'))->toBe([
        'delete '.STRIPE_SUBSCRIPTION,
        'get '.STRIPE_SUBSCRIPTION,
    ]);
    expect(User::withTrashed()->find($user->id))->toBeNull();
});

it('keeps the account when stripe refuses to cancel a subscription that is still active', function () {
    $this->stripe
        ->respond('delete', STRIPE_SUBSCRIPTION, stripeApiError(), 400)
        ->respond('get', STRIPE_SUBSCRIPTION, stripeSubscriptionStatus('active'));
    $user = User::factory()->create(['deletion_scheduled_at' => now()->subDay()]);
    $subscription = subscriptionFor($user, 'stripe', ['vendor_subscription_id' => 'sub_123']);

    $this->artisan('accounts:process-deletions')
        ->expectsOutput('Successfully deleted 0 account(s).')
        ->assertExitCode(0);

    expect(User::find($user->id))->not->toBeNull();
    expect($subscription->fresh()->status)->toBe('active');
});

it('deletes the account when the paddle subscription was already cancelled on paddle', function () {
    config(['wave.paddle.env' => 'sandbox']);
    Http::fake([
        'sandbox-api.paddle.com/subscriptions/sub_123/cancel' => Http::response(['error' => ['code' => 'subscription_is_canceled_action_invalid']], 400),
        'sandbox-api.paddle.com/subscriptions/sub_123' => Http::response(['data' => ['status' => 'canceled']]),
    ]);
    $user = User::factory()->create(['deletion_scheduled_at' => now()->subDay()]);
    subscriptionFor($user, 'paddle', ['vendor_subscription_id' => 'sub_123']);

    $this->artisan('accounts:process-deletions')
        ->expectsOutput("Deleted account: {$user->email}")
        ->assertExitCode(0);

    expect(User::withTrashed()->find($user->id))->toBeNull();
});

it('keeps the account when paddle refuses to cancel a subscription that is still active', function () {
    config(['wave.paddle.env' => 'sandbox']);
    Http::fake([
        'sandbox-api.paddle.com/subscriptions/sub_123/cancel' => Http::response(['error' => ['code' => 'subscription_locked_processing']], 400),
        'sandbox-api.paddle.com/subscriptions/sub_123' => Http::response(['data' => ['status' => 'active']]),
    ]);
    $user = User::factory()->create(['deletion_scheduled_at' => now()->subDay()]);
    $subscription = subscriptionFor($user, 'paddle', ['vendor_subscription_id' => 'sub_123']);

    $this->artisan('accounts:process-deletions')
        ->expectsOutput('Successfully deleted 0 account(s).')
        ->assertExitCode(0);

    expect(User::find($user->id))->not->toBeNull();
    expect($subscription->fresh()->status)->toBe('active');
});

it('deletes the account when the vendor no longer knows the subscription', function (string $vendor) {
    Log::spy();
    config(['wave.paddle.env' => 'sandbox']);
    $this->stripe->respond('delete', STRIPE_SUBSCRIPTION, stripeApiError(), 404);
    Http::fake(['*' => Http::response(['error' => ['code' => 'not_found']], 404)]);
    $user = User::factory()->create(['deletion_scheduled_at' => now()->subDay()]);
    subscriptionFor($user, $vendor, ['vendor_subscription_id' => 'sub_123']);

    $this->artisan('accounts:process-deletions')
        ->expectsOutput("Deleted account: {$user->email}")
        ->assertExitCode(0);

    expect(User::withTrashed()->find($user->id))->toBeNull();
    Log::shouldHaveReceived('warning')->withArgs(fn ($message) => str_contains($message, 'not found with the vendor'))->once();
})->with(['stripe', 'paddle']);

it('cancels a subscription with no vendor id locally and logs it', function (string $vendor) {
    Log::spy();
    Http::fake();
    $user = User::factory()->create(['deletion_scheduled_at' => now()->subDay()]);
    subscriptionFor($user, $vendor, ['vendor_subscription_id' => null]);

    $this->artisan('accounts:process-deletions')
        ->expectsOutput("Deleted account: {$user->email}")
        ->assertExitCode(0);

    expect($this->stripe->requests)->toBeEmpty();
    Http::assertNothingSent();
    expect(User::withTrashed()->find($user->id))->toBeNull();
    Log::shouldHaveReceived('warning')->withArgs(fn ($message) => str_contains($message, 'no vendor id'))->once();
})->with(['stripe', 'paddle']);
