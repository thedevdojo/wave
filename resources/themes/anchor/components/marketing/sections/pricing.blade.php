<section>
    <x-marketing.elements.heading
        level="h2"
        title="Chart Your Course"
        description="Set sail and discover the riches of our value-packed plans, meticulously designed to offer you the very best features for less on your SaaS expedition. " 
    />

    <div x-data="{ on: false, billing: '{{ get_default_billing_cycle() }}',
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
        class="w-full max-w-6xl mx-auto mt-12 mb-2 md:my-12" x-cloak>

        @if(has_monthly_yearly_toggle())
            <div class="relative flex items-center justify-start pb-5 -translate-y-2 md:justify-center">
                <div class="relative inline-flex items-center justify-center w-auto p-1 text-center -translate-y-3 border-2 rounded-full md:mx-auto border-zinc-900">
                    <div x-ref="monthly" x-on:click="billing='Monthly'; toggleRepositionMarker($el)" :class="{ 'text-white': billing == 'Monthly', 'text-zinc-900' : billing != 'Monthly' }" class="relative z-20 px-3.5 py-1 text-sm font-medium leading-6 rounded-full duration-300 ease-out cursor-pointer">
                        Monthly
                    </div>
                    <div x-ref="yearly" x-on:click="billing='Yearly'; toggleRepositionMarker($el)" :class="{ 'text-white': billing == 'Yearly', 'text-zinc-900' : billing != 'Yearly' }" class="relative z-20 px-3.5 py-1 text-sm font-medium leading-6 rounded-full duration-300 ease-out cursor-pointer">
                        Yearly
                    </div>
                    <div x-ref="marker" class="absolute left-0 z-10 w-1/2 h-full opacity-0" x-cloak>
                        <div class="w-full h-full rounded-full shadow-sm bg-zinc-900"></div>
                    </div>
                </div>  
            </div>
        @endif

        <div class="flex flex-col flex-wrap lg:flex-row lg:space-x-5">

            @foreach(Wave\Plan::where('active', 1)->get() as $plan)
                @php $features = explode(',', $plan->features); @endphp
                <div
                    {{--  Say that you have a monthly plan that doesn't have a yearly plan, in that case we will hide the place that doesn't have a price_id --}}
                    x-show="(billing == 'Monthly' && '{{ $plan->monthly_price_id }}' != '') || (billing == 'Yearly' && '{{ $plan->yearly_price_id }}' != '')" 
                    class="flex-1 w-full px-0 mx-auto mb-6 md:max-w-lg lg:mb-0" x-cloak>
                    <div class="flex flex-col lg:mb-10 h-full bg-white rounded-xl border-2  @if($plan->default){{ 'border-zinc-900 lg:scale-105' }}@else{{ 'border-zinc-200' }}@endif shadow-sm sm:mb-0">
                        <div class="px-8 pt-8">
                            <span class="px-4 py-1 text-base font-medium text-white rounded-full bg-zinc-900 text-uppercase" data-primary="indigo-700">
                                {{ $plan->name }}
                            </span>
                        </div>

                        <div class="px-8 mt-5">
                            <span class="text-5xl font-bold">$<span x-text="billing == 'Monthly' ? '{{ $plan->monthly_price }}' : '{{ $plan->yearly_price }}'"></span></span>
                            <span class="text-xl font-bold text-zinc-500"><span x-text="billing == 'Monthly' ? '/mo' : '/yr'"></span></span>
                        </div>

                        <div class="px-8 pb-10 mt-3">
                            <p class="text-base leading-7 text-zinc-500">{{ $plan->description }}</p>
                        </div>

                        <div class="p-8 mt-auto rounded-b-lg bg-zinc-50">
                            <ul class="flex flex-col">
                                @foreach($features as $feature)
                                    <li class="mt-1">
                                        <span class="flex items-center text-green-500">
                                            <svg class="w-4 h-4 mr-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path></svg>
                                            <span class="text-zinc-700">
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

    <p class="w-full mt-0 mb-8 text-center text-zinc-500 sm:my-10">All plans are fully configurable in the Admin Area.</p>
</section>