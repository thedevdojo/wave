# Cursor Rules for SupaPost

## Coding Standards

- Follow PSR-12 standards for PHP code
- Use camelCase for variable and method names
- Use PascalCase for class names
- Include docblocks for classes and public methods

## Consistent Technology Usage

### Framework & Component Structure
- All pages should use Laravel with Wave framework fundamentals
- Pages requiring server-side state management should use Livewire/Volt components
- Pages should follow the `@volt('name')` pattern for encapsulating logic
- Use `x-layouts.app` and `x-app.container` for consistent page structure

### State Management
- Server-side state should be managed with Livewire/Volt properties and methods
- Session variables should be used for cross-page persistence (like `session()->put()`)
- Client-side temporary state can use Alpine.js with `x-data`
- Prefer Livewire for stateful components that need database interaction

### UI Interactions
- Use `wire:click` for server-side action handlers
- Use `@click` for purely client-side interactions without server needs
- Use `x-show` and similar Alpine directives for simple DOM manipulation
- Use `x-transition` for animations when appropriate

### Component Communication
- Use Livewire events (`$this->dispatch()`) for server-triggered updates
- Use Alpine events (`x-on:eventname.window`) for cross-component client updates
- Use Livewire's `$listeners` array to define component event listeners

## File Structure

**Important: All application pages must be placed in the `resources/views` folder, NOT in the `themes` folder.**

The application follows a standard Laravel structure with these additional guidelines:
- Organize views by feature in the views directory (e.g., dashboard/generator/, dashboard/inspiration/)
- Use appropriate subdirectories to maintain logical organization
- Keep layouts and reusable components in their respective directories

**Image Locations**: 
- All images must be stored in the `resources/images` directory
- Organize into logical subdirectories (e.g., `calendar`, `logos`, `ui/icons`)
- Use SVG format when possible for better scaling and smaller file size
- Reference in templates using Vite: `{{ Vite::asset('resources/images/path/to/image.svg') }}`

## Architecture Guidelines

### Volt Component Standard

**All UI views should use the Livewire Volt component approach**

This approach combines the view and its related logic into a self-contained component:

1. Each blade file should begin with a PHP class that extends `Livewire\Volt\Component`
2. All view-related state and behavior should be encapsulated in this class
3. Controllers should be minimal, primarily passing initial data to the component

See the complete standards in the [cursor-instructions.md](./cursor-instructions.md) file.

### Example
```php
<?php
    use function Laravel\Folio\{middleware};
    use Livewire\Volt\Component;
    
    middleware('auth');

    new class extends Component {
        public $property;
        
        public function mount($initialData)
        {
            $this->property = $initialData;
        }
        
        // Component methods
    }
?>

<x-layouts.app>
    @volt('component-name')
        <!-- Component UI here -->
    @endvolt
</x-layouts.app>
```

## Database Standards

- Use migrations for all database changes
- Include foreign key constraints in migrations
- Use Eloquent relationships appropriately

## Reference Components

- Generator: `resources/views/dashboard/generator/index.blade.php` 