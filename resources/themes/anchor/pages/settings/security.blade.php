<?php
    use Filament\Forms\Components\TextInput;
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware, name};
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Schemas\Schema;
    use Filament\Notifications\Notification;
    use Wave\ActivityLog;

    middleware(['auth', 'verified']);
    name('settings.security');

	new class extends Component implements HasForms
	{
        use InteractsWithForms;

        public ?array $data = [];

        public function mount(): void
        {
            $this->form->fill();
        }

        public function form(Schema $schema): Schema
        {
            return $schema
                ->components([
                    TextInput::make('current_password')
                        ->label('Current Password')
                        ->required()
                        ->currentPassword()
                        ->password()
                        ->revealable(),
                    TextInput::make('password')
                        ->label('New Password')
                        ->required()
                        ->minLength(config('wave.auth.min_password_length'))
                        ->password()
                        ->revealable(),
                    TextInput::make('password_confirmation')
                        ->label('Confirm New Password')
                        ->required()
                        ->password()
                        ->revealable()
                        ->same('password')
                    // ...
                ])
                ->statePath('data');
        }

        public function save(): void
        {
            $state = $this->form->getState();
            $this->validate();

            auth()->user()->forceFill([
                'password' => bcrypt($state['password'])
            ])->save();

            // Log the activity
            ActivityLog::log(
                'password_changed',
                'Password was successfully changed'
            );

            $this->form->fill();

            Notification::make()
                ->title('Successfully changed password')
                ->success()
                ->send();
        }

	}

?>

<x-layouts.app>
    @volt('settings.security')
        <div class="relative">
            <x-app.settings-layout
                title="Security"
                description="Update and change your current account password."
            >
                <form wire:submit="save" class="w-full max-w-lg">
                    {{ $this->form }}
                    <div class="w-full pt-6 text-right">
                        <x-button type="submit">Save</x-button>
                    </div>
                </form>

                {{-- The setup screen itself is provided by devdojo/auth --}}
                @if (config('devdojo.auth.settings.enable_2fa'))
                    <div class="w-full max-w-lg pt-8 mt-8 border-t border-zinc-200 dark:border-zinc-800">
                        <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Two-Factor Authentication</h3>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            @if (auth()->user()->two_factor_confirmed_at)
                                Two-factor authentication is enabled for your account.
                            @else
                                Add an extra layer of security by requiring a code from your authenticator app when you log in.
                            @endif
                        </p>
                        <div class="pt-4">
                            <x-button tag="a" :href="route('user.two-factor-authentication')">
                                {{ auth()->user()->two_factor_confirmed_at ? 'Manage two-factor authentication' : 'Set up two-factor authentication' }}
                            </x-button>
                        </div>
                    </div>
                @endif

            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
