<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Volt\Volt;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->notificationId = Str::uuid()->toString();

    DB::table('notifications')->insert([
        'id' => $this->notificationId,
        'type' => 'App\Notifications\TestNotification',
        'notifiable_type' => $this->user->getMorphClass(),
        'notifiable_id' => $this->user->id,
        'data' => json_encode(['message' => 'Test notification']),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($this->user);
});

it('ignores a notification id that is not a uuid', function () {
    // Postgres throws when a uuid column is compared to a non-uuid string
    Volt::test('notifications')
        ->call('delete', 'not-a-uuid')
        ->assertOk();

    expect(DB::table('notifications')->where('id', $this->notificationId)->exists())->toBeTrue();
});

it("deletes the user's own notification from the page", function () {
    Volt::test('notifications')->call('delete', $this->notificationId);

    expect(DB::table('notifications')->where('id', $this->notificationId)->exists())->toBeFalse();
});
