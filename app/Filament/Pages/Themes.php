<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Wave\Theme;

class Themes extends Page
{
    public $themes = [];

    private $themes_folder = '';

    protected static ?string $navigationIcon = 'phosphor-paint-bucket-duotone';

    protected static ?int $navigationSort = 8;

    protected static string $view = 'filament.pages.themes';

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
            if (file_exists(resource_path('themes/'.$theme->folder))) {
                array_push($this->themes, $theme);
            }
        }
    }

    private function getThemesFromFolder()
    {
        $themes = [];

        if (! file_exists($this->themes_folder)) {
            mkdir($this->themes_folder);
        }

        $scandirectory = scandir($this->themes_folder);

        if (isset($scandirectory)) {

            foreach ($scandirectory as $folder) {
                // dd($theme_folder . '/' . $folder . '/' . $folder . '.json');
                $json_file = $this->themes_folder.'/'.$folder.'/theme.json';
                if (file_exists($json_file)) {
                    $themes[$folder] = json_decode(file_get_contents($json_file), true);
                    $themes[$folder]['folder'] = $folder;
                    $themes[$folder] = (object) $themes[$folder];
                }
            }

        }

        return (object) $themes;
    }

    private function installThemes()
    {

        $themes = $this->getThemesFromFolder();

        foreach ($themes as $theme) {
            if (isset($theme->folder)) {
                $theme_exists = Theme::where('folder', '=', $theme->folder)->first();
                // If the theme does not exist in the database, then update it.
                if (! isset($theme_exists->id)) {
                    $version = isset($theme->version) ? $theme->version : '';
                    Theme::create(['name' => $theme->name, 'folder' => $theme->folder, 'version' => $version]);
                    if (config('themes.publish_assets', true)) {
                        $this->publishAssets($theme->folder);
                    }
                } else {
                    // If it does exist, let's make sure it's been updated
                    $theme_exists->name = $theme->name;
                    $theme_exists->version = isset($theme->version) ? $theme->version : '';
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
        }

        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');

        $this->refreshThemes();

    }

    private function writeThemeJson($themeName)
    {
        $themeJsonPath = base_path('theme.json');
        $themeJsonContent = json_encode(['name' => $themeName], JSON_PRETTY_PRINT);
        File::put($themeJsonPath, $themeJsonContent);
    }

    private function deactivateThemes()
    {
        Theme::query()->update(['active' => 0]);
    }

    public function deleteTheme($theme_folder)
    {
        $theme = Theme::where('folder', '=', $theme_folder)->first();
        if (! isset($theme)) {
            Notification::make()
                ->title('Theme not found, please make sure this theme exists in the database.')
                ->danger()
                ->send();
        }

        $theme_name = $theme->name;

        $theme_location = config('themes.folder').'/'.$theme->folder;

        // if the folder exists delete it
        if (file_exists($theme_location)) {
            File::deleteDirectory($theme_location, false);
        }

        $theme->delete();

        Notification::make()
            ->title('Theme successfully deleted')
            ->success()
            ->send();

        $this->refreshThemes();
    }
}
