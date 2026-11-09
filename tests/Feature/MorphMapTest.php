<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;

it('maps every morph alias to a model class', function () {
    expect(Relation::morphMap())->each->toBeString()->not->toBeEmpty();
});

it('keeps storing users under the users alias', function () {
    $user = User::factory()->create();

    expect($user->getMorphClass())->toBe('users')
        ->and(Relation::getMorphedModel('users'))->toBe(User::class);

    // Roles and notifications are stored with this alias
    $this->assertDatabaseHas('model_has_roles', ['model_type' => 'users', 'model_id' => $user->id]);
});
