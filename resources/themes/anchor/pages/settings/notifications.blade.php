<?php
    use Filament\Forms\Components\Toggle;
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware, name};
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Schemas\Schema;
    use Filament\Notifications\Notification;
    
    middleware('auth');
    name('settings.notifications');

	new class extends Component implements HasForms
	{
        use InteractsWithForms;

        public ?array $data = [];

        public function mount(): void
        {
            $preferences = auth()->user()->notification_preferences ?? $this->getDefaultPreferences();
            $this->form->fill($preferences);
        }

        public function form(Schema $schema): Schema
        {
            return $schema
                ->components([
                    Toggle::make('email_notifications')
                        ->label('Email Notifications')
                        ->helperText('Receive notifications via email')
                        ->default(true),
                    Toggle::make('marketing_emails')
                        ->label('Marketing Emails')
                        ->helperText('Receive promotional and marketing emails')
                        ->default(true),
                    Toggle::make('product_updates')
                        ->label('Product Updates')
                        ->helperText('Receive updates about new features and improvements')
                        ->default(true),
                    Toggle::make('blog_notifications')
                        ->label('Blog Post Notifications')
                        ->helperText('Get notified when new blog posts are published')
                        ->default(false),
                    Toggle::make('security_alerts')
                        ->label('Security Alerts')
                        ->helperText('Important security notifications (always enabled)')
                        ->default(true)
                        ->disabled(),
                ])
                ->statePath('data');
        }
        
        public function save(): void
        {
            $state = $this->form->getState();
            $this->validate();

            // Security alerts are always enabled
            $state['security_alerts'] = true;

            auth()->user()->forceFill([
                'notification_preferences' => $state
            ])->save();

            Notification::make()
                ->title('Successfully saved notification preferences')
                ->success()
                ->send();
        }

        private function getDefaultPreferences(): array
        {
            return [
                'email_notifications' => true,
                'marketing_emails' => true,
                'product_updates' => true,
                'blog_notifications' => false,
                'security_alerts' => true,
            ];
        }

	}

?>

<x-layouts.app>
    @volt('settings.notifications') 
        <div class="relative">
            <x-app.settings-layout
                title="Notification Preferences"
                description="Manage how you receive notifications and updates."
            >
                <form wire:submit="save" class="w-full max-w-lg space-y-6">
                    {{ $this->form }}
                    <div class="w-full pt-6 text-right">
                        <x-button type="submit">Save Preferences</x-button>
                    </div>
                </form>

            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
