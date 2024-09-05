<div class="relative w-full h-auto">
    @if(config('wave.billing_provider') == 'paddle')
        <script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>
        <script>
            Paddle.Initialize({
                token: '{{ config("wave.paddle.public_key") }}',
                checkout: {
                    settings: {
                        displayMode: "overlay",
                        frameStyle: "width: 100%; min-width: 312px; background-color: transparent; border: none;",
                        locale: "en",
                        allowLogout: false
                    }
                }
            });

            if("{{ config('wave.paddle.env') }}" == 'sandbox') {
                Paddle.Environment.set('sandbox');
            }
        </script>

        @if($error_retrieving_data)
            <div class="relative w-full rounded-lg border border-transparent bg-red-50 p-4 [&>svg]:absolute [&>svg]:text-foreground [&>svg]:left-4 [&>svg]:top-4 [&>svg+div]:translate-y-[-3px] [&:has(svg)]:pl-11 text-red-600">
                <svg class="w-5 h-5 -translate-y-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                <h5 class="mb-1 font-medium tracking-tight leading-none">Payment Provider Error</h5>
                <div class="text-sm opacity-80">Error fetching your subscription data. Please reload or contact support.</div>
            </div>
        @endif

        <div class="flex items-start space-x-2">
            
            <x-filament::modal width="lg" id="update-plan-modal" slide-over>
                <x-slot name="trigger">
                        <x-button x-on:click="setTimeout(function(){ window.dispatchEvent(new CustomEvent('reposition-interval-marker')); }, 10);" color="primary" class="flex-shrink-0">Change My Plan</x-button>
                </x-slot>
                <div class="flex relative flex-col justify-center items-center">
                    <livewire:billing.checkout :change="true" />
                </div>
                {{-- Modal content --}}
            </x-filament::modal>

            <x-button color="primary" href="{{ $update_url }}" tag="a" class="flex-shrink-0">Update Payment Details</x-button>

            @if($cancellation_scheduled)
                <p class="block flex-1 text-red-600">Your subscription will be canceled on {{ \Carbon\Carbon::parse($subscription_ends_at)->format('F jS, Y') }}. To re-activate it, please <button wire:click="cancelImmediately" wire:confirm="This will cancel your subscription immediately, are you sure?" class="underline">cancel immediately</button> and place a new order.
            @else
                <x-filament::modal width="lg" id="cancel-modal">
                    <x-slot name="trigger">
                            <x-button color="danger">Cancel My Subscription</x-button>
                    </x-slot>
                    <div x-data class="flex relative flex-col justify-center items-center">
                        <div class="flex flex-shrink-0 justify-center items-center mx-auto w-12 h-12 bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path></svg>
                        </div>
                        <div class="mt-3 mb-5 text-center">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Cancel Subscription</h3>
                            <div class="mt-2 max-w-xs">
                                <p class="text-sm text-gray-500">Are you sure you want to cancel? <br>You will not be able to re-activate immediately.</p>
                            </div>
                        </div>
                        <div class="flex relative items-center space-x-3 w-full">
                            <x-button x-on:click="$dispatch('close-modal', { id: 'cancel-modal' })" color="secondary" class="w-1/2">No Thanks</x-button> 
                            <x-button wire:click="cancel" color="danger" class="w-1/2">Cancel Subscription</x-button>
                            {{-- <x-button tag="a" href="{{ $cancel_url }}" color="danger" class="w-1/2">Cancel Subscription</x-button> --}}
                        </div>
                    </div>
                    {{-- Modal content --}}
                </x-filament::modal>
            @endif
            
        </div>
    @else
        <x-button :href="route('stripe.portal')" tag="a">Manage Subscription</x-button>
        @if(!is_null($subscription->ends_at))
            <p class="my-3 text-red-600">Your subscription is scheduled to cancel on {{ \Carbon\Carbon::parse($subscription_ends_at)->format('F jS, Y') }}. Click the manage subscription button to re-activate/renew your subscription.
        @endif
    @endif
</div>