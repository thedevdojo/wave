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
                @role('admin')
                    <x-app.alert id="no_subscriptions" :dismissable="false" type="info">
                        You are logged in as an admin and have full access. Authenticate with a different user and visit this page to see the subscription checkout process.
                    </x-app.alert>
                @notrole
                    @subscriber
                        <x-app.alert id="no_subscriptions" :dismissable="false" type="success">
                            <x-phosphor-seal-check-duotone class="flex-shrink-0 mr-1.5 -ml-1.5 w-6 h-6" /> 
                            <span>You are currently subscribed to the {{ auth()->user()->plan()->name }} {{ auth()->user()->planInterval() }} Plan.</span>
                        </x-app.alert>
                        <p class="my-4">Manage your subscription by clicking below. Edit this page from the following file:  <x-code-inline>resources/views/{{ $theme->folder }}/pages/settings/subscription.blade.php</x-code-inline></p>
                        @if(config('wave.billing_provider') == 'paddle')
                            <x-button tag="a" color="info" href="{{ auth()->user()->latestSubscription()->update_url }}">Update</x-button>
                            <x-button tag="a" color="danger" href="{{ auth()->user()->latestSubscription()->cancel_url }}">Cancel</x-button>

                        @else
                            <x-button :href="route('stripe.portal')" tag="a">Manage Subscription</x-button>
                        @endif

                    @endsubscriber

                    @notsubscriber
                        <div class="mb-4">
                            <x-app.alert id="no_subscriptions" :dismissable="false" type="info">
                                <x-phosphor-shopping-bag-open-duotone class="flex-shrink-0 mr-1.5 -ml-1.5 w-6 h-6" />
                                <span>No active subscriptions found. Please select a plan below.</span>
                            </x-app.alert>
                        </div>
                        <livewire:billing.checkout />
                        <p class="flex items-center mt-3 mb-4">
                            <x-phosphor-shield-check-duotone class="mr-1 w-4 h-4" />
                            <span class="mr-1">Billing is securely managed via </span><strong>{{ ucfirst(config('wave.billing_provider')) }} Payment Platform</strong>.
                        </p>
                    @endnotsubscriber
                @endrole
            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
