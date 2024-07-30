<section>
    <x-marketing.elements.heading
        level="h2"
        title="Chart Your Course"
        description="Set sail and discover the riches of our value-packed plans, meticulously designed to offer you the very best features for less on your SaaS expedition. " 
    />

    <div x-data="{ on: false, billing: 'Monthly', basic: {'Monthly' : '19', 'Yearly' : '180'}, pro: {'Monthly' : '49', 'Yearly' : '450' },
            toggleRepositionMarker(toggleButton){
                this.$refs.marker.style.width=toggleButton.offsetWidth + 'px';
                this.$refs.marker.style.height=toggleButton.offsetHeight + 'px';
                this.$refs.marker.style.left=toggleButton.offsetLeft + 'px';
            }
         }" 
        x-init="
                setTimeout(function(){ 
                    toggleRepositionMarker($refs.monthly); 
                    $refs.marker.classList.remove('opacity-0');
                    setTimeout(function(){ 
                        $refs.marker.classList.add('duration-300', 'ease-out');
                    }, 10); 
                }, 1);
        "
        class="mx-auto my-12 w-full max-w-6xl" x-cloak>

        <div class="flex relative justify-center items-center pb-5 -translate-y-2">
            <div class="inline-flex relative justify-center items-center p-1 mx-auto w-auto text-center rounded-full border-2 border-gray-900 -translate-y-3">
                <div x-ref="monthly" x-on:click="billing='Monthly'; toggleRepositionMarker($el)" :class="{ 'text-white': billing == 'Monthly' }" class="relative z-20 px-3.5 py-1 text-sm font-medium leading-6 text-gray-900 rounded-full duration-300 ease-out cursor-pointer">
                    Monthly
                </div>
                <div x-ref="yearly" x-on:click="billing='Yearly'; toggleRepositionMarker($el)" :class="{ 'text-white': billing == 'Yearly' }" class="relative z-20 px-3.5 py-1 text-sm font-medium leading-6 text-gray-900 rounded-full duration-300 ease-out cursor-pointer">
                    Yearly
                </div>
                <div x-ref="marker" class="absolute left-0 z-10 w-1/2 h-full opacity-0" x-cloak>
                    <div class="w-full h-full bg-gray-900 rounded-full shadow-sm"></div>
                </div>
            </div>  
        </div>

        <div class="flex flex-wrap">

            @foreach(Wave\Plan::all() as $plan)
                @php $features = explode(',', $plan->features); @endphp
                <div class="px-0 mx-auto mb-6 w-full max-w-md lg:w-1/3 lg:px-0 lg:mb-0">
                    <div class="flex flex-col mb-10 h-full bg-white rounded-xl border-2  @if($plan->default){{ 'border-gray-900 scale-105' }}@else{{ 'border-gray-200' }}@endif shadow-sm sm:mb-0">
                        <div class="px-8 pt-8">
                            <span class="px-4 py-1 text-base font-medium text-white bg-gray-900 rounded-full text-uppercase" data-primary="indigo-700">
                                {{ $plan->name }}
                            </span>
                        </div>

                        <div class="px-8 mt-5">
                            <span class="text-5xl font-bold">$<span x-text="billing == 'Monthly' ? '{{ $plan->monthly_price }}' : '{{ $plan->yearly_price }}'"></span></span>
                            <span class="text-xl font-bold text-gray-500"><span x-text="billing == 'Monthly' ? '/mo' : '/yr'"></span></span>
                        </div>

                        <div class="px-8 pb-10 mt-3">
                            <p class="text-base leading-7 text-gray-500">{{ $plan->description }}</p>
                        </div>

                        <div class="p-8 mt-auto rounded-b-lg bg-zinc-50">
                            <ul class="flex flex-col">
                                @foreach($features as $feature)
                                    <li class="mt-1">
                                        <span class="flex items-center text-green-500">
                                            <svg class="mr-3 w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path></svg>
                                            <span class="text-gray-700">
                                                {{ $feature }}
                                            </span>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-8">
                                <x-button class="w-full" tag="a" href="/settings/subscription">
                                    Get Started
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <p class="my-8 w-full text-left text-zinc-500 sm:my-10 sm:text-center">All plans are fully configurable in the Admin Area.</p>
</section>