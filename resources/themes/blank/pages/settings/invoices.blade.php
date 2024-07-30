<?php
    
    use Filament\Forms\Components\TextInput;
    use Livewire\Volt\Component;
    use function Laravel\Folio\{name};
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Notifications\Notification;
    
    name('settings.invoices');

	new class extends Component
	{
        public function mount(): void
        {
            
        }
    }

?>

<x-layouts.app>
    @volt('settings.invoices') 
        <div class="relative">
            <x-app.settings-layout
                title="Invoices"
                description="Your past plan invoices"
            >
                Invoices Content here

            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
