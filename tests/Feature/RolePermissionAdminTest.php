<?php

use App\Filament\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permissions\Pages\EditPermission;
use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->actingAs(User::factory()->admin()->create());

    $this->editPosts = Permission::create(['name' => 'edit posts', 'guard_name' => 'web']);
    $this->deletePosts = Permission::create(['name' => 'delete posts', 'guard_name' => 'web']);

    $this->member = User::factory()->create();
    $this->member->syncRoles(['premium']);
});

it('assigns permissions when creating a role', function () {
    Livewire::test(CreateRole::class)
        ->fillForm(['name' => 'editor', 'description' => 'Edits content', 'permissions' => [$this->editPosts->id]])
        ->call('create')
        ->assertHasNoFormErrors();

    $role = Role::findByName('editor');
    expect($role->guard_name)->toBe('web')
        ->and($role->permissions->pluck('name')->all())->toBe(['edit posts']);
});

it('updates a role\'s permissions and users get them straight away', function () {
    $premium = Role::findByName('premium');

    // warm Spatie's permission cache before the change
    expect($this->member->fresh()->can('edit posts'))->toBeFalse();

    Livewire::test(EditRole::class, ['record' => $premium->id])
        ->fillForm(['permissions' => [$this->editPosts->id, $this->deletePosts->id]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($premium->fresh()->permissions->pluck('name')->sort()->values()->all())->toBe(['delete posts', 'edit posts'])
        ->and($this->member->fresh()->can('edit posts'))->toBeTrue();

    Livewire::test(EditRole::class, ['record' => $premium->id])
        ->fillForm(['permissions' => []])
        ->call('save');

    expect($this->member->fresh()->can('edit posts'))->toBeFalse();
});

it('assigns roles from the permission form', function () {
    Livewire::test(CreatePermission::class)
        ->fillForm(['name' => 'view reports', 'roles' => [Role::findByName('premium')->id]])
        ->call('create')
        ->assertHasNoFormErrors();

    expect($this->member->fresh()->can('view reports'))->toBeTrue();

    $permission = Permission::findByName('view reports');
    Livewire::test(EditPermission::class, ['record' => $permission->id])
        ->fillForm(['roles' => []])
        ->call('save');

    expect($this->member->fresh()->can('view reports'))->toBeFalse();
});

it('shows how many permissions each role has', function () {
    Role::findByName('premium')->givePermissionTo($this->editPosts);

    Livewire::test(ListRoles::class)
        ->assertCanSeeTableRecords(Role::all())
        ->assertTableColumnExists('permissions_count');
});
