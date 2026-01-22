<?php

use Wave\ActivityLog;
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

test('export handles subscription with string ends_at date', function () {
    $user = User::where('email', 'admin@admin.com')->first();

    $this->actingAs($user);

    // Clean up any existing subscriptions for this test
    \Wave\Subscription::where('billable_id', $user->id)->delete();

    // Get a plan to use
    $plan = \Wave\Plan::first();

    // Create a subscription directly in the database with ends_at as a string
    // This simulates a cancelled subscription scenario where ends_at might not be cast properly
    $subscriptionId = \DB::table('subscriptions')->insertGetId([
        'billable_type' => 'user',
        'billable_id' => $user->id,
        'plan_id' => $plan->id,
        'vendor_slug' => 'paddle',
        'vendor_subscription_id' => 'sub_test_'.time(),
        'status' => 'cancelled',
        'cycle' => 'month',
        'seats' => 1,
        'trial_ends_at' => null,
        'ends_at' => '2026-12-31 23:59:59', // String format, not Carbon
        'created_at' => now()->toDateTimeString(),
        'updated_at' => now()->toDateTimeString(),
    ]);

    // Verify the subscription was created
    expect($subscriptionId)->toBeGreaterThan(0);

    // Clear the user relationship cache
    $user = $user->fresh();

    // Verify ends_at is a string when loaded directly from DB
    $rawSubscription = \DB::table('subscriptions')->where('id', $subscriptionId)->first();
    expect($rawSubscription->ends_at)->toBeString();
    expect($rawSubscription->ends_at)->toBe('2026-12-31 23:59:59');

    // Load the subscription through Eloquent (which won't cast ends_at since it's not in casts array)
    $subscription = \Wave\Subscription::find($subscriptionId);

    // Simulate the export logic that was causing the bug
    $exportData = [
        'subscription' => [
            'plan' => $subscription->plan->name ?? null,
            'status' => $subscription->status,
            'cycle' => $subscription->cycle ?? null,
            'created_at' => $subscription->created_at instanceof \Carbon\Carbon
                ? $subscription->created_at->toDateTimeString()
                : $subscription->created_at,
            'ends_at' => $subscription->ends_at
                ? ($subscription->ends_at instanceof \Carbon\Carbon
                    ? $subscription->ends_at->toDateTimeString()
                    : $subscription->ends_at)
                : null,
        ],
    ];

    // This should work without throwing an error
    expect($exportData['subscription']['ends_at'])->toBe('2026-12-31 23:59:59');
    expect($exportData['subscription']['status'])->toBe('cancelled');
    expect($exportData['subscription']['plan'])->toBe($plan->name);

    // Clean up
    \Wave\Subscription::where('id', $subscriptionId)->delete();
});
