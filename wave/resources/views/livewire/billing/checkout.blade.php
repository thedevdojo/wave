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
            }
        }" class="flex justify-center items-start w-full h-full rounded-xl">
        <div class="flex flex-col flex-wrap mx-auto w-full lg:max-w-3xl">
            <x-billing.billing_cycle_toggle></x-billing.billing_cycle_toggle>

            <div class="space-y-5 h-full">
                @foreach($plans as $plan)
                    @php $features = explode(',', $plan->features); @endphp
                    <div class="px-0 mx-auto w-full max-w-full group">
                        <div class="flex flex-col mb-10 h-full bg-white rounded-xl ease-out duration-300 border-2 border-gray-200 shadow-sm sm:mb-0 group-hover:border-{{ config('devdojo.billing.style.color') }}-500">
                            <div class="p-6 lg:p-8">
                                <div class="relative">
                                    <span class="text-lg md:text-xl font-semibold rounded-full text-uppercase text-{{ config('devdojo.billing.style.color') }}-600">
                                        {{ $plan->name }} Plan
                                        {{ $billing_cycle_selected }}
                                    </span>
                                </div>

                                <div class="my-3 space-y-2 md:my-5">
                                    <div class="relative">
                                        <span class="text-3xl font-bold lg:text-4xl">$<span x-text="billing_cycle_selected == 'month' ? '{{ $plan->monthly_price }}' : '{{ $plan->yearly_price }}'"></span></span>
                                        <span class="inline-block text-xl font-bold text-gray-500 -translate-y-0.5 lg:text-2xl"><span x-text="billing_cycle_selected == 'month' ? '/mo' : '/yr'"></span></span>
                                    </div>
                                    <p class="text-sm leading-7 text-gray-500 lg:text-base">{{ $plan->description }}</p>
                                </div>

                                <ul class="flex flex-col mt-5">
                                    @foreach($features as $feature)
                                        <li class="mt-1 text-sm">
                                            <span class="flex items-center text-green-500">
                                                <svg class="mr-3 w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path></svg>
                                                <span class="text-gray-600">
                                                    {{ $feature }}
                                                </span>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="px-6 py-5 mt-auto bg-gray-50 rounded-b-xl">
                                <div class="flex justify-end items-center w-full">
                                    <div class="relative w-full md:w-auto">
                                        <x-billing.button wire:click="redirectToPaymentProvider('{{ $plan->id }}')" wire:target="redirectToPaymentProvider" rounded="md" color="{{ config('devdojo.billing.style.color') }}">
                                            Subscribe to this Plan
                                        </x-billing.button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>