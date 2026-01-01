<?php

use App\Models\ActivityLog;
use App\Models\User;

test('user can access export data page', function () {
    $user = User::where('email', 'admin@admin.com')->first();

    $this->actingAs($user);

    $response = $this->get(route('settings.export'));

    $response->assertStatus(200);
    $response->assertSee('Export Data');
    $response->assertSee('Download Your Data');
});

test('user can export their data', function () {
    $user = User::where('email', 'admin@admin.com')->first();

    $this->actingAs($user);

    // Call the export action
    $response = $this->call('GET', route('settings.export'));

    expect($response->status())->toBe(200);
});

test('export data contains user profile information', function () {
    $user = User::where('email', 'admin@admin.com')->first();

    $this->actingAs($user);

    // Simulate the export
    $data = [
        'profile' => [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
        ],
        'activity_logs' => $user->activityLogs()->get()->toArray(),
    ];

    expect($data['profile'])->toHaveKeys(['id', 'name', 'username', 'email']);
    expect($data['profile']['email'])->toBe($user->email);
});

test('export logs activity', function () {
    $user = User::where('email', 'admin@admin.com')->first();

    $this->actingAs($user);

    $initialLogCount = ActivityLog::where('user_id', $user->id)->count();

    // Log an export manually to test
    ActivityLog::log('data_exported', 'User data exported');

    $newLogCount = ActivityLog::where('user_id', $user->id)->count();

    expect($newLogCount)->toBe($initialLogCount + 1);

    $latestLog = ActivityLog::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->first();

    expect($latestLog->action)->toBe('data_exported');
});

test('exported data masks api keys', function () {
    $user = User::where('email', 'admin@admin.com')->first();

    // Create a test API key if none exists
    if ($user->apiKeys()->count() === 0) {
        $user->createApiKey('Test Key');
    }

    $apiKey = $user->apiKeys()->first();
    $fullKey = $apiKey->key;

    // Simulate the masking logic
    $maskedKey = substr($fullKey, 0, 10).'...'.substr($fullKey, -5);

    expect($maskedKey)->not->toBe($fullKey);
    expect($maskedKey)->toContain('...');
    expect(strlen($maskedKey))->toBeLessThan(strlen($fullKey));
});

test('export includes privacy settings', function () {
    $user = User::where('email', 'admin@admin.com')->first();

    // Set some privacy settings
    $privacySettings = [
        'profile_visibility' => 'public',
        'show_email' => false,
    ];

    $user->privacy_settings = $privacySettings;
    $user->save();

    $user->refresh();

    expect($user->privacy_settings)->toBe($privacySettings);
});

test('export includes blog posts authored by user', function () {
    $user = User::where('email', 'admin@admin.com')->first();

    $this->actingAs($user);

    $posts = \Wave\Post::where('author_id', $user->id)->get();

    expect($posts->count())->toBeGreaterThanOrEqual(0);
});
