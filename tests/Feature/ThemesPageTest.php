<?php

use App\Filament\Pages\Themes;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use Wave\Theme;

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

describe('Themes admin page', function () {
    beforeEach(function () {
        $this->actingAs(User::factory()->admin()->create());

        $this->themesPath = sys_get_temp_dir().'/wave-test-'.Str::random(12);
        foreach (['first-theme' => 'First Theme', 'second-theme' => 'Second Theme'] as $folder => $name) {
            File::ensureDirectoryExists($this->themesPath.'/'.$folder);
            File::put($this->themesPath.'/'.$folder.'/theme.json', json_encode(['name' => $name, 'version' => '1.0']));
        }
        $this->beforeApplicationDestroyed(fn () => File::deleteDirectory($this->themesPath));

        config(['themes.folder' => $this->themesPath]);
        Theme::query()->delete();
    });

    it('lists the themes in the configured themes folder', function () {
        Livewire\Livewire::test(Themes::class)
            ->assertOk()
            ->assertSee('First Theme')
            ->assertSee('Second Theme');
    });

    it('does not delete the active theme', function () {
        Livewire\Livewire::test(Themes::class);
        Theme::where('folder', 'first-theme')->update(['active' => 1]);

        Livewire\Livewire::test(Themes::class)
            ->assertSeeHtml("deleteTheme('second-theme')")
            ->assertDontSeeHtml("deleteTheme('first-theme')")
            ->call('deleteTheme', 'first-theme')
            ->assertNotified('The active theme can\'t be deleted. Activate another theme first.');

        expect(File::isDirectory($this->themesPath.'/first-theme'))->toBeTrue()
            ->and(Theme::where('folder', 'first-theme')->exists())->toBeTrue();
    });

    it('deletes an inactive theme', function () {
        Livewire\Livewire::test(Themes::class)
            ->call('deleteTheme', 'second-theme')
            ->assertNotified('Theme successfully deleted')
            ->assertDontSee('Second Theme');

        expect(File::isDirectory($this->themesPath.'/second-theme'))->toBeFalse()
            ->and(Theme::where('folder', 'second-theme')->exists())->toBeFalse();
    });

    it('shows a notification when deleting a theme that is not installed', function () {
        Livewire\Livewire::test(Themes::class)
            ->call('deleteTheme', 'missing-theme')
            ->assertNotified('Theme not found, please make sure this theme exists in the database.');
    });
});
