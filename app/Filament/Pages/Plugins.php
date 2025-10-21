<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
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
        $pluginsFolder = resource_path('plugins');

        // Sicherstellen, dass der Plugins-Ordner existiert und ein Verzeichnis ist
        if (! is_dir($pluginsFolder)) {
            // versuche, das Verzeichnis anzulegen
            @mkdir($pluginsFolder, 0755, true);
            return $plugins;
        }

        $items = scandir($pluginsFolder);
        if (! is_array($items)) {
            return $plugins;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $itemPath = $pluginsFolder . DIRECTORY_SEPARATOR . $item;

            // WICHTIG: nur Verzeichnisse als Plugin-Ordner behandeln
            if (! is_dir($itemPath)) {
                continue;
            }

            $studlyFolderName = Str::studly($item);
            $pluginFile = $itemPath . DIRECTORY_SEPARATOR . $studlyFolderName . 'Plugin.php';

            // Falls die erwartete Plugin-Datei existiert, versuchen wir, die Klasse zu benutzen
            if (is_file($pluginFile)) {
                $pluginClass = "Wave\\Plugins\\{$studlyFolderName}\\{$studlyFolderName}Plugin";
                try {
                    if (class_exists($pluginClass) && method_exists($pluginClass, 'getPluginInfo')) {
                        $plugin = new $pluginClass(app());
                        $info = $plugin->getPluginInfo();
                        $info['folder'] = $item;
                        $info['active'] = $this->isPluginActive($item);
                        $plugins[$item] = $info;
                    }
                } catch (\Throwable $e) {
                    // Bei Fehlern im Plugin nicht die gesamte Seite zerstören
                    // Optional: loggen oder $plugins[$item] = ['error' => $e->getMessage()];
                    continue;
                }
            }
        }

        return $plugins;
    }

    private function isPluginActive($folder)
    {
        $installedPlugins = $this->getInstalledPlugins();

        return in_array($folder, $installedPlugins);
    }

    private function getInstalledPlugins()
    {
        $path = resource_path('plugins/installed.json');

        // Nur lesen, wenn es eine Datei ist
        if (is_file($path)) {
            $content = File::get($path);
            $decoded = json_decode($content, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    private function updateInstalledPlugins($plugins)
    {
        $json = json_encode(array_values($plugins));
        file_put_contents(resource_path('plugins/installed.json'), $json);
    }

    public function activate($pluginFolder)
    {
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
            $migrationPath = resource_path("plugins/{$pluginFolder}/database/migrations");
            if (is_dir($migrationPath)) {
                Artisan::call('migrate', [
                    '--path' => "resources/plugins/{$pluginFolder}/database/migrations",
                    '--force' => true,
                ]);
            }
        }
    }

    public function deactivate($pluginFolder)
    {
        $installedPlugins = $this->getInstalledPlugins();
        $installedPlugins = array_diff($installedPlugins, [$pluginFolder]);
        $this->updateInstalledPlugins($installedPlugins);

        Notification::make()
            ->title('Successfully deactivated '.$pluginFolder.' plugin')
            ->success()
            ->send();

        $this->refreshPlugins();
    }

    public function deletePlugin($pluginFolder)
    {
        $this->deactivate($pluginFolder);

        $pluginPath = resource_path('plugins').'/'.$pluginFolder;
        if (is_dir($pluginPath)) {
            File::deleteDirectory($pluginPath);
        }

        Notification::make()
            ->title('Successfully deleted '.$pluginFolder.' plugin')
            ->success()
            ->send();

        $this->refreshPlugins();
    }
}