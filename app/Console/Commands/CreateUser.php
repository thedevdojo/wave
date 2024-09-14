<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class CreateUser extends Command
{
    protected $signature = 'app:create-user';
    protected $description = 'Create a new user with role assignment';

    public function handle()
    {
        $name = $this->ask('Enter the user\'s name');
        $email = $this->ask('Enter the user\'s email');
        $username = $this->ask('Enter the user\'s username');
        $password = $this->secret('Enter the user\'s password');

        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->line($error);
            }
            return 1;
        }

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => Hash::make($password),
            'verified' => 1
        ]);

        // Get roles and let user select
        $roles = Role::all()->pluck('name')->toArray();
        $selectedRole = $this->choice(
            'Select a role for the user',
            $roles,
            0
        );

        $user->syncRoles([]);
        // Assign selected role to the user
        $user->assignRole($selectedRole);

        $this->info("User created successfully with role: {$selectedRole}");
        return 0;
    }
}