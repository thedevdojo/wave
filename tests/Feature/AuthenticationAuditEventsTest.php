<?php

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Wave\ActivityLog;
use Wave\Jobs\CreateActivityLog;

beforeEach(function () {
    config(['activity.enabled' => true, 'activity.queue' => false]);
    $this->user = User::factory()->create();
});

it('retains rapid real login logout cycles', function () {
    foreach (range(1, 2) as $i) {
        Auth::login($this->user);
        Auth::logout();
    }
    expect(ActivityLog::where('user_id', $this->user->id)->orderBy('id')->pluck('action')->all())
        ->toBe(['login', 'logout', 'login', 'logout']);
});

it('retains different events even for the same user IP and timestamp', function (string $action) {
    $event = $action === 'login' ? new Login('web', $this->user, false) : new Logout('web', $this->user);
    event($event);
    event(clone $event);
    expect(ActivityLog::where('user_id', $this->user->id)->where('action', $action)->count())->toBe(2);
})->with(['login', 'logout']);

it('records the event actor instead of a different default-guard user', function (string $action) {
    $other = User::factory()->create();
    $this->actingAs($other);
    event($action === 'login' ? new Login('api', $this->user, false) : new Logout('api', $this->user));
    $row = ActivityLog::where('action', $action)->sole();
    expect($row->user_id)->toBe($this->user->id)->and($row->metadata)->toBe(['guard' => 'api']);
})->with(['login', 'logout']);

it('captures separate device and IP contexts for close authentication events', function () {
    foreach ([['203.0.113.10', 'First device'], ['203.0.113.11', 'Second device']] as [$ip, $agent]) {
        request()->server->set('REMOTE_ADDR', $ip);
        request()->headers->set('User-Agent', $agent);
        event(new Login('web', $this->user, false));
    }
    $rows = ActivityLog::where('user_id', $this->user->id)->orderBy('id')->get();
    expect($rows->pluck('ip_address')->all())->toBe(['203.0.113.10', '203.0.113.11'])
        ->and($rows->pluck('user_agent')->all())->toBe(['First device', 'Second device']);
});

it('queues each distinct login and logout event with its actor and request context', function () {
    config(['activity.queue' => true]);
    Bus::fake([CreateActivityLog::class]);
    request()->server->set('REMOTE_ADDR', '203.0.113.20');
    $event = new Login('web', $this->user, false);
    event($event);
    event(new Logout('web', $this->user));
    event(new Login('web', $this->user, false));
    Bus::assertDispatchedTimes(CreateActivityLog::class, 3);
    Bus::assertDispatched(CreateActivityLog::class, fn ($job) => $job->data['user_id'] === $this->user->id
        && $job->data['ip_address'] === '203.0.113.20' && $job->data['metadata'] === ['guard' => 'web']);
    expect(ActivityLog::count())->toBe(0);
    foreach (Bus::dispatched(CreateActivityLog::class) as $job) {
        $job->handle();
    }
    expect(ActivityLog::where('user_id', $this->user->id)->orderBy('id')->pluck('action')->all())
        ->toBe(['login', 'logout', 'login']);
});

it('does not record authentication events when activity logging is disabled', function () {
    config(['activity.enabled' => false]);
    event(new Login('web', $this->user, false));
    event(new Logout('web', $this->user));
    expect(ActivityLog::count())->toBe(0);
});

it('ignores logout events without an actor', function () {
    event(new Logout('web', null));
    expect(ActivityLog::count())->toBe(0);
});
