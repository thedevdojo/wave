<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

beforeEach(function () {
    Http::preventStrayRequests();
    Http::fake(['cdn.devdojo.com/*' => Http::response('image')]);

    $this->pluginsPath = $this->useTemporaryPluginsPath();
});

test('generated plugin class can be loaded', function () {
    $this->artisan('plugin:create', ['name' => 'SEO Tools'])
        ->expectsQuestion('Provide a short description for your plugin:', "Tom's \\ plugin")
        ->assertSuccessful();

    $pluginFile = $this->pluginsPath.'/seo-tools/SeoToolsPlugin.php';
    $componentFile = $this->pluginsPath.'/seo-tools/src/Components/SeoTools.php';

    expect($pluginFile)->toBeFile()
        ->and($componentFile)->toBeFile();

    // Load the generated classes in a separate process so incompatible
    // signatures or parse errors fail the test instead of the test run.
    $result = Process::path(base_path())->run([
        PHP_BINARY,
        '-r',
        'require "vendor/autoload.php";'
        .'require $argv[1]; require $argv[2];'
        .'echo (new ReflectionClass(Wave\Plugins\SeoTools\SeoToolsPlugin::class))->getDefaultProperties()["description"];',
        $pluginFile,
        $componentFile,
    ]);

    expect($result->errorOutput().$result->output())->toBe("Tom's \\ plugin");
});

test('plugin names that do not map to a matching folder and class are rejected', function (string $name) {
    $before = File::directories($this->pluginsPath);

    $this->artisan('plugin:create', ['name' => $name])->assertFailed();

    expect(File::directories($this->pluginsPath))->toBe($before);
})->with(['Plugin 2 Go', '!!!']);
