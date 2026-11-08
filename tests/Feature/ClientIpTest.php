<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Wave\ActivityLog;

it('rate limits guests by their ip, not a client supplied header', function () {
    $request = Request::create('/api/login', 'POST', server: [
        'REMOTE_ADDR' => '203.0.113.7',
        'HTTP_CF_CONNECTING_IP' => '198.51.100.1',
    ]);

    expect(RateLimiter::limiter('api')($request)->key)->toBe('203.0.113.7');
});

it('logs the request ip, not a client supplied header', function () {
    $this->actingAs(User::factory()->create());
    $this->app->instance('request', Request::create('/', server: [
        'REMOTE_ADDR' => '203.0.113.7',
        'HTTP_CF_CONNECTING_IP' => '198.51.100.1',
    ]));
    config(['activity.enabled' => true, 'activity.queue' => false]);

    expect(ActivityLog::log('test_action')->ip_address)->toBe('203.0.113.7');
});
