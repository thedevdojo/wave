<?php

use App\Models\User;

it('allows user to update their username', function () {
    // Use an existing seeded user (admin user from seed)
    $user = User::where('email', 'admin@admin.com')->first();
    $originalUsername = $user->username;

    $this->actingAs($user);

    // Update the username
    $newUsername = 'updated_username_'.time();
    $user->username = $newUsername;
    $user->save();

    $user->refresh();
    expect($user->username)->toBe($newUsername);

    // Restore original username for other tests
    $user->username = $originalUsername;
    $user->save();
});

it('validates username is unique when updating', function () {
    // Get two seeded users
    $users = User::limit(2)->get();
    $user1 = $users[0];
    $user2 = $users[1];

    $this->actingAs($user1);

    // Attempt to update to user2's username should fail
    $user1->username = $user2->username;

    expect(function () use ($user1) {
        $user1->save();
    })->toThrow(\Illuminate\Database\QueryException::class);
});

it('allows username with dashes and underscores', function () {
    $user = User::where('email', 'admin@admin.com')->first();
    $originalUsername = $user->username;

    $this->actingAs($user);

    // Update with dashes and underscores
    $user->username = 'new-user_name';
    $user->save();

    $user->refresh();
    expect($user->username)->toBe('new-user_name');

    // Restore
    $user->username = $originalUsername;
    $user->save();
});

it('user can keep their current username when updating profile', function () {
    $user = User::where('email', 'admin@admin.com')->first();
    $currentUsername = $user->username;
    $originalName = $user->name;

    $this->actingAs($user);

    // Update name but keep username
    $user->name = 'Updated Name';
    $user->save();

    $user->refresh();
    expect($user->username)->toBe($currentUsername);
    expect($user->name)->toBe('Updated Name');

    // Restore
    $user->name = $originalName;
    $user->save();
});

it('multiple users can update their usernames independently', function () {
    $users = User::limit(2)->get();
    $user1 = $users[0];
    $user2 = $users[1];

    $original1 = $user1->username;
    $original2 = $user2->username;

    // Both users update their usernames
    $user1->username = 'temp-user1-'.time();
    $user1->save();

    $user2->username = 'temp-user2-'.time();
    $user2->save();

    $user1->refresh();
    $user2->refresh();

    expect($user1->username)->toStartWith('temp-user1-');
    expect($user2->username)->toStartWith('temp-user2-');

    // Restore
    $user1->username = $original1;
    $user1->save();
    $user2->username = $original2;
    $user2->save();
});

it('username field is included in user model fillable attributes', function () {
    $user = new User();

    expect($user->getFillable())->toContain('username');
});

it('username can be updated through mass assignment', function () {
    $user = User::where('email', 'admin@admin.com')->first();
    $originalUsername = $user->username;

    $newUsername = 'mass_assigned_'.time();
    $user->fill(['username' => $newUsername]);
    $user->save();

    $user->refresh();
    expect($user->username)->toBe($newUsername);

    // Restore
    $user->username = $originalUsername;
    $user->save();
});
