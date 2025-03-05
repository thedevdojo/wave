# Cursor Rules for SupaPost

## Coding Standards

- Follow PSR-12 standards for PHP code
- Use camelCase for variable and method names
- Use PascalCase for class names
- Include docblocks for classes and public methods

## File Structure

**Important: All application pages must be placed in the `resources/views` folder, NOT in the `themes` folder.**

The application follows a standard Laravel structure with these additional guidelines:
- Organize views by feature in the views directory (e.g., dashboard/generator/, dashboard/inspiration/)
- Use appropriate subdirectories to maintain logical organization
- Keep layouts and reusable components in their respective directories

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