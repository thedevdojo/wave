<div class="relative z-30 pt-4 pb-10 bg-white dark:bg-black sm:py-16">
    <x-app.container>
        <x-marketing.elements.heading
            heading="Pricing plans for any team size"
            description="Find the perfect plan for your team, offering essential features that improve productivity, collaboration, and performance."
            :level="(Request::is('/')) ? 'h2' : 'h1'"
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
            class="w-full mx-auto max-w-7xl" x-cloak>

            @if(has_monthly_yearly_toggle())
                <div class="relative flex items-center justify-start pb-5 sm:justify-center lg:pb-0 sm:-translate-y-2 md:-translate-y-4 lg:-translate-y-7">
                    <div class="relative inline-flex items-center justify-center w-auto p-1 ml-0 text-center -translate-y-3 border border-gray-300 rounded-full sm:mx-auto dark:border-gray-800">
                        <div x-ref="monthly" x-on:click="billing='Monthly'; toggleRepositionMarker($el)" :class="{ 'text-white': billing == 'Monthly' }" class="relative z-20 px-3.5 py-0.5 text-xs font-medium leading-6 text-gray-900 dark:text-gray-300 rounded-full duration-300 ease-out cursor-pointer">
                            Monthly
                        </div>
                        <div x-ref="yearly" x-on:click="billing='Yearly'; toggleRepositionMarker($el)" :class="{ 'text-white': billing == 'Yearly' }" class="relative z-20 px-3.5 py-0.5 text-xs font-medium leading-6 text-gray-900 dark:text-gray-300 rounded-full duration-300 ease-out cursor-pointer">
                            Annually
                        </div>
                        <div x-ref="marker" class="absolute left-0 z-10 w-1/2 h-full opacity-0" x-cloak>
                            <div class="w-full h-full bg-indigo-600 rounded-full shadow-sm"></div>
                        </div>
                    </div>  
                </div>
            @endif

            <div class="flex flex-col justify-center gap-8 mx-auto lg:flex-row sm:max-w-md isolate lg:mx-0 lg:max-w-none">

                @foreach(Wave\Plan::where('active', 1)->get() as $plan)
                    @php $features = explode(',', $plan->features); @endphp
                    <div 
                        {{--  Say that you have a monthly plan that doesn't have a yearly plan, in that case we will hide the place that doesn't have a price_id --}}
                        x-show="(billing == 'Monthly' && '{{ $plan->monthly_price_id }}' != '') || (billing == 'Yearly' && '{{ $plan->yearly_price_id }}' != '')" 
                        class="p-8 rounded-3xl flex-1 ring-1 max-w-lg @if($plan->default){{ 'ring-2 ring-indigo-600 dark:ring-indigo-600' }}@endif ring-gray-200 dark:ring-gray-800 xl:p-10" x-cloak>
                        <div class="flex items-center justify-between gap-x-4">
                            <h3 class="text-lg font-semibold leading-8 @if($plan->default){{ 'text-indigo-600' }}@else{{ 'text-gray-900 dark:text-gray-200' }}@endif">{{ $plan->name }}</h3>
                            @if($plan->default)
                                <p class="px-2.5 py-1 text-xs font-semibold leading-5 text-indigo-600 rounded-full bg-indigo-600/10">Most popular</p>
                            @endif
                        </div>
                        <p class="mt-4 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ $plan->description }}</p>
                        <p class="flex items-baseline mt-6 gap-x-1">
                            <span class="text-4xl font-bold tracking-tight text-gray-900 dark:text-gray-100">$<span x-text="billing == 'Monthly' ? '{{ $plan->monthly_price }}' : '{{ $plan->yearly_price }}'"></span></span>
                            <span class="text-sm font-semibold leading-6 text-gray-600 dark:text-gray-500">/<span x-text="billing == 'Monthly' ? 'month' : 'year'"></span></span>
                        </p>
                        
                        {{-- <x-button size="xl" class="w-full duration-300 ease-out transform-gpu hover:ring-2 hover:ring-offset-2 hover:ring-gray-800 dark:hover:ring-gray-200 dark:ring-offset-gray-900 group">Get Started</x-button> --}}
                        <a href="#" href="/settings/subscription" class="block px-3 py-2 mt-6 text-sm font-semibold leading-6 text-center duration-300 ease-out transform-gpu hover:ring-2 hover:ring-offset-2 @if($plan->default){{ 'text-white bg-indigo-600 hover:ring-indigo-600' }}@else{{ 'text-indigo-600 border border-indigo-200 dark:border-neutral-600 ring-indigo-200 dark:ring-indigo-600 dark:hover:ring-offset-neutral-900 hover:ring-indigo-300' }}@endif rounded-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Get Started</a>
                        <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600 dark:text-gray-400 xl:mt-10">
                            @foreach($features as $feature)
                                <li class="flex gap-x-3"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="flex-none w-5 h-6 text-indigo-600"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"></path></svg> {{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>

                    
                @endforeach
            </div>
        </div>
        <p class="hidden w-full mt-10 mb-5 text-sm text-center text-gray-400 sm:mb-0 sm:block sm:mt-16">All plans are fully configurable in the Admin Area.</p>
        
    </x-app.container>
</div>