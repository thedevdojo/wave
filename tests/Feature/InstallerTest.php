<?php

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

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
    Schema::drop('users');

    $this->get('/install')->assertOk()->assertSee('php artisan migrate --seed');
});

it('says wave is installed once there are users outside local', function () {
    User::factory()->create();

    $this->get('/install')
        ->assertOk()
        ->assertSee('Wave is already installed')
        ->assertDontSee('php artisan migrate --seed');
});
