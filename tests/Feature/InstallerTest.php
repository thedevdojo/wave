<?php

use App\Models\User;
use Illuminate\Support\Facades\File;

it('shows the install screen when the database has no users yet', function () {
    $this->app['env'] = 'local';

    // The installer creates database.sqlite if it's missing, so keep it out of the project.
    $databasePath = sys_get_temp_dir().'/wave-install-'.uniqid();
    $this->app->useDatabasePath($databasePath);
    $this->beforeApplicationDestroyed(fn () => File::deleteDirectory($databasePath));

    $this->get('/install')->assertOk()->assertSee('Install Wave');
});

it('explains how to install from the command line outside local', function () {
    expect(app()->isLocal())->toBeFalse();

    $this->get('/install')
        ->assertOk()
        ->assertSee("Wave isn't installed yet", false)
        ->assertSee('php artisan migrate --seed')
        ->assertDontSee('Install Wave');
});

it('shows the same message when the tables do not exist yet', function () {
    // Point at an empty database instead of dropping tables from the test one.
    $default = config('database.default');
    config(['database.connections.wave_empty' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']]);
    config(['database.default' => 'wave_empty']);

    try {
        $this->get('/install')->assertOk()->assertSee('php artisan migrate --seed');
    } finally {
        config(['database.default' => $default]);
    }
});

it('sends an installed app home instead of showing the installer', function (string $env, string $uri) {
    $this->app['env'] = $env;
    User::factory()->create();

    $this->get($uri)->assertRedirect('/');
})->with([
    'outside local' => ['production', '/install'],
    'local' => ['local', '/install'],
    'local, finishing again' => ['local', '/install?complete=true'],
]);
