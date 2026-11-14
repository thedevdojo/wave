<?php

use App\Models\User;
use Livewire\Volt\Volt;

it('requires the configured second factor even when the client changes the display flag', function () {
    config(['devdojo.auth.settings.enable_2fa' => true]);
    $user = User::factory()->create(['password' => bcrypt('policy-password-123'), 'two_factor_confirmed_at' => now()]);

    Volt::test('auth.login')
        ->set('email', $user->email)
        ->set('password', 'policy-password-123')
        ->set('showPasswordField', true)
        ->set('twoFactorEnabled', false)
        ->call('authenticate')
        ->assertHasNoErrors()
        ->assertRedirect(route('auth.two-factor-challenge'));

    $this->assertGuest();
    expect(session('login.id'))->toBe($user->id);
});

it('reads the current server policy rather than a value retained from mounting', function () {
    config(['devdojo.auth.settings.enable_2fa' => false]);
    $user = User::factory()->create(['password' => bcrypt('policy-password-123'), 'two_factor_confirmed_at' => now()]);
    $component = Volt::test('auth.login')
        ->set('email', $user->email)->set('password', 'policy-password-123')->set('showPasswordField', true);

    config(['devdojo.auth.settings.enable_2fa' => true]);
    $component->call('authenticate')->assertRedirect(route('auth.two-factor-challenge'));

    $this->assertGuest();
    expect(session('login.id'))->toBe($user->id);
});

it('does not let a client enable a second factor disabled by server policy', function () {
    config(['devdojo.auth.settings.enable_2fa' => false]);
    $user = User::factory()->create(['password' => bcrypt('policy-password-123'), 'two_factor_confirmed_at' => now()]);

    Volt::test('auth.login')
        ->set('email', $user->email)->set('password', 'policy-password-123')->set('showPasswordField', true)
        ->set('twoFactorEnabled', true)->call('authenticate')->assertHasNoErrors();

    $this->assertAuthenticatedAs($user);
    expect(session('login.id'))->toBeNull();
});

it('still rejects incorrect passwords when the client changes the display flag', function () {
    config(['devdojo.auth.settings.enable_2fa' => true]);
    $user = User::factory()->create(['password' => bcrypt('policy-password-123'), 'two_factor_confirmed_at' => now()]);

    Volt::test('auth.login')
        ->set('email', $user->email)->set('password', 'wrong-password')->set('showPasswordField', true)
        ->set('twoFactorEnabled', false)->call('authenticate')->assertHasErrors('password');

    $this->assertGuest();
    expect(session('login.id'))->toBeNull();
});
