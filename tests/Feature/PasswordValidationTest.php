<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Volt;

it('respects minimum password length from config when changing password', function () {
    $minLength = config('wave.auth.min_password_length');

    $user = User::factory()->create([
        'password' => Hash::make('oldpassword123'),
    ]);

    $this->actingAs($user);

    // Test with password shorter than minimum length
    $shortPassword = str_repeat('a', $minLength - 1);

    Volt::test('settings.security')
        ->set('data.current_password', 'oldpassword123')
        ->set('data.password', $shortPassword)
        ->set('data.password_confirmation', $shortPassword)
        ->call('save')
        ->assertHasErrors(['data.password']);
});

it('allows password change with valid length', function () {
    $minLength = config('wave.auth.min_password_length');

    $user = User::factory()->create([
        'password' => Hash::make('oldpassword123'),
    ]);

    $this->actingAs($user);

    // Test with password meeting minimum length
    $validPassword = str_repeat('a', $minLength);

    Volt::test('settings.security')
        ->set('data.current_password', 'oldpassword123')
        ->set('data.password', $validPassword)
        ->set('data.password_confirmation', $validPassword)
        ->call('save')
        ->assertHasNoErrors();

    // Verify password was actually changed
    $user->refresh();
    expect(Hash::check($validPassword, $user->password))->toBeTrue();
});

it('requires current password to be correct', function () {
    $user = User::factory()->create([
        'password' => Hash::make('oldpassword123'),
    ]);

    $this->actingAs($user);

    Volt::test('settings.security')
        ->set('data.current_password', 'wrongpassword')
        ->set('data.password', 'newpassword123')
        ->set('data.password_confirmation', 'newpassword123')
        ->call('save')
        ->assertHasErrors(['data.current_password']);
});

it('requires password confirmation to match', function () {
    $user = User::factory()->create([
        'password' => Hash::make('oldpassword123'),
    ]);

    $this->actingAs($user);

    Volt::test('settings.security')
        ->set('data.current_password', 'oldpassword123')
        ->set('data.password', 'newpassword123')
        ->set('data.password_confirmation', 'differentpassword')
        ->call('save')
        ->assertHasErrors(['data.password_confirmation']);
});
