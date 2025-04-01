# Cursor Instructions for SupaPost

## File Structure Standards
- All app pages must be placed in the `resources/views` folder, NOT in the `themes` folder
- Follow feature-based organization:
  ```
  resources/
    ├── views/
    │   ├── dashboard/
    │   │   ├── generator/
    │   │   │   └── index.blade.php
    │   │   ├── inspiration/
    │   │   │   └── index.blade.php
    │   │   └── index.blade.php
    │   └── layouts/
    │       └── app.blade.php
  ```

## Image Location Standards
- All images must be stored in the `resources/images` directory
- Organize images in subdirectories by feature (e.g., `calendar`, `generator`)
- For reusable UI elements, use categories like:
  ```
  resources/images/
    ├── ui/
    │   ├── icons/
    │   └── backgrounds/
    ├── calendar/
    │   ├── calendar-page-img.svg
    │   └── date-picker-icon.svg
    └── logos/
       └── chrome-logo.svg
  ```
- Use SVG format when possible for better scaling and smaller file size

## Referencing Images in Blade Files
- Use the Vite asset helper to reference images:
  ```php
  <img src="{{ Vite::asset('resources/images/calendar/calendar-page-img.svg') }}" alt="Calendar illustration">
  ```

- For background images in CSS:
  ```html
  <div style="background-image: url('{{ Vite::asset('resources/images/ui/backgrounds/pattern.svg') }}')">
    <!-- Content -->
  </div>
  ```

- Alternative method using the asset helper (for public images):
  ```php
  <img src="{{ asset('images/calendar/calendar-page-img.svg') }}" alt="Calendar illustration">
  ```

## Component Structure
- All interactive pages should use Livewire Volt components
- Access component variables using the @volt directive:
  ```php
  @volt
  class MyComponent extends \Livewire\Volt\Component
  {
      public $variable = 0;
      
      public function mount($passedVariable)
      {
          $this->variable = $passedVariable;
      }
  }
  @endvolt

  <div>
      <h1>Value: {{ $variable }}</h1>
  </div>
  ```

## Controller Standards
- Controllers should pass variables to Volt components via the mount method
- Example:
  ```php
  return view('dashboard.generator.index', [
      'credits' => $user->credits,
  ]);
  ```

## UI Components
- Use Tailwind CSS for styling
- Follow consistent color scheme and design patterns
- For info boxes and notifications:
  ```php
  <div class="rounded-lg bg-blue-50 dark:bg-blue-900/50 p-4 text-blue-800 dark:text-blue-200 relative mb-4">
      <!-- Content -->
      <button wire:click="dismissInfoBox" class="absolute top-0 right-0 p-2 hover:bg-blue-100 dark:hover:bg-blue-800/50 rounded-tr-lg">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
      </button>
  </div>
  ```
