<?php

use App\Models\User;
use Illuminate\Database\QueryException;

it('allows user to update their username', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    // Update the username
    $newUsername = 'updated_username_'.time();
    $user->username = $newUsername;
    $user->save();

    $user->refresh();
    expect($user->username)->toBe($newUsername);
});

it('validates username is unique when updating', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $this->actingAs($user1);

    // Attempt to update to user2's username should fail
    $user1->username = $user2->username;

    expect(function () use ($user1) {
        $user1->save();
    })->toThrow(QueryException::class);
});

it('allows username with dashes and underscores', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    // Update with dashes and underscores
    $user->username = 'new-user_name';
    $user->save();

    $user->refresh();
    expect($user->username)->toBe('new-user_name');
});

it('user can keep their current username when updating profile', function () {
    $user = User::factory()->create();
    $currentUsername = $user->username;

    $this->actingAs($user);

    // Update name but keep username
    $user->name = 'Updated Name';
    $user->save();

    $user->refresh();
    expect($user->username)->toBe($currentUsername);
    expect($user->name)->toBe('Updated Name');
});

it('multiple users can update their usernames independently', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    // Both users update their usernames
    $user1->username = 'temp-user1-'.time();
    $user1->save();

    $user2->username = 'temp-user2-'.time();
    $user2->save();

    $user1->refresh();
    $user2->refresh();

    expect($user1->username)->toStartWith('temp-user1-');
    expect($user2->username)->toStartWith('temp-user2-');
});

it('username field is included in user model fillable attributes', function () {
    $user = new User;

    expect($user->getFillable())->toContain('username');
});

it('username can be updated through mass assignment', function () {
    $user = User::factory()->create();

    $newUsername = 'mass_assigned_'.time();
    $user->fill(['username' => $newUsername]);
    $user->save();

    $user->refresh();
    expect($user->username)->toBe($newUsername);
});
