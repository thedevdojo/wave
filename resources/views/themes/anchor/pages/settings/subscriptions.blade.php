<?php
    
    use Filament\Forms\Components\TextInput;
    use Livewire\Volt\Component;
    use function Laravel\Folio\{name};
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Notifications\Notification;
    
    name('settings.subscriptions');

	new class extends Component
	{
        public function mount(): void
        {
            
        }
    }

?>

<x-layouts.app>
    @volt('settings.subscriptions') 
        <div class="relative">
            <x-app.settings-layout
                title="Subscriptions"
                description="Your subscription details"
            >
                @subscriber
                    <p class="mb-3">Thanks for subscribing to the {{ auth()->user()->plan()->name }} {{ auth()->user()->planInterval() }} Plan.</p>
                    <x-button href="/stripe-customer-portal" tag="a">Manage Your Subscription Here</x-button>
                @endsubscriber

                @notsubscriber
                    <div class="mb-4">
                        <x-app.alert id="no_subscriptions" :dismissable="false" type="info">No active subscriptions found. Please select a plan below.</x-app.alert>
                    </div>
                    <livewire:billing.checkout />
                @endnotsubscriber

                

            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
