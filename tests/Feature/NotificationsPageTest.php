<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Volt\Volt;

it('ignores a notification id that is not a uuid', function () {
    $user = User::factory()->create();
    $id = Str::uuid()->toString();

    DB::table('notifications')->insert([
        'id' => $id,
        'type' => 'App\Notifications\TestNotification',
        'notifiable_type' => $user->getMorphClass(),
        'notifiable_id' => $user->id,
        'data' => json_encode(['message' => 'Test notification']),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user);

    // Postgres throws when a uuid column is compared to a non-uuid string
    Volt::test('notifications')
        ->call('delete', 'not-a-uuid')
        ->assertOk();

    expect(DB::table('notifications')->where('id', $id)->exists())->toBeTrue();
});

it('deletes the users own notification from the page', function () {
    $user = User::factory()->create();
    $id = Str::uuid()->toString();

    DB::table('notifications')->insert([
        'id' => $id,
        'type' => 'App\Notifications\TestNotification',
        'notifiable_type' => $user->getMorphClass(),
        'notifiable_id' => $user->id,
        'data' => json_encode(['message' => 'Test notification']),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user);

    Volt::test('notifications')->call('delete', $id);

    expect(DB::table('notifications')->where('id', $id)->exists())->toBeFalse();
});
