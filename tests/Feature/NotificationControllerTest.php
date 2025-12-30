<?php

use App\Models\User;
use App\Notifications\TestNotification;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

beforeEach(function () {
    // Ensure database is set up
    $this->artisan('migrate:fresh');
});

it('allows authenticated user to delete their own notification', function () {
    $user = User::factory()->create();
    
    // Create a notification for the user
    $user->notify(new TestNotification());
    $notification = $user->notifications()->first();
    
    expect($notification)->not->toBeNull();
    
    $response = actingAs($user)
        ->postJson(route('wave.notification.read', ['id' => $notification->id]), [
            'listid' => 1,
        ]);
    
    $response->assertStatus(200)
        ->assertJson([
            'type' => 'success',
            'message' => 'Marked Notification as Read',
            'listid' => 1,
        ]);
    
    // Verify notification was deleted
    expect($user->fresh()->notifications()->count())->toBe(0);
});

it('returns 404 when notification does not exist', function () {
    $user = User::factory()->create();
    $fakeUuid = Str::uuid()->toString();
    
    $response = actingAs($user)
        ->postJson(route('wave.notification.read', ['id' => $fakeUuid]));
    
    $response->assertStatus(404)
        ->assertJson([
            'type' => 'error',
            'message' => 'Notification not found.',
        ]);
});

it('returns 404 when trying to delete another users notification', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    // Create a notification for user1
    $user1->notify(new TestNotification());
    $notification = $user1->notifications()->first();
    
    // Try to delete user1's notification while authenticated as user2
    $response = actingAs($user2)
        ->postJson(route('wave.notification.read', ['id' => $notification->id]));
    
    $response->assertStatus(404)
        ->assertJson([
            'type' => 'error',
            'message' => 'Notification not found.',
        ]);
    
    // Verify notification was NOT deleted
    expect($user1->fresh()->notifications()->count())->toBe(1);
});

it('returns 400 for invalid UUID format', function () {
    $user = User::factory()->create();
    
    $response = actingAs($user)
        ->postJson(route('wave.notification.read', ['id' => 'invalid-uuid']));
    
    $response->assertStatus(400)
        ->assertJson([
            'type' => 'error',
            'message' => 'Invalid notification ID format.',
        ]);
});

it('requires authentication to delete notifications', function () {
    $fakeUuid = Str::uuid()->toString();
    
    $response = postJson(route('wave.notification.read', ['id' => $fakeUuid]));
    
    $response->assertStatus(401);
});

it('successfully deletes multiple notifications for the same user', function () {
    $user = User::factory()->create();
    
    // Create multiple notifications
    $user->notify(new TestNotification());
    $user->notify(new TestNotification());
    $user->notify(new TestNotification());
    
    expect($user->notifications()->count())->toBe(3);
    
    $notifications = $user->notifications()->get();
    
    // Delete first notification
    $response = actingAs($user)
        ->postJson(route('wave.notification.read', ['id' => $notifications[0]->id]), ['listid' => 1]);
    
    $response->assertStatus(200);
    expect($user->fresh()->notifications()->count())->toBe(2);
    
    // Delete second notification
    $response = actingAs($user)
        ->postJson(route('wave.notification.read', ['id' => $notifications[1]->id]), ['listid' => 2]);
    
    $response->assertStatus(200);
    expect($user->fresh()->notifications()->count())->toBe(1);
});

it('preserves other users notifications when deleting own notification', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    // Create notifications for both users
    $user1->notify(new TestNotification());
    $user2->notify(new TestNotification());
    
    expect($user1->notifications()->count())->toBe(1);
    expect($user2->notifications()->count())->toBe(1);
    
    $notification = $user1->notifications()->first();
    
    // User1 deletes their notification
    $response = actingAs($user1)
        ->postJson(route('wave.notification.read', ['id' => $notification->id]));
    
    $response->assertStatus(200);
    
    // Verify user1's notification is gone but user2's remains
    expect($user1->fresh()->notifications()->count())->toBe(0);
    expect($user2->fresh()->notifications()->count())->toBe(1);
});
