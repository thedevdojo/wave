<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->error('No users found. Please create a user first.');

            return;
        }

        $actions = [
            'login' => 'User logged in',
            'logout' => 'User logged out',
            'profile_updated' => 'Profile information updated',
            'password_changed' => 'Password was changed',
            'email_updated' => 'Email address updated',
            'settings_updated' => 'Account settings updated',
            'api_key_created' => 'New API key created',
            'api_key_deleted' => 'API key deleted',
            '2fa_enabled' => 'Two-factor authentication enabled',
            '2fa_disabled' => 'Two-factor authentication disabled',
        ];

        $ips = ['127.0.0.1', '192.168.1.1', '10.0.0.1', '172.16.0.1'];
        $userAgents = [
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X)',
            'Mozilla/5.0 (Linux; Android 11; Pixel 5)',
        ];

        for ($i = 0; $i < 100; $i++) {
            $action = array_rand($actions);
            $daysAgo = rand(0, 89);

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'description' => $actions[$action],
                'ip_address' => $ips[array_rand($ips)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'created_at' => now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
            ]);
        }

        $this->command->info("Created 100 activity log events for user: {$user->name}");
        $this->command->info('Total activity logs: '.ActivityLog::count());
    }
}
