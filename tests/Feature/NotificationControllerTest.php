<?php

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->user = User::factory()->create();
});

afterEach(function () {
    $this->user->forceDelete();
});

/**
 * Helper to create a notification for a user
 */
function createNotificationForUser(User $user, ?string $id = null, bool $read = false): string
{
    $notificationId = $id ?? Str::uuid()->toString();

    DB::table('notifications')->insert([
        'id' => $notificationId,
        'type' => 'App\Notifications\TestNotification',
        'notifiable_type' => $user->getMorphClass(),
        'notifiable_id' => $user->id,
        'data' => json_encode(['message' => 'Test notification']),
        'read_at' => $read ? now() : null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return $notificationId;
}

describe('Notification Controller', function () {
    it('requires authentication to delete notification', function () {
        $response = $this->post(route('wave.notification.read', ['id' => 'fake-id']));

        $response->assertRedirect(route('login'));
    });

    it('deletes users own notification', function () {
        $this->actingAs($this->user);

        $notificationId = createNotificationForUser($this->user);

        expect($this->user->notifications()->count())->toBe(1);

        $response = $this->postJson(route('wave.notification.read', ['id' => $notificationId]));

        $response->assertStatus(200);
        $response->assertJson([
            'type' => 'success',
            'message' => 'Marked Notification as Read',
        ]);

        expect($this->user->notifications()->count())->toBe(0);
    });

    it('returns error for non-existent notification', function () {
        $this->actingAs($this->user);

        $response = $this->postJson(route('wave.notification.read', ['id' => 'non-existent-id']));

        $response->assertStatus(200);
        $response->assertJson([
            'type' => 'error',
            'message' => 'Could not find the specified notification.',
        ]);
    });

    it('cannot delete another users notification', function () {
        $this->actingAs($this->user);

        $otherUser = User::factory()->create();
        $notificationId = createNotificationForUser($otherUser);

        $response = $this->postJson(route('wave.notification.read', ['id' => $notificationId]));

        $response->assertJson([
            'type' => 'error',
            'message' => 'Could not find the specified notification.',
        ]);

        // Notification should still exist
        expect($otherUser->notifications()->count())->toBe(1);

        $otherUser->forceDelete();
    });

    it('includes listid in response when provided', function () {
        $this->actingAs($this->user);

        $notificationId = createNotificationForUser($this->user);

        $response = $this->postJson(route('wave.notification.read', ['id' => $notificationId]), [
            'listid' => 'notification-list-1',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'type' => 'success',
            'listid' => 'notification-list-1',
        ]);
    });
});

describe('User Notifications Relationship', function () {
    it('user can have multiple notifications', function () {
        for ($i = 1; $i <= 3; $i++) {
            createNotificationForUser($this->user);
        }

        expect($this->user->notifications)->toHaveCount(3);
    });

    it('can mark notification as read', function () {
        $notificationId = createNotificationForUser($this->user);

        $notification = DatabaseNotification::find($notificationId);

        expect($notification->read_at)->toBeNull();
        expect($this->user->unreadNotifications)->toHaveCount(1);

        $notification->markAsRead();

        expect($notification->fresh()->read_at)->not->toBeNull();
        expect($this->user->fresh()->unreadNotifications)->toHaveCount(0);
    });

    it('separates read and unread notifications', function () {
        createNotificationForUser($this->user, null, false); // Unread
        createNotificationForUser($this->user, null, true);  // Read

        expect($this->user->notifications)->toHaveCount(2);
        expect($this->user->unreadNotifications)->toHaveCount(1);
        expect($this->user->readNotifications)->toHaveCount(1);
    });
});
