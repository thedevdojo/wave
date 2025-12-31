<?php

use App\Models\User;

it('allows user to add social media links', function () {
    $user = User::where('email', 'admin@admin.com')->first();
    $originalLinks = $user->social_links;

    $this->actingAs($user);

    $links = [
        'twitter' => 'https://twitter.com/testuser',
        'linkedin' => 'https://linkedin.com/in/testuser',
        'github' => 'https://github.com/testuser',
        'website' => 'https://testuser.com',
    ];

    $user->social_links = $links;
    $user->save();

    $user->refresh();

    expect($user->social_links)->toBe($links);
    expect($user->social_links['twitter'])->toBe('https://twitter.com/testuser');
    expect($user->social_links['github'])->toBe('https://github.com/testuser');

    // Restore
    $user->social_links = $originalLinks;
    $user->save();
});

it('returns null when no social links are set', function () {
    $user = User::where('email', 'admin@admin.com')->first();
    $originalLinks = $user->social_links;

    // Clear links
    $user->social_links = null;
    $user->save();

    $user->refresh();

    expect($user->social_links)->toBeNull();

    // Restore
    $user->social_links = $originalLinks;
    $user->save();
});

it('can update individual social links', function () {
    $user = User::where('email', 'admin@admin.com')->first();
    $originalLinks = $user->social_links;

    $this->actingAs($user);

    // Set initial links
    $links = [
        'twitter' => 'https://twitter.com/oldhandle',
        'github' => 'https://github.com/oldusername',
    ];

    $user->social_links = $links;
    $user->save();

    // Update only Twitter
    $links['twitter'] = 'https://twitter.com/newhandle';
    $user->social_links = $links;
    $user->save();

    $user->refresh();

    expect($user->social_links['twitter'])->toBe('https://twitter.com/newhandle');
    expect($user->social_links['github'])->toBe('https://github.com/oldusername');

    // Restore
    $user->social_links = $originalLinks;
    $user->save();
});

it('social links can be stored as json', function () {
    $user = User::where('email', 'admin@admin.com')->first();
    $originalLinks = $user->social_links;

    $links = [
        'twitter' => 'https://twitter.com/test',
        'linkedin' => 'https://linkedin.com/in/test',
        'github' => 'https://github.com/test',
        'website' => 'https://example.com',
        'youtube' => 'https://youtube.com/@test',
        'instagram' => 'https://instagram.com/test',
    ];

    $user->social_links = $links;
    $user->save();

    // Verify it's stored properly and can be retrieved
    $freshUser = User::find($user->id);

    expect($freshUser->social_links)->toBeArray();
    expect($freshUser->social_links)->toHaveCount(6);
    expect($freshUser->social_links['youtube'])->toBe('https://youtube.com/@test');

    // Restore
    $user->social_links = $originalLinks;
    $user->save();
});

it('can remove social links by setting to null', function () {
    $user = User::where('email', 'admin@admin.com')->first();
    $originalLinks = $user->social_links;

    // Set some links
    $user->social_links = [
        'twitter' => 'https://twitter.com/test',
        'github' => 'https://github.com/test',
    ];
    $user->save();

    // Remove all links
    $user->social_links = null;
    $user->save();

    $user->refresh();

    expect($user->social_links)->toBeNull();

    // Restore
    $user->social_links = $originalLinks;
    $user->save();
});

it('multiple users can have different social links', function () {
    // Get admin user and ensure we have a second user
    $user1 = User::where('email', 'admin@admin.com')->first();

    // Get or create a second user
    $user2 = User::where('email', '!=', 'admin@admin.com')->first();
    if (! $user2) {
        $user2 = User::factory()->create(['avatar' => 'demo/default.png']);
    }

    $original1 = $user1->social_links;
    $original2 = $user2->social_links;

    // Set different links for each user
    $user1->social_links = [
        'twitter' => 'https://twitter.com/user1',
        'github' => 'https://github.com/user1',
    ];
    $user1->save();

    $user2->social_links = [
        'linkedin' => 'https://linkedin.com/in/user2',
        'website' => 'https://user2.com',
    ];
    $user2->save();

    $user1->refresh();
    $user2->refresh();

    expect($user1->social_links['twitter'])->toBe('https://twitter.com/user1');
    expect($user2->social_links['linkedin'])->toBe('https://linkedin.com/in/user2');
    expect($user1->social_links)->not->toHaveKey('linkedin');
    expect($user2->social_links)->not->toHaveKey('twitter');

    // Restore
    $user1->social_links = $original1;
    $user1->save();
    $user2->social_links = $original2;
    $user2->save();
});

it('can check if user has any social links', function () {
    $user = User::where('email', 'admin@admin.com')->first();
    $originalLinks = $user->social_links;

    // User with links
    $user->social_links = ['twitter' => 'https://twitter.com/test'];
    $user->save();
    $user->refresh();

    $hasLinks = ! empty($user->social_links);
    expect($hasLinks)->toBeTrue();

    // User without links
    $user->social_links = null;
    $user->save();
    $user->refresh();

    $hasLinks = ! empty($user->social_links);
    expect($hasLinks)->toBeFalse();

    // Restore
    $user->social_links = $originalLinks;
    $user->save();
});
