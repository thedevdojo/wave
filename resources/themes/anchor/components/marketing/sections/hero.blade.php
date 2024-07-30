<section class="flex relative top-0 justify-center items-center -mt-24 w-full h-screen min-h-screen">
    <div class="flex flex-col justify-between mx-auto w-full h-full">
        {{-- This is the div that will sit at the top taking up the place of the absolute header --}}
        <div class="flex-shrink-0 h-20"></div>
        <div class="grid grid-cols-1 gap-6 items-center px-8 mx-auto max-w-6xl md:px-12 lg:px-[84px] lg:grid-cols-2 lg:gap-24">
            <div class="md:order-first">
                <h1 class="text-7xl font-bold tracking-tighter text-gray-900 text-balance">
                    Your<br>Headline<br> <span >Goes Here</span>
                </h1>
                <p class="mt-5 max-w-sm text-2xl font-normal text-gray-500">
                    Customize this to highlight your product’s unique selling points.
                </p>
                <div class="flex flex-col gap-2 items-center mx-auto mt-8 md:flex-row">
                    <x-button size="lg">Primary Button</x-button>
                    <x-button size="lg" color="secondary">Secondary Button</x-button>
                </div>
            </div>
            <div class="block order-first mt-12 w-full lg:mt-0">
                <img alt="Wave Character" class="relative w-full scale-125" src="/wave/img/character.png" style="max-width:450px;">
            </div>
        </div>
        <div class="py-12 w-full bg-gray-100">
            <div class="grid grid-cols-1 px-8 mx-auto max-w-6xl divide-x md:px-12 lg:px-20 divide-zinc-200 lg:grid-cols-3 text-balance">
                <div class="">
                    <h3 class="flex items-center font-medium text-gray-900">
                        Key Feature
                    </h3>
                    <p class="mt-2 text-sm font-medium text-gray-500">
                        Why might users be interested in your product. Tell them here.
                    </p>
                </div>
                <div class="pl-10">
                    <h3 class="font-medium text-gray-900">Pain Points</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        What are some pain points that your SaaS aims to solve?
                    </p>
                </div>
                <div class="pl-10">
                    <h3 class="font-medium text-gray-900">Unique Advantage</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Explain the unique advantage your product has over others in the market.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>