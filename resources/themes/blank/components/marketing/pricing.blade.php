<section class="py-24">
    <x-marketing.heading
        level="h2"
        title="Pricing plan"
        description="Modify this view from your theme pages/pricing/index.blade.php file." 
    />

    <div class="flex flex-wrap mx-auto my-12 w-full max-w-6xl">
        @foreach(Wave\Plan::all() as $plan)
            @php $features = explode(',', $plan->features); @endphp
            <div class="px-4 mb-8 w-full md:w-1/3 md:mb-0">
                <div class="p-8 rounded-lg border">
                <h3 class="mb-6 text-xl font-semibold">{{ $plan->name }}</h3>
                <div class="mb-6 text-5xl font-bold">${{ $plan->price }}<span class="text-base font-medium">/mo</span></div>
                <ul class="mb-8 text-left">
                    @foreach($features as $feature)
                        <li class="flex items-center mb-2">
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
                <x-button class="w-full">Get started</x-button>
                </div>
            </div>
        @endforeach
    </div>

    <p class="my-8 w-full text-left text-zinc-500 sm:my-10 sm:text-center">All plans are fully configurable in the Admin Area.</p>
</section>