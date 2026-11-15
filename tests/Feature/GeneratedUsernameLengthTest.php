<?php

use App\Models\User;

it('reserves space for collision suffixes within the username column', function () {
    $name = str_repeat('a', 191);
    $names = collect(range(0, 12))->map(fn () => User::factory()->create(['name' => $name, 'username' => null])->username);
    expect($names->unique())->toHaveCount(13)
        ->and($names->every(fn ($username) => strlen($username) <= 191))->toBeTrue()
        ->and($names[0])->toBe($name)
        ->and($names[1])->toBe(str_repeat('a', 190).'1')
        ->and($names[10])->toBe(str_repeat('a', 189).'10');
});

it('keeps deleted usernames reserved when choosing a bounded suffix', function () {
    $name = str_repeat('b', 191);
    $first = User::factory()->create(['name' => $name, 'username' => null]);
    $first->delete();
    $second = User::factory()->create(['name' => $name, 'username' => null]);
    expect($second->username)->toBe(str_repeat('b', 190).'1');
});

it('bounds usernames even when transliteration expands a valid length name', function () {
    $user = User::factory()->create(['name' => str_repeat('ß', 191), 'username' => null]);
    expect(strlen($user->username))->toBeLessThanOrEqual(191)->and($user->username)->not->toBe('');
});

it('uses a nonempty unique fallback when a name cannot produce a slug', function () {
    $first = User::factory()->create(['name' => '!!!', 'username' => null]);
    $second = User::factory()->create(['name' => '!!!', 'username' => null]);
    expect($first->username)->toBe('user')->and($second->username)->toBe('user1');
});

it('preserves explicit usernames and valid numeric name slugs', function () {
    $explicit = User::factory()->create(['name' => str_repeat('n', 191), 'username' => 'existing-name']);
    $numeric = User::factory()->create(['name' => '0', 'username' => null]);
    expect($explicit->username)->toBe('existing-name')->and($numeric->username)->toBe('0');
});
