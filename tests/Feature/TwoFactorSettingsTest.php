<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

afterEach(function () {
    $this->user->forceDelete();
});

it('links to two factor setup from the security settings when 2FA is enabled', function () {
    config(['devdojo.auth.settings.enable_2fa' => true]);

    $this->actingAs($this->user)
        ->get('/settings/security')
        ->assertOk()
        ->assertSee('Two-Factor Authentication')
        ->assertSee(route('user.two-factor-authentication'))
        ->assertSee('Set up two-factor authentication');
});

it('shows when two factor authentication is already on', function () {
    config(['devdojo.auth.settings.enable_2fa' => true]);
    $this->user->forceFill(['two_factor_confirmed_at' => now()])->save();

    $this->actingAs($this->user)
        ->get('/settings/security')
        ->assertOk()
        ->assertSee(route('user.two-factor-authentication'))
        ->assertSee('Manage two-factor authentication');
});

it('hides two factor setup when 2FA is disabled', function () {
    config(['devdojo.auth.settings.enable_2fa' => false]);

    $this->actingAs($this->user)
        ->get('/settings/security')
        ->assertOk()
        ->assertDontSee('Two-Factor Authentication')
        ->assertDontSee(route('user.two-factor-authentication'));
});

it('opens the two factor setup page', function () {
    config(['devdojo.auth.settings.enable_2fa' => true]);

    $this->actingAs($this->user)
        ->get(route('user.two-factor-authentication'))
        ->assertOk()
        ->assertSee('Two factor authentication disabled.');
});
