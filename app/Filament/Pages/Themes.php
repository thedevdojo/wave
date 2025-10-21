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
            if (is_dir(resource_path('themes/'.$theme->folder))) {
                array_push($this->themes, $theme);
            }
        }
    }

    /**
     * Safely scan theme folders in resources/themes (or configured folder).
     * Only directories are treated as theme candidates.
     */
    private function getThemesFromFolder(): array
    {
        $themes = [];

        $folder = $this->themes_folder;

        if (! is_dir($folder)) {
            // Try to create the directory; if it fails, return empty
            @mkdir($folder, 0755, true);
            return $themes;
        }

        $items = scandir($folder);
        if (! is_array($items)) {
            return $themes;
        }

        foreach ($items as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $entryPath = $folder . DIRECTORY_SEPARATOR . $entry;

            // IMPORTANT: only directories are considered themes
            if (! is_dir($entryPath)) {
                continue;
            }

            // look for a theme.json metadata file
            $jsonFile = $entryPath . DIRECTORY_SEPARATOR . 'theme.json';
            $themeMeta = [
                'name' => $entry,
                'folder' => $entry,
                'version' => null,
            ];

            if (is_file($jsonFile)) {
                try {
                    $content = File::get($jsonFile);
                    $decoded = json_decode($content, true);
                    if (is_array($decoded)) {
                        $themeMeta = array_merge($themeMeta, $decoded);
                    }
                } catch (\Throwable $e) {
                    // ignore invalid json and continue
                    continue;
                }
            }

            $themes[$entry] = (object) $themeMeta;
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

    /**
     * Activate a theme by folder name.
     */
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
        }

        // clear caches and refresh
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        $this->refreshThemes();
    }

    /**
     * Write a simple theme.json in the project root to indicate active theme.
     */
    private function writeThemeJson($themeName)
    {
        $themeJsonPath = base_path('theme.json');

        try {
            $themeJsonContent = json_encode(['name' => $themeName], JSON_PRETTY_PRINT);
            File::put($themeJsonPath, $themeJsonContent);
        } catch (\Throwable $e) {
            // optional: log error, but don't break UI
        }
    }

    private function deactivateThemes()
    {
        Theme::query()->update(['active' => 0]);
    }

    /**
     * Delete a theme folder and DB entry, safe checks included.
     */
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

        // only delete if it's inside the expected folder
        if (is_dir($theme_location) && Str::startsWith(realpath($theme_location), realpath(base_path()))) {
            File::deleteDirectory($theme_location, false);
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
                // ensure destination exists
                if (! is_dir($destination)) {
                    @mkdir($destination, 0755, true);
                }
                // copy files (simple recursive)
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
            // ignore publish errors (do not break activation)
        }
    }
}