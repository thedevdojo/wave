<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Plugins extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'phosphor-plugs-duotone';

    protected string $view = 'filament.pages.plugins';

    protected static ?int $navigationSort = 9;

    public $plugins = [];

    public function mount()
    {
        $this->refreshPlugins();
    }

    private function refreshPlugins()
    {
        $this->plugins = $this->getPluginsFromFolder();
    }

    private function getPluginsFromFolder()
    {
        $plugins = [];
        $plugins_folder = config('wave.plugins_path', resource_path('plugins'));

        if (! file_exists($plugins_folder)) {
            mkdir($plugins_folder);
        }

        $scandirectory = scandir($plugins_folder);

        foreach ($scandirectory as $folder) {
            if ($folder === '.' || $folder === '..') {
                continue;
            }

            $studlyFolderName = Str::studly($folder);
            $pluginFile = $plugins_folder.'/'.$folder.'/'.$studlyFolderName.'Plugin.php';

            if (file_exists($pluginFile)) {
                $pluginClass = "Wave\\Plugins\\{$studlyFolderName}\\{$studlyFolderName}Plugin";
                if (class_exists($pluginClass) && method_exists($pluginClass, 'getPluginInfo')) {
                    $plugin = new $pluginClass(app());
                    $info = $plugin->getPluginInfo();
                    $info['folder'] = $folder;
                    $info['active'] = $this->isPluginActive($folder);
                    $plugins[$folder] = $info;
                }
            }
        }

        return $plugins;
    }

    private function ensurePluginExists(string $pluginFolder): void
    {
        if (! array_key_exists($pluginFolder, $this->getPluginsFromFolder())) {
            abort(404);
        }
    }

    private function isPluginActive($folder)
    {
        $installedPlugins = $this->getInstalledPlugins();

        return in_array($folder, $installedPlugins);
    }

    private function getInstalledPlugins()
    {
        $path = config('wave.plugins_path', resource_path('plugins')).'/installed.json';

        return File::exists($path) ? File::json($path) : [];
    }

    private function updateInstalledPlugins($plugins)
    {
        $json = json_encode($plugins);
        file_put_contents(config('wave.plugins_path', resource_path('plugins')).'/installed.json', $json);

        Cache::forget('wave_installed_plugins');
    }

    public function activate($pluginFolder)
    {
        $this->ensurePluginExists($pluginFolder);

        $installedPlugins = $this->getInstalledPlugins();
        if (! in_array($pluginFolder, $installedPlugins)) {
            $installedPlugins[] = $pluginFolder;
            $this->updateInstalledPlugins($installedPlugins);

            $this->runPostActivationCommands($pluginFolder);

            Notification::make()
                ->title('Successfully activated '.$pluginFolder.' plugin')
                ->success()
                ->send();
        }

        $this->refreshPlugins();
    }

    private function runPostActivationCommands($pluginFolder)
    {
        $studlyFolderName = Str::studly($pluginFolder);
        $pluginClass = "Wave\\Plugins\\{$studlyFolderName}\\{$studlyFolderName}Plugin";

        if (class_exists($pluginClass)) {
            $plugin = new $pluginClass(app());

            if (method_exists($plugin, 'getPostActivationCommands')) {
                $commands = $plugin->getPostActivationCommands();

                foreach ($commands as $command) {
                    if (is_string($command)) {
                        Artisan::call($command);
                    } elseif (is_callable($command)) {
                        $command();
                    }
                }
            }

            // Run migrations if they exist
            $migrationPath = config('wave.plugins_path', resource_path('plugins'))."/{$pluginFolder}/database/migrations";
            if (File::isDirectory($migrationPath)) {
                Artisan::call('migrate', [
                    '--path' => $migrationPath,
                    '--realpath' => true,
                    '--force' => true,
                ]);
            }
        }
    }

    public function deactivate($pluginFolder)
    {
        $this->ensurePluginExists($pluginFolder);

        $this->removeFromInstalledPlugins($pluginFolder);

        Notification::make()
            ->title('Successfully deactivated '.$pluginFolder.' plugin')
            ->success()
            ->send();

        $this->refreshPlugins();
    }

    public function deletePlugin($pluginFolder)
    {
        $this->ensurePluginExists($pluginFolder);

        $this->removeFromInstalledPlugins($pluginFolder);

        $pluginPath = config('wave.plugins_path', resource_path('plugins')).'/'.$pluginFolder;
        if (file_exists($pluginPath)) {
            File::deleteDirectory($pluginPath);
        }

        Notification::make()
            ->title('Successfully deleted '.$pluginFolder.' plugin')
            ->success()
            ->send();

        $this->refreshPlugins();
    }

    private function removeFromInstalledPlugins(string $pluginFolder): void
    {
        $installedPlugins = array_values(array_diff($this->getInstalledPlugins(), [$pluginFolder]));
        $this->updateInstalledPlugins($installedPlugins);
    }
}
