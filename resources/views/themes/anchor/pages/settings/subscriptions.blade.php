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
                    <x-app.alert id="dashboard_alert">No Active Subscription. <a href="/billing/checkout" class="underline">Click here to subscribe</a>.</x-app.alert>
                    <p class="mt-3">We could not find a current active subscription plan for this account.</p>
                @endnotsubscriber

            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
