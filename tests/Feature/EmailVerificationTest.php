<?php

use App\Models\User;
use Devdojo\Auth\Notifications\VerifyEmailWithCode;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Livewire\Volt\Volt;

function verificationUrl(User $user): string
{
    return URL::temporarySignedRoute('verification.verify', now()->addHour(), [
        'id' => $user->id,
        'hash' => sha1($user->email),
    ]);
}

// The login form asks for the email first, then the password.
function submitLoginForm(User $user)
{
    return Volt::test('auth.login')
        ->set('email', $user->email)
        ->call('authenticate')
        ->set('password', 'secret')
        ->call('authenticate')
        ->assertHasNoErrors();
}

beforeEach(function () {
    config(['devdojo.auth.settings.registration_require_email_verification' => true]);

    $this->user = User::factory()->create();
});

afterEach(function () {
    $this->user->forceDelete();
    User::withTrashed()->where('email', 'like', '%@verify-register.test')->forceDelete();
});

it('sends the verification email when a user registers', function () {
    Notification::fake();

    Volt::test('auth.register')
        ->set('email', 'new-user@verify-register.test')
        ->set('password', str_repeat('a', 12))
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'new-user@verify-register.test')->firstOrFail();

    expect($user->hasVerifiedEmail())->toBeFalse();
    Notification::assertSentTo($user, VerifyEmailWithCode::class);
});

it('sends unverified users to the verify page', function (string $url) {
    $this->actingAs($this->user)
        ->get($url)
        ->assertRedirect(route('verification.notice'));
})->with([
    '/dashboard',
    '/notifications',
    '/settings',
    '/settings/profile',
    '/settings/security',
    '/settings/api',
    '/settings/subscription',
    '/settings/notifications',
    '/settings/privacy',
    '/settings/activity',
    '/settings/export',
    '/settings/deletion',
    '/settings/social',
    '/settings/invoices',
    '/checkout/welcome',
    '/subscription/welcome',
]);

it('keeps blocking an unverified user after logging out and back in', function () {
    $this->actingAs($this->user)->get('/auth/verify')->assertOk();
    $this->post(route('logout'));
    $this->assertGuest();

    submitLoginForm($this->user);

    $this->assertAuthenticatedAs($this->user);
    $this->get('/dashboard')->assertRedirect(route('verification.notice'));
});

it('lets verified users in', function () {
    $this->user->markEmailAsVerified();

    $this->actingAs($this->user)->get('/dashboard')->assertOk();
});

it('lets unverified users in when verification is turned off', function () {
    config(['devdojo.auth.settings.registration_require_email_verification' => false]);

    $this->actingAs($this->user)->get('/dashboard')->assertOk();
});

it('verifies a link opened by a guest once they log in', function () {
    $url = verificationUrl($this->user);

    // The verify route sits behind auth, so a fresh browser is sent to log in first.
    $this->get($url)->assertRedirect(route('login'));

    submitLoginForm($this->user)->assertRedirect($url);

    $this->get($url)->assertRedirect();

    expect($this->user->fresh()->hasVerifiedEmail())->toBeTrue();
    $this->get('/dashboard')->assertOk();
});

it('rejects a verification link opened by a different user', function () {
    $other = User::factory()->create();

    $this->actingAs($other)->get(verificationUrl($this->user))->assertForbidden();

    expect($this->user->fresh()->hasVerifiedEmail())->toBeFalse();

    $other->forceDelete();
});

it('keeps checking verification on Livewire component updates', function () {
    expect(Livewire\Livewire::getPersistentMiddleware())->toContain(EnsureEmailIsVerified::class);
});

it('rejects a tampered verification link', function () {
    $this->actingAs($this->user)->get(verificationUrl($this->user).'x')->assertForbidden();

    expect($this->user->fresh()->hasVerifiedEmail())->toBeFalse();
});
