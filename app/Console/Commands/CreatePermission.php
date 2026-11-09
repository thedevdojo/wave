<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreatePermission extends Command
{
    protected $signature = 'app:create-permission';

    protected $description = 'Create a new permission and optionally assign it to roles';

    public function handle(): int
    {
        $name = $this->ask('Enter the name of the new permission');

        $validator = Validator::make(['name' => $name], [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->line($error);
            }

            return 1;
        }

        $permission = Permission::create(['name' => $name]);

        $roles = Role::pluck('name')->all();

        if ($roles && $this->confirm('Do you want to assign this permission to roles?', true)) {
            $selectedRoles = $this->choice(
                'Select the roles that should get this permission (multiple selection allowed)',
                $roles,
                null,
                null,
                true
            );

            $permission->syncRoles($selectedRoles);

            $this->info('Permission assigned to: '.implode(', ', $selectedRoles));
        }

        $this->info("Permission '{$name}' created successfully.");

        return 0;
    }
}
