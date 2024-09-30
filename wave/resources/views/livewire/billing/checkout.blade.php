<section>
    <div x-data="{ 
            billing_cycle_available: @entangle('billing_cycle_available'),
            billing_cycle_selected: @entangle('billing_cycle_selected'),
            toggleButtonClicked(el, month_or_year){
                this.toggleRepositionMarker(el);
                this.billing_cycle_selected = month_or_year;
            },
            toggleRepositionMarker(toggleButton){
                this.$refs.marker.style.width=toggleButton.offsetWidth + 'px';
                this.$refs.marker.style.height=toggleButton.offsetHeight + 'px';
                this.$refs.marker.style.left=toggleButton.offsetLeft + 'px';
            },
            fullScreenLoader: false,
            fullScreenLoaderMessage: 'Loading'
        }"
        @loader-show.window="fullScreenLoader = true"
        @loader-hide.window="fullScreenLoader = false"
        @loader-message.window="fullScreenLoaderMessage = event.detail.message" 
        class="flex items-start justify-center w-full h-full rounded-xl">
        <div class="flex flex-col flex-wrap w-full mx-auto lg:max-w-4xl">
            <x-billing.billing_cycle_toggle></x-billing.billing_cycle_toggle>

            <div class="h-full space-y-5">
                @foreach($plans as $plan)
                    @php $features = explode(',', $plan->features); @endphp
                    <div 
                        {{--  Say that you have a monthly plan that doesn't have a yearly plan, in that case we will hide the place that doesn't have a price_id --}}
                        x-show="(billing_cycle_selected == 'month' && '{{ $plan->monthly_price_id }}' != '') || (billing_cycle_selected == 'year' && '{{ $plan->yearly_price_id }}' != '')" 
                        class="w-full max-w-full px-0 mx-auto group">
                        <div class="flex flex-col mb-10 h-full bg-white dark:bg-neutral-800 rounded-xl ease-out duration-300 border-2 border-gray-200 dark:border-neutral-700 shadow-sm sm:mb-0 group-hover:border-{{ config('devdojo.billing.style.color') }}-500">
                            <div class="p-6 lg:p-8">
                                <div class="relative text-gray-500 dark:text-neutral-400">
                                    <span lass="text-lg md:text-xl font-semibold rounded-full">
                                        {{ $plan->name }} Plan
                                        {{ $billing_cycle_selected }}
                                    </span>
                                </div>

                                <div class="my-3 space-y-2 md:my-5">
                                    <div class="relative">
                                        <span class="text-3xl font-bold lg:text-4xl dark:text-neutral-200">$<span x-text="billing_cycle_selected == 'month' ? '{{ $plan->monthly_price }}' : '{{ $plan->yearly_price }}'"></span></span>
                                        <span class="inline-block text-xl font-bold text-gray-500 dark:text-neutral-200 -translate-y-0.5 lg:text-2xl"><span x-text="billing_cycle_selected == 'month' ? '/mo' : '/yr'"></span></span>
                                    </div>
                                    <p class="text-sm leading-7 text-gray-500 dark:text-neutral-300 lg:text-base">{{ $plan->description }}</p>
                                </div>

                                <ul class="flex flex-col mt-5">
                                    @foreach($features as $feature)
                                        <li class="mt-1 text-sm">
                                            <span class="flex items-center text-green-500">
                                                <svg class="w-4 h-4 mr-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path></svg>
                                                <span class="text-gray-600 dark:text-neutral-400">
                                                    {{ $feature }}
                                                </span>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="px-6 py-5 mt-auto bg-gray-50 dark:bg-neutral-700 rounded-b-xl">
                                <div class="flex items-center justify-end w-full">
                                    <div class="relative w-full md:w-auto">
                                        @if(config('wave.billing_provider') == 'stripe')
                                            <x-billing.button wire:click="redirectToStripeCheckout('{{ $plan->id }}')" wire:target="redirectToPaymentProvider" rounded="md" color="{{ config('devdojo.billing.style.color') }}">
                                                Subscribe to this Plan
                                            </x-billing.button>
                                        @else
                                            @if($change)

                                                <x-filament::modal width="lg" id="change-plan-modal">
                                                    <x-slot name="trigger">
                                                            @if($plan->id == $userPlan->id)
                                                                <x-billing.button rounded="md" color="{{ config('devdojo.billing.style.color') }}" x-show="billing_cycle_selected == '{{ $userSubscription->cycle }}'">You are currently on this plan</x-billing.button>
                                                                <x-billing.button rounded="md" color="{{ config('devdojo.billing.style.color') }}" x-show="billing_cycle_selected != '{{ $userSubscription->cycle }}'">Switch to this plan</x-billing.button>
                                                            @else
                                                                <x-billing.button rounded="md" color="{{ config('devdojo.billing.style.color') }}">Switch to this plan</x-billing.button>
                                                            @endif
                                                    </x-slot>
                                                    <div x-data class="relative flex flex-col items-center justify-center">
                                                        <div 
                                                            @if($plan->id == $userPlan->id)
                                                                x-show="billing_cycle_selected != '{{ $userSubscription->cycle }}'" 
                                                            @endif class="relative flex flex-col items-center justify-center w-full">
                                                            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-blue-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                                                <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><circle cx="128" cy="120" r="40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M63.8,199.37a72,72,0,0,1,128.4,0" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><polyline points="200 128 224 152 248 128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><polyline points="8 128 32 104 56 128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M32,104v24a96,96,0,0,0,174,56" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M224,152V128A96,96,0,0,0,50,72" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                                            </div>
                                                            <div class="mt-3 mb-5 text-center">
                                                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Switch Subscription Plans</h3>
                                                                <div class="max-w-xs mt-2">
                                                                    <p class="text-sm text-gray-500">Are you sure you want to change your current subscription plan?</p>
                                                                </div>
                                                            </div>
                                                            <div class="relative flex items-center w-full space-x-3">
                                                                <x-button x-on:click="$dispatch('close-modal', { id: 'change-plan-modal' })" color="secondary" class="w-1/2">Cancel</x-button> 
                                                                <x-button wire:click="switchPlan('{{ $plan->id }}')" color="info" class="w-1/2">Yes, Switch Plans</x-button>
                                                            </div>
                                                        </div>
                                                        <div 
                                                            x-show="billing_cycle_selected == '{{ $userSubscription->cycle }}' && {{ ($plan->id == $userPlan->id) ? 'true' : 'false' }}"
                                                            class="flex items-center justify-center">
                                                            <p>You are currently on this plan</p>
                                                        </div>
                                                    </div>
                                                    {{-- Modal content --}}
                                                </x-filament::modal>

                                                
                                            @else
                                                <x-billing.button x-on:click="
                                                        if(billing_cycle_selected == 'month'){ openCheckout('{{ $plan->monthly_price_id }}'); }
                                                        if(billing_cycle_selected == 'year'){ openCheckout('{{ $plan->yearly_price_id }}'); }
                                                    " 
                                                    rounded="md" color="{{ config('devdojo.billing.style.color') }}"
                                                >
                                                    Subscribe to this Plan
                                                </x-billing.button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div x-show="fullScreenLoader" class="flex fixed inset-0 justify-center items-center w-screen h-screen z-[999999999]">
            <div class="absolute inset-0 z-10 w-screen h-screen bg-black opacity-50"></div>
            <div class="flex relative z-20 justify-center items-center px-3.5 py-2 bg-black bg-opacity-30 rounded-full">
                <svg class="w-4 h-4 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <p x-text="fullScreenLoaderMessage" class="ml-2 font-medium text-white"></p>
            </div>
        </div>
    </div>
    @if(config('wave.billing_provider') == 'paddle')
        <script>
            window.paddle_public_key = '{{ config("wave.paddle.public_key") }}';

            window.injectPaddleCDN = function(){
                // only inject if the Paddle object is undefined
                if (typeof Paddle == 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://cdn.paddle.com/paddle/v2/paddle.js';
                    document.head.appendChild(script);
                }
            }

            window.whenPaddleIsReady = function(callback){
                let paddleCheckInterval = setInterval(function() {
                    if (typeof Paddle !== 'undefined') {
                        clearInterval(paddleCheckInterval);
                        callback();
                    }
                }, 200);
            }

            window.initialize = function(){
                Paddle.Initialize({
                    token: paddle_public_key,
                    checkout: {
                        settings: {
                            displayMode: "overlay",
                            frameStyle: "width: 100%; min-width: 312px; background-color: transparent; border: none;",
                            locale: "en",
                            allowLogout: false
                        }
                    },
                    eventCallback: function(data) {
                        if (data.name == "checkout.completed") {
                            verifyPaddleTransaction(data.data);
                        }
                    }
                });

                if("{{ config('wave.paddle.env') }}" == 'sandbox') {
                    Paddle.Environment.set('sandbox');
                }
            }

            window.verifyPaddleTransaction = function(data) {
                window.Livewire.dispatch('verifyPaddleTransaction', { transactionId: data.transaction_id });
            }

            window.savePaddleSubscription = function(transactionId){
                Paddle.Checkout.close();
                window.dispatchEvent(new CustomEvent('loader-show'));
                window.dispatchEvent(new CustomEvent('loader-message', { detail: { message: 'Verifying Subscription' }}));
                window.Livewire.dispatch('savePaddleSubscription', { transactionId: transactionId });
            }

            window.closeLoader = function(){
               window.dispatchEvent(new CustomEvent('loader-hide')); 
            }
            
            window.openCheckout = function(priceId) {
                if(paddle_public_key){
                    Paddle.Checkout.open({
                        items: [{
                            priceId: priceId,
                            quantity: 1
                        }],
                        customer: {
                            email: '{{ auth()->user()->email }}'
                        },
                    });
                } else {
                    alert('Paddle API keys and tokens must be set');
                }
            }

            document.addEventListener('livewire:navigated', () => {
                injectPaddleCDN();
                whenPaddleIsReady(function(){
                    initialize();
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                injectPaddleCDN();
                whenPaddleIsReady(function(){
                    
                    initialize();
                });
            });
        </script>
    @endif
</section>