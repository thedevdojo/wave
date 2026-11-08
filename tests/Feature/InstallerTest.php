<?php

use Illuminate\Support\Facades\File;

it('shows the install screen when the database has no users yet', function () {
    $this->app['env'] = 'local';

    // The installer creates database.sqlite if it's missing, so keep it out of the project.
    $databasePath = sys_get_temp_dir().'/wave-install-'.uniqid();
    $this->app->useDatabasePath($databasePath);
    $this->beforeApplicationDestroyed(fn () => File::deleteDirectory($databasePath));

    $this->get('/install')->assertOk()->assertSee('Install Wave');
});
