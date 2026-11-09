<?php

use App\Models\User;
use Livewire\Volt\Volt;

it('allows user to add social media links', function () {
    $user = User::factory()->create();

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
});

it('returns null when no social links are set', function () {
    $user = User::factory()->create();

    // Clear links
    $user->social_links = null;
    $user->save();

    $user->refresh();

    expect($user->social_links)->toBeNull();
});

it('can update individual social links', function () {
    $user = User::factory()->create();

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
});

it('social links can be stored as json', function () {
    $user = User::factory()->create();

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
});

it('can remove social links by setting to null', function () {
    $user = User::factory()->create();

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
});

it('multiple users can have different social links', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

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
});

it('can check if user has any social links', function () {
    $user = User::factory()->create();

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
});

it('rejects social links that are not http or https', function (string $url) {
    $user = User::factory()->create();
    $this->actingAs($user);

    Volt::test('settings.social')
        ->set('data.website', $url)
        ->call('save')
        ->assertHasErrors(['data.website']);

    expect($user->fresh()->social_links)->toBeNull();
})->with([
    'data://text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==',
    'smb://attacker.example/share',
    'view-source://example.com',
    'ftp://example.com/file',
]);

it('saves http and https social links', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Volt::test('settings.social')
        ->set('data.website', 'http://example.com')
        ->set('data.github', 'https://github.com/test')
        ->call('save')
        ->assertHasNoErrors();

    expect($user->fresh()->social_links)->toBe([
        'github' => 'https://github.com/test',
        'website' => 'http://example.com',
    ]);
});

it('does not link previously saved non-http social links on the profile', function () {
    $user = User::factory()->create([
        'social_links' => [
            'website' => 'data://text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==',
            'github' => 'https://github.com/test',
        ],
    ]);

    $this->get('/profile/'.$user->username)
        ->assertOk()
        ->assertSee('href="https://github.com/test"', false)
        ->assertDontSee('data://text/html', false);
});
