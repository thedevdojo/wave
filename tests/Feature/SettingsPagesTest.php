<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Volt;
use Wave\ActivityLog;
use Wave\ApiKey;

beforeEach(function () {
    $this->user = User::factory()->create(['password' => Hash::make('oldpassword123')]);
    $this->actingAs($this->user);
});

it('goes back to the first page of activity when the filters change', function (string $property, string $value) {
    foreach (range(1, 20) as $i) {
        ActivityLog::create(['user_id' => $this->user->id, 'action' => 'login', 'description' => 'Logged in']);
    }
    ActivityLog::create(['user_id' => $this->user->id, 'action' => 'password_changed', 'description' => 'Password was changed']);

    Volt::test('settings.activity')
        ->call('gotoPage', 2)
        ->set($property, $value)
        ->assertViewHas('activities', fn ($activities) => $activities->currentPage() === 1 && $activities->count() === 1);
})->with([
    'action filter' => ['filterAction', 'password_changed'],
    'search' => ['search', 'Password'],
]);

it('stores API key names as typed', function () {
    Volt::test('settings.api')
        ->set('data.key', 'My Production Key')
        ->call('add')
        ->assertHasNoErrors();

    expect(ApiKey::where('user_id', $this->user->id)->pluck('name')->all())->toBe(['My Production Key']);
});

it('stops users at their plan\'s API key limit', function () {
    config(['limits.defaults.api_keys' => 1]);
    $this->user->createApiKey('first');

    Volt::test('settings.api')
        ->set('data.key', 'second')
        ->call('add');

    expect(ApiKey::where('user_id', $this->user->id)->count())->toBe(1);
});

it('lets users create API keys up to their limit', function () {
    config(['limits.defaults.api_keys' => 2]);
    $this->user->createApiKey('first');

    Volt::test('settings.api')
        ->set('data.key', 'second')
        ->call('add')
        ->assertHasNoErrors();

    expect(ApiKey::where('user_id', $this->user->id)->count())->toBe(2);
});

it('lets a user without a password set one', function () {
    // social logins create users with no password
    $user = User::factory()->create();
    $user->forceFill(['password' => null])->save();
    $this->actingAs($user);

    Volt::test('settings.security')
        ->set('data.password', 'newpassword123')
        ->set('data.password_confirmation', 'newpassword123')
        ->call('save')
        ->assertHasNoErrors();

    expect(Hash::check('newpassword123', $user->fresh()->password))->toBeTrue();
});

it('still asks for the current password when there is one', function () {
    Volt::test('settings.security')
        ->set('data.password', 'newpassword123')
        ->set('data.password_confirmation', 'newpassword123')
        ->call('save')
        ->assertHasErrors(['data.current_password']);

    expect(Hash::check('oldpassword123', $this->user->fresh()->password))->toBeTrue();
});

it('shows the dashboard to a user without a role', function () {
    $this->user->syncRoles([]);

    $this->get('/dashboard')->assertOk();
});

it('names the user\'s role on the dashboard', function () {
    $this->user->syncRoles(['registered']);

    $this->get('/dashboard')->assertOk()->assertSee('has a <strong>registered</strong> role', false);
});
