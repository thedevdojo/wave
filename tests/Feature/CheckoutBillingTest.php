<?php

use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Wave\Http\Livewire\Billing\Checkout;
use Wave\Http\Livewire\Billing\Update;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    config(['wave.billing_provider' => 'paddle', 'wave.paddle.env' => 'sandbox']);

    $this->user = User::factory()->create();

    $this->plans = collect(['Basic', 'Premium', 'Pro'])->map(fn (string $name) => Plan::create([
        'name' => $name,
        'features' => '',
        'monthly_price' => '10.00',
        'monthly_price_id' => 'pri_'.strtolower($name),
        'currency' => '€',
        'active' => true,
        'role_id' => Role::findByName('premium')->id,
    ]));

    $this->subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->user->id,
        'plan_id' => $this->plans[0]->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_1',
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);
    $this->user->syncRoles(['premium']);
});

describe('Change plan', function () {
    it('gives each plan its own confirmation modal', function () {
        $html = Livewire::actingAs($this->user)->test(Checkout::class, ['change' => true])->html();

        foreach ($this->plans as $plan) {
            expect(substr_count($html, 'data-fi-modal-id="change-plan-modal-'.$plan->id.'"'))->toBe(1);
        }
        expect($html)->not->toContain('data-fi-modal-id="change-plan-modal"');
    });

    it('switches to the chosen plan', function () {
        Http::fake(['sandbox-api.paddle.com/subscriptions/sub_1' => Http::response(['data' => []])]);

        Livewire::actingAs($this->user)
            ->test(Checkout::class, ['change' => true])
            ->call('switchPlan', $this->plans[1]->id)
            ->assertRedirect('/settings/subscription');

        expect($this->subscription->fresh()->plan_id)->toBe($this->plans[1]->id);
        Http::assertSent(fn (Request $request) => $request['items'][0]['price_id'] === 'pri_premium');
    });

    it('tells the user when paddle rejects the plan change', function () {
        Http::fake(['sandbox-api.paddle.com/subscriptions/sub_1' => Http::response(['error' => []], 400)]);

        Livewire::actingAs($this->user)
            ->test(Checkout::class, ['change' => true])
            ->call('switchPlan', $this->plans[1]->id)
            ->assertNoRedirect()
            ->assertNotified('Your plan could not be changed. Please try again or contact support.');

        expect($this->subscription->fresh()->plan_id)->toBe($this->plans[0]->id);
    });
});

describe('Checkout', function () {
    it('labels plan cards without the billing cycle', function () {
        Livewire::actingAs($this->user)
            ->test(Checkout::class)
            ->assertSeeHtml('<span class="text-lg md:text-xl font-semibold rounded-full">')
            ->assertDontSee('Basic Plan month');
    });

    it('shows the loading state on the stripe subscribe button', function () {
        config(['wave.billing_provider' => 'stripe']);

        Livewire::actingAs($this->user)
            ->test(Checkout::class)
            ->assertSeeHtml('wire:target="redirectToStripeCheckout"');
    });
});

it('tells the user when paddle rejects the cancellation', function () {
    Http::fake([
        'sandbox-api.paddle.com/subscriptions/sub_1/cancel' => Http::response(['error' => []], 400),
        'sandbox-api.paddle.com/subscriptions/sub_1' => Http::response(['data' => [
            'current_billing_period' => ['ends_at' => '2026-12-01T00:00:00Z'],
            'management_urls' => ['cancel' => 'https://example.com/cancel', 'update_payment_method' => 'https://example.com/update'],
        ]]),
    ]);

    Livewire::actingAs($this->user)
        ->test(Update::class)
        ->call('cancel')
        ->assertSet('cancellation_scheduled', false)
        ->assertNotified('Your subscription could not be cancelled. Please try again or contact support.');

    expect($this->subscription->fresh()->ends_at)->toBeNull();
});

describe('Invoices', function () {
    it('shows paddle invoices in the plan currency with tax included', function () {
        Http::fake(['sandbox-api.paddle.com/transactions*' => Http::response(['data' => [[
            'id' => 'txn_1',
            'subscription_id' => 'sub_1',
            'created_at' => '2026-01-15T10:00:00Z',
            'details' => ['totals' => ['subtotal' => '1000', 'tax' => '200', 'total' => '1200']],
        ]]])]);

        $this->actingAs($this->user)
            ->get('/settings/invoices')
            ->assertOk()
            ->assertSee('€12.00')
            ->assertDontSee('$10.00');
    });
});

it('creates users with a verified email', function () {
    config(['devdojo.auth.settings.registration_require_email_verification' => true]);

    $this->artisan('app:create-user')
        ->expectsQuestion('Enter the user\'s name', 'New User')
        ->expectsQuestion('Enter the user\'s email', 'new@example.com')
        ->expectsQuestion('Enter the user\'s username', 'newuser')
        ->expectsQuestion('Enter the user\'s password', 'a-long-password-123')
        ->expectsChoice('Select a role for the user', 'registered', Role::pluck('name')->all())
        ->assertSuccessful();

    expect(User::where('email', 'new@example.com')->first()->hasVerifiedEmail())->toBeTrue();
});
