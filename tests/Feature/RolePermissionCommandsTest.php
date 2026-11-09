<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

it('assigns the selected permissions when creating a role', function () {
    Permission::create(['name' => 'edit posts']);
    Permission::create(['name' => 'delete posts']);

    $this->artisan('app:create-role')
        ->expectsQuestion('Enter the name of the new role', 'writer')
        ->expectsQuestion('Enter a description for the new role (optional)', 'Writes posts')
        ->expectsConfirmation('Do you want to assign permissions to this role?', 'yes')
        ->expectsChoice('Select permissions to assign to the role (multiple selection allowed)', ['edit posts'], ['edit posts', 'delete posts'])
        ->assertExitCode(0);

    expect(Role::findByName('writer')->permissions->pluck('name')->all())->toBe(['edit posts']);
});

it('creates a permission and assigns it to roles', function () {
    $user = User::factory()->create();
    $user->syncRoles(['premium']);

    $this->artisan('app:create-permission')
        ->expectsQuestion('Enter the name of the new permission', 'view reports')
        ->expectsConfirmation('Do you want to assign this permission to roles?', 'yes')
        ->expectsChoice('Select the roles that should get this permission (multiple selection allowed)', ['premium', 'pro'], Role::pluck('name')->all())
        ->expectsOutputToContain("Permission 'view reports' created successfully.")
        ->assertExitCode(0);

    expect(Permission::findByName('view reports')->roles->pluck('name')->sort()->values()->all())->toBe(['premium', 'pro'])
        ->and($user->fresh()->can('view reports'))->toBeTrue();
});

it('creates a permission without roles', function () {
    $this->artisan('app:create-permission')
        ->expectsQuestion('Enter the name of the new permission', 'export data')
        ->expectsConfirmation('Do you want to assign this permission to roles?', 'no')
        ->assertExitCode(0);

    expect(Permission::findByName('export data')->roles)->toBeEmpty();
});

it('rejects a duplicate permission name', function () {
    Permission::create(['name' => 'edit posts']);

    $this->artisan('app:create-permission')
        ->expectsQuestion('Enter the name of the new permission', 'edit posts')
        ->assertExitCode(1);
});
