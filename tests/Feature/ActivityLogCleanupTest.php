<?php

use App\Models\ActivityLog;
use App\Models\User;

test('cleanup command deletes old activity logs', function () {
    // Disable activity logging for test setup
    config(['activity.enabled' => false]);

    // Clear existing logs
    ActivityLog::query()->delete();

    $user = User::factory()->create();
    $this->actingAs($user);

    // Re-enable for manual log creation
    config(['activity.enabled' => true]);

    // Create an old log manually
    ActivityLog::create([
        'user_id' => $user->id,
        'action' => 'test',
        'description' => 'Old log',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test',
        'created_at' => now()->subDays(100),
    ]);

    // Create a recent log manually
    ActivityLog::create([
        'user_id' => $user->id,
        'action' => 'test',
        'description' => 'Recent log',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test',
        'created_at' => now(),
    ]);

    expect(ActivityLog::count())->toBe(2);

    // Run cleanup with 90 day retention
    $this->artisan('activity:clean', ['--days' => 90, '--no-interaction' => true])
        ->assertSuccessful();

    // Old log should be deleted, recent one kept
    expect(ActivityLog::count())->toBe(1);
    expect(ActivityLog::first()->description)->toBe('Recent log');
});

test('cleanup command respects retention config', function () {
    config(['activity.enabled' => false]);
    ActivityLog::query()->delete();
    config(['activity.retention_days' => 30]);

    $user = User::factory()->create();
    $this->actingAs($user);

    config(['activity.enabled' => true]);

    ActivityLog::create([
        'user_id' => $user->id,
        'action' => 'test',
        'description' => 'Old log',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test',
        'created_at' => now()->subDays(40),
    ]);

    $this->artisan('activity:clean', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(ActivityLog::count())->toBe(0);
});

test('duplicate login events within 5 minutes are prevented', function () {
    config(['activity.enabled' => false]);
    ActivityLog::query()->delete();

    $user = User::factory()->create();
    config(['activity.enabled' => true]);

    // Manually create first login log
    ActivityLog::create([
        'user_id' => $user->id,
        'action' => 'login',
        'description' => 'User logged in',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test',
        'created_at' => now()->subMinutes(3),
    ]);

    expect(ActivityLog::count())->toBe(1);

    // Try to log in again (should be prevented)
    $this->actingAs($user)->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    // Should still be 1 (duplicate prevented)
    expect(ActivityLog::where('action', 'login')->count())->toBe(1);
});
