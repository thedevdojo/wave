<?php

use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->actingAs(User::factory()->admin()->create());
});

test('removing the admin role in the admin panel takes effect right away', function () {
    $user = User::factory()->admin()->create();
    expect($user->isAdmin())->toBeTrue();

    Livewire::test(EditUser::class, ['record' => $user->id])
        ->fillForm(['roles' => [Role::findByName('registered')->id]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->fresh()->isAdmin())->toBeFalse();
});

test('giving the admin role in the admin panel takes effect right away', function () {
    $user = User::factory()->create();
    expect($user->isAdmin())->toBeFalse();

    Livewire::test(EditUser::class, ['record' => $user->id])
        ->fillForm(['roles' => [Role::findByName('admin')->id]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->fresh()->isAdmin())->toBeTrue();
});

test('a deleted user does not leave a cached admin status behind', function () {
    $user = User::factory()->admin()->create();
    expect($user->isAdmin())->toBeTrue();

    $user->delete();

    expect(cache()->has("user_admin_{$user->id}"))->toBeFalse();
});

test('changing roles in code clears the cached admin status', function () {
    $user = User::factory()->admin()->create();
    expect($user->isAdmin())->toBeTrue();

    $user->removeRole('admin');
    expect($user->isAdmin())->toBeFalse();

    $user->assignRole('admin');
    expect($user->isAdmin())->toBeTrue();
});
