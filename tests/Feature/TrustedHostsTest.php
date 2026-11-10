<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Middleware\TrustHosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    config(['app.url' => 'https://wave.test']);

    Route::get('send-reset-link', function () {
        Password::broker()->sendResetLink(['email' => request('email')]);

        return 'sent';
    });
});

afterEach(function () {
    Request::setTrustedHosts([]);
    TrustHosts::flushState();
});

it('rejects requests for a host other than APP_URL outside local', function () {
    Notification::fake();
    $user = User::factory()->create();
    $this->app['env'] = 'production';

    $this->get('http://evil.test/send-reset-link?email='.urlencode($user->email))->assertStatus(400);

    Notification::assertNothingSent();
});

it('sends reset links for APP_URL and its subdomains', function (string $host) {
    Notification::fake();
    $user = User::factory()->create();
    $this->app['env'] = 'production';

    $this->get("https://{$host}/send-reset-link?email=".urlencode($user->email))->assertOk();

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user, $host) {
        return str_starts_with($notification->toMail($user)->actionUrl, "https://{$host}/");
    });
})->with(['wave.test', 'app.wave.test']);

it('accepts any host locally', function () {
    $this->app['env'] = 'local';

    $this->get('http://127.0.0.1:8000/send-reset-link?email=nobody@example.com')->assertOk();
});
