<?php

namespace Wave\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Wave\Plugins\PluginManager;

class CreatePluginCommand extends Command
{
    protected $signature = 'plugin:create {name?}';

    protected $description = 'Create a new plugin skeleton';

    public function handle(): int
    {
        $name = $this->argument('name') ?? $this->ask('What is the name of your plugin?');

        // The plugin loaders map the folder to the class name and back,
        // so the class name has to be derived from the folder name.
        $folderName = Str::slug((string) $name);
        $className = Str::studly($folderName);

        if (! preg_match('/^[A-Z][A-Za-z0-9]*$/', $className) || Str::kebab($className) !== $folderName) {
            $this->error("'{$name}' is not a valid plugin name. Use letters, numbers and spaces, and start each word with a letter.");

            return self::FAILURE;
        }

        $pluginPath = PluginManager::path()."/{$folderName}";

        if (File::exists($pluginPath)) {
            $this->error("A plugin with the name '{$folderName}' already exists.");

            return self::FAILURE;
        }

        $description = $this->ask('Provide a short description for your plugin:');

        // Create plugin directory structure
        File::makeDirectory($pluginPath, 0755, true);
        File::makeDirectory("{$pluginPath}/resources/views", 0755, true);
        File::makeDirectory("{$pluginPath}/resources/views/livewire", 0755, true);
        File::makeDirectory("{$pluginPath}/routes", 0755, true);
        File::makeDirectory("{$pluginPath}/src/Components", 0755, true);

        // Create plugin files
        $this->createPluginFile($className, $folderName, $description, $pluginPath);
        $this->createViewFiles($folderName, $pluginPath);
        $this->createRouteFile($folderName, $className, $pluginPath);
        $this->createComponentFile($className, $folderName, $pluginPath);
        $this->createVersionFile($pluginPath);
        $this->downloadPlaceholderImage($pluginPath);

        $this->info("Plugin '{$className}' created successfully!");

        return self::SUCCESS;
    }

    private function createPluginFile($className, $folderName, $description, $path)
    {
        $description = var_export((string) $description, true);

        $content = <<<EOT
<?php

namespace Wave\Plugins\\{$className};

use Livewire\Livewire;
use Wave\Plugins\Plugin;
use Illuminate\Support\Facades\File;

class {$className}Plugin extends Plugin
{
    protected \$name = '{$className}';

    protected \$description = {$description};

    public function register(): void
    {

    }

    public function boot(): void
    {
        \$this->loadViewsFrom(__DIR__ . '/resources/views', '{$folderName}');
        \$this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        Livewire::component('{$folderName}', \\Wave\\Plugins\\{$className}\\Components\\{$className}::class);
    }

    public function getPluginInfo(): array
    {
        return [
            'name' => \$this->name,
            'description' => \$this->description,
            'version' => \$this->getPluginVersion()
        ];
    }

    public function getPluginVersion(): array
    {
        return File::json(__DIR__ . '/version.json');
    }
}
EOT;

        File::put("{$path}/{$className}Plugin.php", $content);
    }

    private function createViewFiles($folderName, $path)
    {
        File::put("{$path}/resources/views/home.blade.php", '<p>Hello World</p>');

        $exampleContent = <<<'EOT'
<div>
    {{ $message }}
</div>
EOT;
        File::put("{$path}/resources/views/livewire/{$folderName}.blade.php", $exampleContent);
    }

    private function createRouteFile($folderName, $className, $path)
    {
        $content = <<<EOT
<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::view('{$folderName}', '{$folderName}::home')->name('{$folderName}');
    Route::get('{$folderName}/component', \\Wave\\Plugins\\{$className}\\Components\\{$className}::class)->name('{$folderName}.component');
});
EOT;

        File::put("{$path}/routes/web.php", $content);
    }

    private function createComponentFile($className, $folderName, $path)
    {
        $content = <<<EOT
<?php

namespace Wave\Plugins\\{$className}\Components;

use Livewire\Component;

class {$className} extends Component
{
    public \$message;

    public function mount(\$category = null)
    {
        \$this->message = 'Hello World';
    }

    public function render()
    {
        \$layout = (auth()->guest()) ? 'theme::components.layouts.marketing' : 'theme::components.layouts.app';
        
        return view('{$folderName}::livewire.{$folderName}')->layout(\$layout);
    }
}
EOT;

        File::put("{$path}/src/Components/{$className}.php", $content);
    }

    private function createVersionFile($path)
    {
        File::put("{$path}/version.json", json_encode(['version' => '1.0.0'], JSON_PRETTY_PRINT));
    }

    private function downloadPlaceholderImage($path)
    {
        $imageUrl = 'https://cdn.devdojo.com/assets/img/plugin-placeholder.jpg';
        $imagePath = "{$path}/plugin.jpg";

        try {
            $response = Http::get($imageUrl)->throw();
            File::put($imagePath, $response->body());
            $this->info('Placeholder image downloaded successfully.');
        } catch (Exception $e) {
            $this->warn('Failed to download placeholder image: '.$e->getMessage());
        }
    }
}
