<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRole extends Command
{
    protected $signature = 'app:create-role';
    protected $description = 'Create a new role with optional permissions';

    public function handle()
    {
        $name = $this->ask('Enter the name of the new role');

        // Validate role name
        $validator = Validator::make(['name' => $name], [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->line($error);
            }
            return 1;
        }

        $description = $this->ask('Enter a description for the new role (optional)') ?? null;

        // Create the role
        $role = Role::create([
            'name' => $name,
            'description' => $description
        ]);

        // Ask if user wants to assign permissions
        if ($this->confirm('Do you want to assign permissions to this role?', true)) {
            $this->assignPermissions($role);
        }

        $this->info("Role '{$name}' created successfully.");
        return 0;
    }

    protected function assignPermissions(Role $role)
    {
        $allPermissions = Permission::all();

        if ($allPermissions->isEmpty()) {
            $this->warn('No permissions found in the database.');
            return;
        }

        $permissionChoices = $allPermissions->pluck('name', 'id')->toArray();

        $selectedPermissionIds = $this->choice(
            'Select permissions to assign to the role (multiple selection allowed)',
            $permissionChoices,
            null,
            null,
            true
        );

        $selectedPermissions = $allPermissions->whereIn('id', $selectedPermissionIds);


        $role->syncPermissions($selectedPermissions);

        $this->info('Permissions assigned successfully.');
    }
}