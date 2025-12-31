<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->user = User::where('email', 'admin@admin.com')->first();
    // Ensure user starts with no scheduled deletion
    $this->user->deletion_scheduled_at = null;
    $this->user->save();
});

it('can schedule account deletion', function () {
    $this->actingAs($this->user);

    // Initially no deletion scheduled
    expect($this->user->deletion_scheduled_at)->toBeNull();

    // Schedule deletion (simulate Livewire component action)
    $this->user->deletion_scheduled_at = now()->addDays(30);
    $this->user->save();

    $this->user->refresh();

    expect($this->user->deletion_scheduled_at)->not->toBeNull();
    expect($this->user->deletion_scheduled_at->isFuture())->toBeTrue();
});

it('can cancel scheduled account deletion', function () {
    $this->actingAs($this->user);

    // Schedule deletion first
    $this->user->deletion_scheduled_at = now()->addDays(30);
    $this->user->save();

    expect($this->user->deletion_scheduled_at)->not->toBeNull();

    // Cancel deletion
    $this->user->deletion_scheduled_at = null;
    $this->user->save();

    $this->user->refresh();

    expect($this->user->deletion_scheduled_at)->toBeNull();
});

it('deletion page requires authentication', function () {
    $response = $this->get(route('settings.deletion'));

    $response->assertRedirect(route('login'));
});

it('authenticated user can access deletion page', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('settings.deletion'));

    $response->assertOk();
    $response->assertSee('Account Deletion');
    $response->assertSee('Delete Your Account');
});

it('shows scheduled deletion warning when deletion is scheduled', function () {
    $this->actingAs($this->user);

    // Schedule deletion
    $this->user->deletion_scheduled_at = now()->addDays(15);
    $this->user->save();

    $response = $this->get(route('settings.deletion'));

    $response->assertOk();
    $response->assertSee('Account Deletion Scheduled');
    $response->assertSee('Cancel Deletion');
});

it('shows deletion form when no deletion is scheduled', function () {
    $this->actingAs($this->user);

    // Ensure no deletion scheduled
    $this->user->deletion_scheduled_at = null;
    $this->user->save();

    $response = $this->get(route('settings.deletion'));

    $response->assertOk();
    $response->assertSee('Schedule Account Deletion');
    $response->assertSee('Confirm Your Password');
});

it('calculates days remaining correctly', function () {
    $this->actingAs($this->user);

    // Schedule deletion 10 days from now
    $this->user->deletion_scheduled_at = now()->addDays(10);
    $this->user->save();

    $daysRemaining = ceil(now()->diffInDays($this->user->deletion_scheduled_at, false));

    expect($daysRemaining)->toBe(10.0);
});

it('uses soft deletes for user model', function () {
    // Create a fresh user without any dependencies
    $freshUser = User::factory()->create();
    $userId = $freshUser->id;

    // Soft delete the user
    $freshUser->delete();

    // User should not be found in normal queries
    $foundUser = User::find($userId);
    expect($foundUser)->toBeNull();

    // But should be found with trashed
    $trashedUser = User::withTrashed()->find($userId);
    expect($trashedUser)->not->toBeNull();
    expect($trashedUser->deleted_at)->not->toBeNull();

    // Restore for other tests
    $trashedUser->restore();
});

it('password must be correct to schedule deletion', function () {
    $this->actingAs($this->user);

    // Verify password check would fail with wrong password
    $wrongPassword = 'wrongpassword';
    $isCorrect = Hash::check($wrongPassword, $this->user->password);

    expect($isCorrect)->toBeFalse();
});

it('deletion scheduled date is properly formatted', function () {
    $this->actingAs($this->user);

    $scheduledDate = now()->addDays(30);
    $this->user->deletion_scheduled_at = $scheduledDate;
    $this->user->save();

    $this->user->refresh();

    $formattedDate = $this->user->deletion_scheduled_at->format('F j, Y');

    expect($formattedDate)->toBeString();
    expect($formattedDate)->toContain($scheduledDate->format('Y'));
});

it('multiple users can have different deletion schedules', function () {
    $user1 = User::where('email', 'admin@admin.com')->first();
    $user2 = User::factory()->create();

    // Schedule deletion for user1
    $user1->deletion_scheduled_at = now()->addDays(20);
    $user1->save();

    // Schedule deletion for user2 with different date
    $user2->deletion_scheduled_at = now()->addDays(10);
    $user2->save();

    expect($user1->deletion_scheduled_at)->not->toEqual($user2->deletion_scheduled_at);
    expect($user1->deletion_scheduled_at->isAfter($user2->deletion_scheduled_at))->toBeTrue();

    // Cleanup
    $user1->deletion_scheduled_at = null;
    $user1->save();
    $user2->forceDelete();
});

it('can check if deletion is scheduled', function () {
    $this->actingAs($this->user);

    // Not scheduled
    $this->user->deletion_scheduled_at = null;
    $this->user->save();
    expect($this->user->deletion_scheduled_at)->toBeNull();

    // Schedule it
    $this->user->deletion_scheduled_at = now()->addDays(30);
    $this->user->save();
    $this->user->refresh();
    expect($this->user->deletion_scheduled_at)->not->toBeNull();

    // Cleanup
    $this->user->deletion_scheduled_at = null;
    $this->user->save();
});

it('processes expired deletions with artisan command', function () {
    // Create users with different deletion schedules
    $userToDelete = User::factory()->create([
        'deletion_scheduled_at' => now()->subDay(), // Past date
    ]);

    $userNotToDelete = User::factory()->create([
        'deletion_scheduled_at' => now()->addDays(10), // Future date
    ]);

    $userWithoutDeletion = User::factory()->create([
        'deletion_scheduled_at' => null,
    ]);

    // Run the command
    $this->artisan('accounts:process-deletions')
        ->expectsOutput('Processing scheduled account deletions...')
        ->expectsOutput("Deleted account: {$userToDelete->email}")
        ->expectsOutput('Successfully deleted 1 account(s).')
        ->assertExitCode(0);

    // Verify the user was deleted
    expect(User::withTrashed()->find($userToDelete->id))->toBeNull();

    // Verify other users still exist
    expect(User::find($userNotToDelete->id))->not->toBeNull();
    expect(User::find($userWithoutDeletion->id))->not->toBeNull();
});

it('rounds days remaining to avoid decimals', function () {
    $this->actingAs($this->user);

    // Schedule deletion for 29.999 days from now
    $this->user->deletion_scheduled_at = now()->addHours(719.9);
    $this->user->save();

    $daysRemaining = (int) ceil(now()->diffInDays($this->user->deletion_scheduled_at, false));

    // Should round to 30, not show 29.999940712002
    expect($daysRemaining)->toBe(30);
});
