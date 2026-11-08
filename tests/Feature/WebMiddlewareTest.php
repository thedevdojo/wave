<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Wave\Http\Middleware\ThemeDemoMiddleware;

it('runs the demo theme switcher when demo mode is on', function () {
    // config('wave.demo') is read while the app boots, so boot a fresh app with it on.
    $app = require base_path('bootstrap/app.php');
    $app->afterBootstrapping(LoadConfiguration::class, fn ($app) => $app['config']->set('wave.demo', true));
    $app->make(Kernel::class)->bootstrap();
    $this->app = $app;

    expect($app['router']->getMiddlewareGroups()['web'])->toContain(ThemeDemoMiddleware::class);

    $this->get('/install?theme=drift')
        ->assertRedirect('/')
        ->assertPlainCookie('theme', 'drift');
});

it('leaves the theme switcher off outside demo mode', function () {
    expect(config('wave.demo'))->toBeFalsy();
    expect(app('router')->getMiddlewareGroups()['web'])->not->toContain(ThemeDemoMiddleware::class);

    $this->get('/install?theme=drift')->assertCookieMissing('theme');
});
