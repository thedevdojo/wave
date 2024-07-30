<?php
    
    use Filament\Forms\Components\TextInput;
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware,name};
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Notifications\Notification;
    
    name('settings.subscription');
    middleware('auth');

	new class extends Component
	{
        public function mount(): void
        {
            
        }
    }

?>

<x-layouts.app>
    @volt('settings.subscription') 
        <div class="relative">
            <x-app.settings-layout
                title="Subscriptions"
                description="Your subscription details"
            >
                @subscriber
                    <x-app.alert id="no_subscriptions" :dismissable="false" type="success">
                        <x-phosphor-seal-check-duotone class="flex-shrink-0 mr-1.5 -ml-1.5 w-6 h-6" /> 
                        <span>You are currently subscribed to the {{ auth()->user()->plan()->name }} {{ auth()->user()->planInterval() }} Plan.</span>
                    </x-app.alert>
                    <p class="my-4">To modify your subscription, update your billing information, or cancel your current subscription, click the "Manage Subscription" button below.</p>
                    <p class="my-4">You can easily modify this message and the page design by editing this file:  <x-code-inline>resources/views/{{ $theme->folder }}/pages/settings/subscription.blade.php</x-code-inline></p>
                    <x-button href="/stripe-customer-portal" tag="a">Manage Subscription</x-button>
                @endsubscriber

                @notsubscriber
                    <div class="mb-4">
                        <x-app.alert id="no_subscriptions" :dismissable="false" type="info">
                            <x-phosphor-shopping-bag-open-duotone class="flex-shrink-0 mr-1.5 -ml-1.5 w-6 h-6" />
                            <span>No active subscriptions found. Please select a plan below.</span>
                        </x-app.alert>
                    </div>
                    <livewire:billing.checkout />
                @endnotsubscriber

                

            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
