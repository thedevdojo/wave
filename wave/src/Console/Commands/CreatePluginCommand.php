<?php

namespace Wave\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreatePluginCommand extends Command
{
    protected $signature = 'plugin:create {name?}';

    protected $description = 'Create a new plugin skeleton';

    public function handle()
    {
        $name = $this->argument('name') ?? $this->ask('What is the name of your plugin?');
        $description = $this->ask('Provide a short description for your plugin:');

        $folderName = Str::slug($name);
        $className = Str::studly($name);
        $pluginPath = resource_path("plugins/{$folderName}");

        if (File::exists($pluginPath)) {
            $this->error("A plugin with the name '{$folderName}' already exists.");

            return;
        }

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
    }

    private function createPluginFile($className, $folderName, $description, $path)
    {
        $content = <<<EOT
<?php

namespace Wave\Plugins\\{$className};

use Livewire\Livewire;
use Wave\Plugins\Plugin;
use Illuminate\Support\Facades\File;

class {$className}Plugin extends Plugin
{
    protected \$name = '{$className}';

    protected \$description = '{$description}';

    public function register()
    {
        
    }

    public function boot()
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
        $client = new Client;
        $imageUrl = 'https://cdn.devdojo.com/assets/img/plugin-placeholder.jpg';
        $imagePath = "{$path}/plugin.jpg";

        try {
            $response = $client->get($imageUrl);
            File::put($imagePath, $response->getBody());
            $this->info('Placeholder image downloaded successfully.');
        } catch (\Exception $e) {
            $this->warn('Failed to download placeholder image: '.$e->getMessage());
        }
    }
}
