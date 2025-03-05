# Volt Component Standards for SupaPost

## File Structure

### Standard Directory Layout

- **All app pages must be placed in the `resources/views` folder, NOT in the `themes` folder**
- All blade templates should follow a logical organization within the views folder
- Pages should be organized by feature or section (e.g., dashboard, admin, etc.)

For example:
```
resources/
  └── views/
      ├── dashboard/
      │   ├── generator/
      │   │   └── index.blade.php
      │   ├── inspiration/
      │   │   └── index.blade.php
      │   └── index.blade.php
      ├── layouts/
      │   └── app.blade.php
      └── components/
          └── ...
```

## Component Structure

All new pages and components should follow the Livewire Volt component pattern with the following standards:

### 1. Basic Structure

```php
<?php
    use function Laravel\Folio\{middleware};
    use Livewire\Volt\Component;
    use Illuminate\Support\Facades\Auth;
    // Import other required models and classes
    
    middleware('auth');  // Or other required middleware

    new class extends Component {
        // Public properties that will be accessible in the view
        public $property1;
        public $property2 = []; // Initialize arrays as empty arrays
        
        // Mount method handles initialization (similar to constructor)
        public function mount($param1 = null, $param2 = null)
        {
            // Set initial values, typically from controller parameters
            $this->property1 = $param1;
            // Any other initialization logic
            $this->loadData();
        }
        
        // Methods for loading data
        public function loadData()
        {
            // Load data from database, API, etc.
            // Typically populates the public properties
        }
        
        // Action methods (triggered by user interactions)
        public function performAction($param = null)
        {
            // Handle user actions like form submissions, button clicks, etc.
        }
    }
?>

<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        @volt('component-name')
            <!-- Component template here -->
            <div>
                <!-- Access properties directly with $property1 -->
                <!-- Trigger methods with wire:click="methodName" -->
            </div>
        @endvolt
    </div>
</x-layouts.app>
```

### 2. Controller Responsibilities

Controllers should:
- Pass initial data to the view
- Be kept simple, primarily providing the initial data for the component
- Delegate most business logic to the Volt component or dedicated service classes

Example:
```php
public function index()
{
    $data1 = $this->fetchDataFromService();
    $data2 = Model::get();
    
    return view('path.to.view', [
        'data1' => $data1,
        'data2' => $data2,
    ]);
}
```

### 3. Component Communication

- Use dispatch/listen for parent-child communication
- Use events for communication between unrelated components

### 4. Form Handling

For forms, use Laravel's form request validation in the Volt component:

```php
use function Livewire\Volt\{state, rules};

state(['email' => '', 'name' => '']);

rules(['email' => 'required|email', 'name' => 'required|min:3']);

$submit = function() {
    $this->validate();
    // Process the form...
};
```

### 5. Handling Pagination and Complex Objects

Livewire has limitations on what property types it can serialize. For paginated collections or complex objects:

```php
// In the Volt component class
public $itemsList = []; // Will store the actual items
public $paginationInfo = []; // Will store pagination metadata

public function mount($paginatedCollection = null)
{
    if ($paginatedCollection) {
        // Extract only what we need from the paginated collection
        $this->itemsList = $paginatedCollection->items();
        $this->paginationInfo = [
            'current_page' => $paginatedCollection->currentPage(),
            'last_page' => $paginatedCollection->lastPage(),
            'per_page' => $paginatedCollection->perPage(),
            'total' => $paginatedCollection->total(),
        ];
    }
}
```

Then in the template, use:
```blade
@foreach($itemsList as $item)
    <!-- Item rendering -->
@endforeach

<!-- Pagination controls -->
@if($paginationInfo['last_page'] > 1)
    <div class="pagination">
        <!-- Pagination UI -->
    </div>
@endif
```

### 6. Using Filament Forms with Volt Components

When integrating Filament Forms with Volt components:

```php
<?php
    use Livewire\Volt\Component;
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    
    new class extends Component implements HasForms {
        use InteractsWithForms;
        
        // Required for Filament Forms
        public ?array $data = [];
        
        // The form property must be defined
        public $form;
        
        public function boot(): void
        {
            $this->form = $this->makeForm()
                ->schema([
                    TextInput::make('field_name')
                        ->label('Field Label')
                        ->required(),
                    // Other form components...
                ])
                ->statePath('data');
        }
        
        public function mount(): void
        {
            $this->form->fill();
        }
        
        public function submit()
        {
            $data = $this->form->getState();
            // Process the form data
        }
    }
?>

<x-layouts.app>
    @volt('component-name')
        <form wire:submit="submit">
            {{ $this->form }}
            
            <button type="submit">
                Submit
            </button>
        </form>
    @endvolt
</x-layouts.app>
```

Important points:
- Implement `HasForms` interface and use `InteractsWithForms` trait
- Define a `$form` property (this is required)
- Define `$data` for storing form state
- Use `boot()` to initialize the form schema
- Use `$this->form` in templates to render the form

## Converting Existing Pages

When converting non-Volt pages to Volt components:

1. Move controller logic to the Volt component class
2. Ensure all required variables are properties in the class
3. Use the mount method to accept initial data from the controller
4. Replace page-reload actions with reactive Livewire methods

## Example Components

Refer to existing Volt components for reference:
- `resources/views/dashboard/generator/index.blade.php` 