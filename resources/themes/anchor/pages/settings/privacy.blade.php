<?php
    use Filament\Forms\Components\Toggle;
    use Filament\Forms\Components\Radio;
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware, name};
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Schemas\Schema;
    use Filament\Notifications\Notification;
    use App\Models\ActivityLog;
    
    middleware('auth');
    name('settings.privacy');

	new class extends Component implements HasForms
	{
        use InteractsWithForms;

        public ?array $data = [];

        public function mount(): void
        {
            $settings = auth()->user()->privacy_settings ?? $this->getDefaultSettings();
            $this->form->fill($settings);
        }

        public function form(Schema $schema): Schema
        {
            return $schema
                ->components([
                    Radio::make('profile_visibility')
                        ->label('Profile Visibility')
                        ->helperText('Control who can view your profile')
                        ->options([
                            'public' => 'Public - Anyone can view your profile',
                            'private' => 'Private - Only you can view your profile',
                        ])
                        ->default('public')
                        ->inline(false),
                    Toggle::make('show_email')
                        ->label('Show Email on Profile')
                        ->helperText('Display your email address on your public profile')
                        ->default(false),
                    Toggle::make('allow_search_engines')
                        ->label('Allow Search Engine Indexing')
                        ->helperText('Add noindex meta tag to prevent search engines from indexing your profile')
                        ->default(true),
                ])
                ->statePath('data');
        }
        
        public function save(): void
        {
            $state = $this->form->getState();
            $this->validate();

            $oldSettings = auth()->user()->privacy_settings ?? [];
            
            auth()->user()->forceFill([
                'privacy_settings' => $state
            ])->save();

            // Log privacy changes
            $changes = [];
            foreach ($state as $key => $value) {
                if (!isset($oldSettings[$key]) || $oldSettings[$key] !== $value) {
                    $changes[] = $key;
                }
            }
            
            if (!empty($changes)) {
                ActivityLog::log('privacy_updated', 'Privacy settings updated: ' . implode(', ', $changes), [
                    'changed_settings' => $changes
                ]);
            }

            Notification::make()
                ->title('Successfully saved privacy settings')
                ->success()
                ->send();
        }

        private function getDefaultSettings(): array
        {
            return config('privacy.defaults', [
                'profile_visibility' => 'public',
                'show_email' => false,
                'allow_search_engines' => true,
            ]);
        }

	}

?>

<x-layouts.app>
    @volt('settings.privacy') 
        <div class="relative">
            <x-app.settings-layout
                title="Privacy Settings"
                description="Control your privacy and what information is visible to others."
            >
                <form wire:submit="save" class="w-full max-w-lg space-y-6">
                    {{ $this->form }}
                    <div class="w-full pt-6 text-right">
                        <x-button type="submit">Save Settings</x-button>
                    </div>
                </form>

                <!-- Privacy Information -->
                <div class="mt-8 max-w-lg">
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Your Privacy Matters</h4>
                                <p class="mt-1 text-sm text-blue-800 dark:text-blue-200">
                                    These settings help you control your privacy and data. Changes take effect immediately and you can update them at any time.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
