<?php

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Widgets\DashboardWidget;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

it('saves email_verified_at from the user form', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    Livewire::test(EditUser::class, ['record' => $user->id])
        ->fillForm(['email_verified_at' => '2026-01-02 03:04:05'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->fresh()->email_verified_at?->toDateTimeString())->toBe('2026-01-02 03:04:05');
});

it('keeps email_verified_at when a user is created with it', function () {
    // devdojo/auth's social login creates users this way
    $user = User::create([
        'name' => 'Social User',
        'email' => 'social@example.com',
        'email_verified_at' => now(),
    ]);

    expect($user->fresh()->email_verified_at)->not->toBeNull();
});

it('shows a validation error for an email or username that is already taken', function (string $field) {
    $taken = User::factory()->create();
    $user = User::factory()->create();

    Livewire::test(EditUser::class, ['record' => $user->id])
        ->fillForm([$field => $taken->{$field}])
        ->call('save')
        ->assertHasFormErrors([$field => 'unique']);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'New User',
            'username' => 'new-user',
            'email' => 'new@example.com',
            'password' => 'password',
            'roles' => [1],
            $field => $taken->{$field},
        ])
        ->call('create')
        ->assertHasFormErrors([$field => 'unique']);
})->with(['email', 'username']);

it('lets a user keep their own email and username', function () {
    $user = User::factory()->create();

    Livewire::test(EditUser::class, ['record' => $user->id])
        ->fillForm(['name' => 'Renamed'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->fresh()->name)->toBe('Renamed');
});

it('only offers impersonation for users that can be impersonated', function () {
    $member = User::factory()->create();
    $otherAdmin = User::factory()->admin()->create();

    Livewire::test(ListUsers::class)
        ->assertTableActionVisible('Impersonate', $member)
        ->assertTableActionHidden('Impersonate', $otherAdmin)
        ->assertTableActionHidden('Impersonate', $this->admin);
});

it('does not count deleted users on the admin dashboard', function () {
    User::factory()->count(2)->create()->each->delete();
    User::factory()->create();

    Livewire::test(DashboardWidget::class)
        ->assertSeeHtml('>'.User::count().'</div>')
        ->assertDontSeeHtml('>'.User::withTrashed()->count().'</div>');
});

it('casts the user date columns', function () {
    $user = User::factory()->create(['email_verified_at' => now(), 'trial_ends_at' => now()->addDays(3)]);

    expect($user->fresh()->email_verified_at)->toBeInstanceOf(DateTimeInterface::class)
        ->and($user->fresh()->trial_ends_at)->toBeInstanceOf(DateTimeInterface::class);
});

it('only treats a user as on trial until the trial ends', function () {
    expect(User::factory()->create(['trial_ends_at' => now()->addDays(3)])->onTrial())->toBeTrue()
        ->and(User::factory()->create(['trial_ends_at' => now()->subDay()])->onTrial())->toBeFalse()
        ->and(User::factory()->create(['trial_ends_at' => null])->onTrial())->toBeFalse();
});
