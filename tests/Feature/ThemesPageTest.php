<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

test('ignores files in the themes folder, such as .gitignore, under open_basedir', function () {
    $root = sys_get_temp_dir().'/wave-test-'.Str::random(12);
    $themesPath = $root.'/themes';
    File::ensureDirectoryExists($themesPath.'/test-theme');
    File::put($themesPath.'/test-theme/theme.json', json_encode(['name' => 'Test Theme', 'version' => '1.0']));
    File::put($themesPath.'/.gitignore', '');
    File::put($themesPath.'/.DS_Store', '');
    $this->beforeApplicationDestroyed(fn () => File::deleteDirectory($root));

    // open_basedir can't be lifted once set, so list the themes in a separate process.
    $result = Process::path(base_path())->run([
        PHP_BINARY,
        '-d', 'open_basedir='.base_path().PATH_SEPARATOR.$root,
        '-r',
        'require "vendor/autoload.php";'
        .'$app = require "bootstrap/app.php";'
        .'$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();'
        .'echo json_encode(array_keys((array) (function ($path) { $this->themes_folder = $path; return $this->getThemesFromFolder(); })->call(new App\Filament\Pages\Themes, $argv[1])));',
        $themesPath,
    ]);

    expect($result->errorOutput().$result->output())->toBe('["test-theme"]');
});
