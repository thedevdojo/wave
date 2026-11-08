<?php

namespace Tests;

use Database\Seeders\RolesTableSeeder;
use Database\Seeders\ThemesTableSeeder;
use DevDojo\Themes\ThemesServiceProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Wave\Facades\Wave;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([RolesTableSeeder::class, ThemesTableSeeder::class]);

        // The app boots before the test database is migrated, so load the
        // active theme again now that it exists.
        $this->app->getProvider(ThemesServiceProvider::class)->boot();
    }

    /**
     * Page routes are built from the database at boot, so register them
     * again after the test has created its pages.
     */
    protected function registerPageRoutes(): void
    {
        Route::middleware('web')->group(fn () => Wave::routes());
        Route::getRoutes()->refreshNameLookups();
    }

    /**
     * Point the plugins path at an empty temporary folder, removed after the
     * test. It sits inside its own parent folder so tests can write next to it.
     */
    protected function useTemporaryPluginsPath(): string
    {
        $root = sys_get_temp_dir().'/wave-test-'.Str::random(12);
        $path = $root.'/plugins';

        File::ensureDirectoryExists($path);
        config(['wave.plugins_path' => $path]);

        $this->beforeApplicationDestroyed(fn () => File::deleteDirectory($root));

        return $path;
    }
}
