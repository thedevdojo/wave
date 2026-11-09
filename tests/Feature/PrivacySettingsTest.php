<?php

use App\Models\User;

test('user can update privacy settings', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $settings = [
        'profile_visibility' => 'private',
        'show_email' => false,
        'show_activity' => false,
        'allow_search_engines' => false,
        'show_online_status' => false,
        'allow_data_collection' => true,
        'allow_personalization' => true,
    ];

    $user->privacy_settings = $settings;
    $user->save();

    $user->refresh();

    expect($user->privacy_settings)->toBe($settings);
    expect($user->privacy_settings['profile_visibility'])->toBe('private');
    expect($user->privacy_settings['show_email'])->toBe(false);
});

test('privacy settings default to null when not set', function () {
    $user = User::factory()->create();

    // Clear privacy settings
    $user->privacy_settings = null;
    $user->save();

    $user->refresh();

    expect($user->privacy_settings)->toBeNull();
});

test('can update individual privacy settings', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    // Update only profile visibility
    $settings = $user->privacy_settings ?? config('privacy.defaults');
    $settings['profile_visibility'] = 'contacts';

    $user->privacy_settings = $settings;
    $user->save();

    $user->refresh();

    expect($user->privacy_settings['profile_visibility'])->toBe('contacts');
});

test('privacy settings can be stored as json', function () {
    $user = User::factory()->create();

    $settings = [
        'profile_visibility' => 'public',
        'show_email' => true,
        'show_activity' => true,
        'allow_search_engines' => true,
        'show_online_status' => true,
        'allow_data_collection' => false,
        'allow_personalization' => false,
    ];

    $user->privacy_settings = $settings;
    $user->save();

    // Fetch fresh from database
    $freshUser = User::find($user->id);

    expect($freshUser->privacy_settings)->toBeArray();
    expect($freshUser->privacy_settings)->toBe($settings);
});

test('multiple users can have different privacy settings', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $user1Settings = [
        'profile_visibility' => 'public',
        'show_email' => true,
        'allow_search_engines' => true,
    ];

    $user2Settings = [
        'profile_visibility' => 'private',
        'show_email' => false,
        'allow_search_engines' => false,
    ];

    $user1->privacy_settings = $user1Settings;
    $user1->save();

    $user2->privacy_settings = $user2Settings;
    $user2->save();

    $user1->refresh();
    $user2->refresh();

    expect($user1->privacy_settings['profile_visibility'])->toBe('public');
    expect($user2->privacy_settings['profile_visibility'])->toBe('private');
    expect($user1->privacy_settings['show_email'])->toBe(true);
    expect($user2->privacy_settings['show_email'])->toBe(false);
});

test('privacy settings page is accessible', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('settings.privacy'));

    $response->assertStatus(200);
});

test('privacy settings can use default values from config', function () {
    $defaults = config('privacy.defaults');

    expect($defaults)->toBeArray();
    expect($defaults)->toHaveKey('profile_visibility');
    expect($defaults)->toHaveKey('show_email');
    expect($defaults)->toHaveKey('allow_search_engines');
});

test('private profile returns 404 for non-owners', function () {
    $user = User::factory()->create([
        'privacy_settings' => ['profile_visibility' => 'private'],
    ]);

    // Guest user trying to view private profile
    $response = $this->get('/profile/'.$user->username);
    $response->assertStatus(404);

    // Different authenticated user trying to view
    $otherUser = User::factory()->create();
    $this->actingAs($otherUser);
    $response = $this->get('/profile/'.$user->username);
    $response->assertStatus(404);
});

test('private profile is accessible to owner', function () {
    $user = User::factory()->create([
        'privacy_settings' => ['profile_visibility' => 'private'],
    ]);

    $this->actingAs($user);
    $response = $this->get('/profile/'.$user->username);
    $response->assertStatus(200);
});

test('public profile is accessible to everyone', function () {
    $user = User::factory()->create([
        'privacy_settings' => ['profile_visibility' => 'public'],
    ]);

    // Guest can view
    $response = $this->get('/profile/'.$user->username);
    $response->assertStatus(200);

    // Authenticated user can view
    $otherUser = User::factory()->create();
    $this->actingAs($otherUser);
    $response = $this->get('/profile/'.$user->username);
    $response->assertStatus(200);
});

test('email is shown on profile when show_email is enabled', function () {
    $user = User::factory()->create([
        'privacy_settings' => ['show_email' => true, 'profile_visibility' => 'public'],
    ]);

    $response = $this->get('/profile/'.$user->username);
    $response->assertStatus(200);
    $response->assertSee($user->email);
});

test('email is hidden on profile when show_email is disabled', function () {
    $user = User::factory()->create([
        'privacy_settings' => ['show_email' => false, 'profile_visibility' => 'public'],
    ]);

    $response = $this->get('/profile/'.$user->username);
    $response->assertStatus(200);
    $response->assertDontSee($user->email);
});

test('profile tells search engines not to index it when indexing is turned off', function () {
    $user = User::factory()->create([
        'privacy_settings' => ['profile_visibility' => 'public', 'show_email' => false, 'allow_search_engines' => false],
    ]);

    $response = $this->get('/profile/'.$user->username);

    $response->assertOk();
    $response->assertSee('<meta name="robots" content="noindex,nofollow">', false);
    $response->assertDontSee('content="index,follow"', false);
    $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');

    // Logged-in viewers get the app layout, which must carry the tag too.
    $this->actingAs(User::factory()->create())
        ->get('/profile/'.$user->username)
        ->assertSee('<meta name="robots" content="noindex,nofollow">', false);
});

test('profile can be indexed when indexing is allowed or not set', function (?array $settings) {
    $user = User::factory()->create(['privacy_settings' => $settings]);

    $response = $this->get('/profile/'.$user->username);

    $response->assertOk();
    $response->assertSee('<meta name="robots" content="index,follow">', false);
    $response->assertHeaderMissing('X-Robots-Tag');
})->with([
    'allowed' => [['profile_visibility' => 'public', 'allow_search_engines' => true]],
    'missing key' => [['profile_visibility' => 'public']],
    'no settings' => [null],
]);

test('profile settings without a visibility key are treated as public', function () {
    $user = User::factory()->create(['privacy_settings' => ['show_email' => true]]);

    $this->get('/profile/'.$user->username)->assertOk()->assertSee($user->email);
});
