<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Wave\Theme;
use Illuminate\Support\Str;

class Themes extends Page
{
    public $themes = [];

    private $themes_folder = '';

    protected static BackedEnum|string|null $navigationIcon = 'phosphor-paint-bucket-duotone';

    protected static ?int $navigationSort = 8;

    protected string $view = 'filament.pages.themes';

    public function mount()
    {
        $this->themes_folder = config('themes.folder', resource_path('themes'));

        $this->installThemes();
        $this->refreshThemes();
    }

    private function refreshThemes()
    {
        $all_themes = Theme::all();
        $this->themes = [];
        foreach ($all_themes as $theme) {
            // Nur Verzeichnisse berücksichtigen
            if (is_dir(resource_path('themes' . DIRECTORY_SEPARATOR . $theme->folder))) {
                array_push($this->themes, $theme);
            }
        }
    }

    /**
     * Safely scan theme folders in resources/themes (or configured folder).
     * Only directories are treated as theme candidates. Dotfiles are ignored.
     */
    private function getThemesFromFolder(): array
    {
        $themes = [];

        $folder = rtrim($this->themes_folder, DIRECTORY_SEPARATOR);

        if (! is_dir($folder)) {
            @mkdir($folder, 0755, true);
            return $themes;
        }

        $items = scandir($folder);
        if (! is_array($items)) {
            return $themes;
        }

        foreach ($items as $entry) {
            // Skip dotfiles and dotfolders (like .git, .gitignore, etc.)
            if ($entry === '.' || $entry === '..' || str_starts_with($entry, '.')) {
                continue;
            }

            $entryPath = $folder . DIRECTORY_SEPARATOR . $entry;

            // Only consider actual directories
            if (! is_dir($entryPath)) {
                continue;
            }

            // Resolve realpath and ensure it's inside the themes folder (safety)
            $realEntryPath = realpath($entryPath);
            if ($realEntryPath === false || ! str_starts_with($realEntryPath, realpath($folder))) {
                continue;
            }

            // Look for theme.json in that directory
            $jsonFile = $realEntryPath . DIRECTORY_SEPARATOR . 'theme.json';

            if (! is_file($jsonFile) || ! is_readable($jsonFile)) {
                continue;
            }

            try {
                $content = File::get($jsonFile);
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $decoded['folder'] = $entry;
                    $themes[$entry] = (object) $decoded;
                }
            } catch (\Throwable $e) {
                // Ignore invalid json or read errors - do not break the scan
                continue;
            }
        }

        return $themes;
    }

    private function installThemes()
    {
        $themes = $this->getThemesFromFolder();

        foreach ($themes as $theme) {
            if (isset($theme->folder)) {
                $theme_exists = Theme::where('folder', '=', $theme->folder)->first();
                $version = $theme->version ?? '';
                if (! isset($theme_exists->id)) {
                    Theme::create(['name' => $theme->name, 'folder' => $theme->folder, 'version' => $version]);
                    if (config('themes.publish_assets', true)) {
                        $this->publishAssets($theme->folder);
                    }
                } else {
                    $theme_exists->name = $theme->name;
                    $theme_exists->version = $version;
                    $theme_exists->save();
                    if (config('themes.publish_assets', true)) {
                        $this->publishAssets($theme->folder);
                    }
                }
            }
        }
    }

    public function activate($theme_folder)
    {
        $theme = Theme::where('folder', '=', $theme_folder)->first();

        if (isset($theme->id)) {
            $this->deactivateThemes();
            $theme->active = 1;
            $theme->save();

            $this->writeThemeJson($theme_folder);

            Notification::make()
                ->title('Successfully activated '.$theme_folder.' theme')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Could not find theme folder. Please confirm this theme has been installed.')
                ->danger()
                ->send();

            return;
        }

        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        $this->refreshThemes();
    }

    private function writeThemeJson($themeName)
    {
        $themeJsonPath = base_path('theme.json');
        $themeJsonContent = json_encode(['name' => $themeName], JSON_PRETTY_PRINT);
        try {
            File::put($themeJsonPath, $themeJsonContent);
        } catch (\Throwable $e) {
            // ignore write errors
        }
    }

    private function deactivateThemes()
    {
        Theme::query()->update(['active' => 0]);
    }

    public function deleteTheme($theme_folder)
    {
        $theme = Theme::where('folder', '=', $theme_folder)->first();
        if (! isset($theme->id)) {
            Notification::make()
                ->title('Theme not found, please make sure this theme exists in the database.')
                ->danger()
                ->send();

            return;
        }

        $theme_location = rtrim(config('themes.folder', resource_path('themes')), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $theme->folder;

        // resolve realpath and ensure it's inside project folder before deleting
        $realPath = realpath($theme_location);
        $base = realpath(base_path());
        if ($realPath && $base && str_starts_with($realPath, $base) && is_dir($realPath)) {
            File::deleteDirectory($realPath, false);
        }

        $theme->delete();

        Notification::make()
            ->title('Theme successfully deleted')
            ->success()
            ->send();

        $this->refreshThemes();
    }

    /**
     * Publish assets helper (tries to copy theme public assets into public/themes/<folder>)
     */
    private function publishAssets($themeFolder)
    {
        $source = rtrim($this->themes_folder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $themeFolder . DIRECTORY_SEPARATOR . 'public';
        $destination = public_path('themes' . DIRECTORY_SEPARATOR . $themeFolder);

        try {
            if (is_dir($source)) {
                if (! is_dir($destination)) {
                    @mkdir($destination, 0755, true);
                }
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::SELF_FIRST
                );
                foreach ($files as $file) {
                    $targetPath = $destination . DIRECTORY_SEPARATOR . $files->getSubPathName();
                    if ($file->isDir()) {
                        @mkdir($targetPath, 0755, true);
                    } else {
                        @copy($file->getRealPath(), $targetPath);
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore publish errors
        }
    }
}