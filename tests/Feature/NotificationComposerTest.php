<?php

use App\Filament\Pages\Notifications;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Livewire\Livewire;
use Livewire\Volt\Volt;
use Wave\ActivityLog;
use Wave\Http\Livewire\NotificationBadge;
use Wave\Jobs\DeliverNotificationBroadcast;
use Wave\NotificationBroadcast;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

it('lets admins compose a labeled rich notification for queued delivery', function () {
    Bus::fake();
    $this->actingAs($this->admin);
    $component = Livewire::test(Notifications::class)->fillForm([
        'title' => 'Account protection', 'category' => 'security',
        'body' => '<h2>Protect your account</h2><p><strong>Enable two factor</strong> and <a href="https://example.com/security">learn more</a>.</p><script>alert(1)</script>',
    ])->mountAction('send')->assertActionMounted('send');
    Bus::assertNotDispatched(DeliverNotificationBroadcast::class);
    expect(NotificationBroadcast::count())->toBe(0);
    $component->callMountedAction()->assertHasNoFormErrors();
    $broadcast = NotificationBroadcast::sole();
    expect($broadcast->title)->toBe('Account protection')
        ->and($broadcast->body)->toContain('<strong>', 'https://example.com/security')->not->toContain('<script>');
    Bus::assertDispatched(DeliverNotificationBroadcast::class, fn ($job) => $job->broadcastId === $broadcast->id);
});

it('limits the composer to admins and rejects blog labels and blank messages', function () {
    $this->actingAs(User::factory()->create());
    $this->get('/admin/notifications')->assertForbidden();
    Livewire::test(Notifications::class)->assertForbidden();
    $this->actingAs($this->admin);
    Livewire::test(Notifications::class)->fillForm(['title' => 'News', 'category' => 'blog', 'body' => '<p>News</p>'])
        ->call('send')->assertHasFormErrors(['category']);
    Livewire::test(Notifications::class)->fillForm(['title' => 'News', 'category' => 'marketing', 'body' => '<p></p>'])
        ->call('send')->assertHasFormErrors(['body']);
    expect(NotificationBroadcast::count())->toBe(0);
});

it('delivers once to existing users and preserves read state on retry', function () {
    $user = User::factory()->create();
    $broadcast = NotificationBroadcast::create(['title' => 'Product news', 'category' => 'product_update', 'body' => '<p><strong>New feature</strong></p>']);
    $this->travel(1)->minutes();
    $laterUser = User::factory()->create();
    $job = new DeliverNotificationBroadcast($broadcast->id);
    $job->handle();
    expect($user->notifications()->count())->toBe(1)->and($this->admin->notifications()->count())->toBe(1)
        ->and($laterUser->notifications()->count())->toBe(0)->and($broadcast->fresh()->sent_at)->not->toBeNull();
    $delivered = $user->notifications()->sole();
    expect($delivered->data['category'])->toBe('product_update');
    $delivered->markAsRead();
    // Simulate a retry after delivery but before the completion timestamp was saved.
    $broadcast->update(['sent_at' => null]);
    $job->handle();
    expect($user->notifications()->count())->toBe(1)->and($user->unreadNotifications()->count())->toBe(0);
});

it('renders sanitized rich messages and updates the badge when marked as read', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $broadcast = NotificationBroadcast::create(['title' => '<b>News title</b>', 'category' => 'marketing', 'body' => '<h2>Details</h2><p><strong>New features</strong> <a href="javascript:alert(1)">Unsafe link</a></p><img src=x onerror=alert(1)>']);
    (new DeliverNotificationBroadcast($broadcast->id))->handle();
    $notification = $user->notifications()->sole();
    $this->actingAs($user);
    $badge = Livewire::test(NotificationBadge::class)->assertSee('1 unread notifications');
    $this->get('/notifications')->assertOk()->assertSee('Marketing')->assertSee('&lt;b&gt;News title&lt;/b&gt;', false)
        ->assertSee('<strong>New features</strong>', false)->assertDontSee('javascript:', false)->assertDontSee('onerror=', false);
    Volt::test('notifications')->call('delete', $other->notifications()->sole()->id);
    expect($other->unreadNotifications()->count())->toBe(1);
    Volt::test('notifications')->call('delete', $notification->id)->assertDispatched('notifications-updated')->assertDontSee('New features');
    expect($user->notifications()->count())->toBe(1)->and($user->unreadNotifications()->count())->toBe(0);
    $badge->dispatch('notifications-updated')->assertDontSee('unread notifications');
});

it('keeps broadcast read receipts through the existing notification endpoint', function () {
    $broadcast = NotificationBroadcast::create(['title' => 'Security notice', 'category' => 'security', 'body' => '<p>Notice</p>']);
    (new DeliverNotificationBroadcast($broadcast->id))->handle();
    $notification = $this->admin->notifications()->sole();
    $this->actingAs($this->admin)->postJson(route('wave.notification.read', ['id' => $notification->id]))->assertOk();
    expect($notification->fresh()->read_at)->not->toBeNull();
    $broadcast->update(['sent_at' => null]);
    (new DeliverNotificationBroadcast($broadcast->id))->handle();
    expect($this->admin->unreadNotifications()->count())->toBe(0);
});

it('does not dispatch a second job for a repeated submission id', function () {
    Bus::fake();
    $this->actingAs($this->admin);
    $component = Livewire::test(Notifications::class);
    NotificationBroadcast::create(['id' => $component->get('submissionId'), 'title' => 'Already queued', 'category' => 'security', 'body' => '<p>Notice</p>']);
    $component->fillForm(['title' => 'Already queued', 'category' => 'security', 'body' => '<p>Notice</p>'])->call('send')->assertHasNoFormErrors();
    expect(NotificationBroadcast::count())->toBe(1);
    Bus::assertNotDispatched(DeliverNotificationBroadcast::class);
});

it('uses one public team identity while recording the actual administrators in activity logs', function () {
    config(['wave.notifications.team_name' => 'Example Team', 'wave.notifications.team_avatar' => '/storage/demo/default.png', 'activity.enabled' => true, 'activity.queue' => false]);
    Bus::fake();
    $secondAdmin = User::factory()->create(['name' => 'Second Administrator']);
    $secondAdmin->assignRole('admin');
    foreach ([$this->admin, $secondAdmin] as $index => $admin) {
        $this->actingAs($admin);
        Livewire::test(Notifications::class)->fillForm(['title' => 'Notice '.$index, 'category' => 'security', 'body' => '<p>Team announcement</p>'])
            ->callAction('send')->assertHasNoFormErrors();
        $log = ActivityLog::where('action', 'notification_broadcast_queued')->latest('id')->firstOrFail();
        expect($log->user_id)->toBe($admin->id);
        $broadcast = NotificationBroadcast::findOrFail($log->metadata['broadcast_id']);
        (new DeliverNotificationBroadcast($broadcast->id))->handle();
    }
    $payloads = $this->admin->notifications()->get()->pluck('data');
    expect($payloads)->toHaveCount(2);
    foreach ($payloads as $payload) {
        expect($payload['user']['name'])->toBe('Example Team')
            ->and($payload['icon'])->toBe(url('/storage/demo/default.png'));
    }
    $this->actingAs($this->admin)->get('/notifications')->assertOk()->assertSee('Example Team')
        ->assertSee('h-10 w-10 shrink-0 rounded-full', false)->assertDontSee('Second Administrator');
});
