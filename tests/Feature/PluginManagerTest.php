<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Wave\Plugins\PluginManager;

beforeEach(function () {
    $this->pluginsPath = $this->useTemporaryPluginsPath();

    File::ensureDirectoryExists($this->pluginsPath.'/route-fixture/routes');
    File::put($this->pluginsPath.'/route-fixture/RouteFixturePlugin.php', <<<'PHP'
<?php

namespace Wave\Plugins\RouteFixture;

use Wave\Plugins\Plugin;

class RouteFixturePlugin extends Plugin
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    public function getPluginInfo(): array
    {
        return ['name' => 'RouteFixture'];
    }
}
PHP);
    File::put($this->pluginsPath.'/route-fixture/routes/web.php', <<<'PHP'
<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->get('route-fixture', fn () => 'Hello from the plugin');
PHP);
});

// The fixture class can only be declared once per process, so this stays one test.
test('installed plugins register their routes and only problems are logged', function () {
    File::put($this->pluginsPath.'/installed.json', json_encode(['route-fixture', 'missing-plugin']));
    Cache::forget('wave_installed_plugins');
    Log::spy();

    // Same call PluginServiceProvider makes at boot, against the temporary folder.
    (new PluginManager($this->app))->loadPlugins();

    Log::shouldNotHaveReceived('info');
    Log::shouldHaveReceived('warning')->once()->with('Plugin file not found for: missing-plugin');

    $this->get('route-fixture')->assertOk()->assertSee('Hello from the plugin');
});
