<?php

use App\Models\User;

it('skips usernames held by soft-deleted users when generating one', function () {
    $old = User::factory()->create(['name' => 'John Smith', 'username' => 'johnsmith']);
    $old->delete();

    $new = User::factory()->create(['name' => 'John Smith', 'username' => null]);

    expect($new->username)->toBe('johnsmith1');
});
