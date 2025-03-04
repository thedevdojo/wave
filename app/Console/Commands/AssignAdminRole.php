<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class AssignAdminRole extends Command
{
    protected $signature = 'user:make-admin {email : The email of the user}';
    protected $description = 'Assign the admin role to a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        $user->assignRole('admin');
        $this->info("Admin role assigned to {$user->name} ({$user->email})");
        
        return 0;
    }
} 