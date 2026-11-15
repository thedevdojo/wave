<?php

use App\Models\User;
use Wave\ActivityLog;

test('cleanup command deletes old activity logs', function () {
    // Disable activity logging for test setup
    config(['activity.enabled' => false]);

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

test('cleanup command refuses a retention period that is not a positive number of days', function ($days) {
    config(['activity.enabled' => false]);
    $user = User::factory()->create();
    config(['activity.enabled' => true]);

    ActivityLog::create([
        'user_id' => $user->id,
        'action' => 'test',
        'description' => 'Recent log',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test',
        'created_at' => now()->subMinute(),
    ]);

    $this->artisan('activity:clean', ['--days' => $days, '--no-interaction' => true])
        ->assertFailed();

    expect(ActivityLog::count())->toBe(1);
})->with(['0', '-5', 'abc']);

test('cleanup command keeps logs when the retention config is blank', function () {
    config(['activity.enabled' => false]);
    $user = User::factory()->create();
    config(['activity.enabled' => true, 'activity.retention_days' => '']);

    ActivityLog::create([
        'user_id' => $user->id,
        'action' => 'test',
        'description' => 'Recent log',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test',
        'created_at' => now()->subMinute(),
    ]);

    $this->artisan('activity:clean', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(ActivityLog::count())->toBe(1);
});

test('a recent login row does not suppress a new authentication event', function () {
    $user = User::factory()->create();
    ActivityLog::create([
        'user_id' => $user->id, 'action' => 'login', 'description' => 'Previous login',
        'created_at' => now()->subMinutes(3),
    ]);
    auth()->login($user);
    expect(ActivityLog::where('user_id', $user->id)->where('action', 'login')->count())->toBe(2);
});
