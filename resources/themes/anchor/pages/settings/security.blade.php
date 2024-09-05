<?php
    use Filament\Forms\Components\TextInput;
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware, name};
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Notifications\Notification;
    
    middleware('auth');
    name('settings.security');

	new class extends Component implements HasForms
	{
        use InteractsWithForms;

        public ?array $data = [];

        public function mount(): void
        {
            $this->form->fill();
        }

        public function form(Form $form): Form
        {
            return $form
                ->schema([
                    TextInput::make('current_password')
                        ->label('Current Password')
                        ->required()
                        ->currentPassword()
                        ->password()
                        ->revealable(),
                    TextInput::make('password')
                        ->label('New Password')
                        ->required()
                        ->minLength(4)
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

            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
