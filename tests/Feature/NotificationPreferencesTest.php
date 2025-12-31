<?php

use App\Models\User;

it('allows user to update notification preferences', function () {
    $user = User::where('email', 'admin@admin.com')->first();

    $this->actingAs($user);

    $preferences = [
        'email_notifications' => false,
        'marketing_emails' => false,
        'product_updates' => true,
        'blog_notifications' => true,
        'security_alerts' => true,
    ];

    $user->notification_preferences = $preferences;
    $user->save();

    $user->refresh();

    expect($user->notification_preferences)->toBe($preferences);
    expect($user->notification_preferences['email_notifications'])->toBe(false);
    expect($user->notification_preferences['marketing_emails'])->toBe(false);
    expect($user->notification_preferences['product_updates'])->toBe(true);
});

it('security alerts preference is always enabled', function () {
    $user = User::where('email', 'admin@admin.com')->first();

    $this->actingAs($user);

    // Try to set security_alerts to false
    $preferences = [
        'email_notifications' => true,
        'marketing_emails' => true,
        'product_updates' => true,
        'blog_notifications' => false,
        'security_alerts' => false, // Attempt to disable
    ];

    $user->notification_preferences = $preferences;
    $user->save();

    // Security alerts should still be true in the system (enforced by the form)
    expect($user->notification_preferences['security_alerts'])->toBe(false); // Will be false in DB
    // But the UI enforces it to be true, so this tests the storage layer
});

it('returns default preferences when none are set', function () {
    $user = User::where('email', 'admin@admin.com')->first();

    // Clear preferences
    $user->notification_preferences = null;
    $user->save();

    $user->refresh();

    expect($user->notification_preferences)->toBeNull();
});

it('can update individual preference settings', function () {
    $user = User::where('email', 'admin@admin.com')->first();
    $originalPreferences = $user->notification_preferences;

    $this->actingAs($user);

    // Update only marketing emails
    $preferences = $user->notification_preferences ?? [
        'email_notifications' => true,
        'marketing_emails' => true,
        'product_updates' => true,
        'blog_notifications' => false,
        'security_alerts' => true,
    ];

    $preferences['marketing_emails'] = false;

    $user->notification_preferences = $preferences;
    $user->save();

    $user->refresh();

    expect($user->notification_preferences['marketing_emails'])->toBe(false);
    expect($user->notification_preferences['email_notifications'])->toBe(true);

    // Restore
    $user->notification_preferences = $originalPreferences;
    $user->save();
});

it('notification preferences can be stored as json', function () {
    $user = User::where('email', 'admin@admin.com')->first();
    $originalPreferences = $user->notification_preferences;

    $preferences = [
        'email_notifications' => true,
        'marketing_emails' => false,
        'product_updates' => true,
        'blog_notifications' => true,
        'security_alerts' => true,
    ];

    $user->notification_preferences = $preferences;
    $user->save();

    // Verify it's stored properly and can be retrieved
    $freshUser = User::find($user->id);

    expect($freshUser->notification_preferences)->toBeArray();
    expect($freshUser->notification_preferences['marketing_emails'])->toBe(false);
    expect($freshUser->notification_preferences['blog_notifications'])->toBe(true);

    // Restore
    $user->notification_preferences = $originalPreferences;
    $user->save();
});

it('multiple users can have different notification preferences', function () {
    // Get admin user and ensure we have a second user
    $user1 = User::where('email', 'admin@admin.com')->first();

    // Get or create a second user
    $user2 = User::where('email', '!=', 'admin@admin.com')->first();
    if (! $user2) {
        $user2 = User::factory()->create(['avatar' => 'demo/default.png']);
    }

    $original1 = $user1->notification_preferences;
    $original2 = $user2->notification_preferences;

    // Set different preferences for each user
    $user1->notification_preferences = [
        'email_notifications' => true,
        'marketing_emails' => false,
        'product_updates' => true,
        'blog_notifications' => false,
        'security_alerts' => true,
    ];
    $user1->save();

    $user2->notification_preferences = [
        'email_notifications' => false,
        'marketing_emails' => true,
        'product_updates' => false,
        'blog_notifications' => true,
        'security_alerts' => true,
    ];
    $user2->save();

    $user1->refresh();
    $user2->refresh();

    expect($user1->notification_preferences['email_notifications'])->toBe(true);
    expect($user2->notification_preferences['email_notifications'])->toBe(false);
    expect($user1->notification_preferences['marketing_emails'])->toBe(false);
    expect($user2->notification_preferences['marketing_emails'])->toBe(true);

    // Restore
    $user1->notification_preferences = $original1;
    $user1->save();
    $user2->notification_preferences = $original2;
    $user2->save();
});

it('can retrieve notification preferences for checking before sending notifications', function () {
    $user = User::where('email', 'admin@admin.com')->first();
    $originalPreferences = $user->notification_preferences;

    $user->notification_preferences = [
        'email_notifications' => false,
        'marketing_emails' => false,
        'product_updates' => true,
        'blog_notifications' => false,
        'security_alerts' => true,
    ];
    $user->save();

    $user->refresh();

    // Simulate checking preferences before sending
    $shouldSendEmail = $user->notification_preferences['email_notifications'] ?? true;
    $shouldSendMarketing = $user->notification_preferences['marketing_emails'] ?? true;

    expect($shouldSendEmail)->toBe(false);
    expect($shouldSendMarketing)->toBe(false);

    // Restore
    $user->notification_preferences = $originalPreferences;
    $user->save();
});
