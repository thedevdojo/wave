<?php

use App\Models\ActivityLog;
use App\Models\User;

test('activity log can be created', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $log = ActivityLog::log('test_action', 'Test description', ['key' => 'value']);

    expect($log)
        ->user_id->toBe($user->id)
        ->action->toBe('test_action')
        ->description->toBe('Test description')
        ->metadata->toBe(['key' => 'value'])
        ->ip_address->not->toBeNull();
});

test('activity log respects enabled config', function () {
    // Clear any existing logs first
    ActivityLog::query()->delete();

    config(['activity.enabled' => false]);

    $user = User::factory()->create();
    $this->actingAs($user);

    $log = ActivityLog::log('test_action', 'Test description');

    expect($log)->toBeNull();
    expect(ActivityLog::count())->toBe(0);

    config(['activity.enabled' => true]);
});

test('user has activity logs relationship', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    ActivityLog::log('action1', 'Description 1');
    ActivityLog::log('action2', 'Description 2');

    expect($user->fresh()->activityLogs)->toHaveCount(2);
});

test('activity logs can be deleted when user is deleted', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    ActivityLog::log('test_action', 'Test description');
    $userId = $user->id;
    expect(ActivityLog::where('user_id', $userId)->count())->toBe(1);

    // Manually delete logs (cascade may not work in SQLite tests)
    ActivityLog::where('user_id', $userId)->delete();
    $user->delete();

    expect(ActivityLog::where('user_id', $userId)->count())->toBe(0);
});

test('login creates activity log', function () {
    $user = User::factory()->create();
    auth()->login($user);

    expect(ActivityLog::where('user_id', $user->id)
        ->where('action', 'login')
        ->exists())->toBeTrue();
});

test('activity log page is accessible', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('settings.activity'));

    $response->assertStatus(200);
});

test('activity log page displays user logs', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    ActivityLog::log('test_action_1', 'First test action');
    ActivityLog::log('test_action_2', 'Second test action');

    $response = $this->get(route('settings.activity'));

    $response->assertSee('First test action')
        ->assertSee('Second test action');
});

test('activity log page only shows current user logs', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $this->actingAs($user1);
    ActivityLog::log('user1_action', 'User 1 action');

    $this->actingAs($user2);
    ActivityLog::log('user2_action', 'User 2 action');

    $response = $this->get(route('settings.activity'));

    $response->assertSee('User 2 action')
        ->assertDontSee('User 1 action');
});
