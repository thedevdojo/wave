<div class="relative flex items-center w-full">
    <div class="relative z-20 px-8 sm:px-16 mx-auto xl:px-5 w-full max-w-7xl">

        <div class="flex flex-col items-center h-full pt-16 sm:pt-28 pb-56 lg:flex-row">

            <div class="flex flex-col items-start w-full mb-16 md:items-center lg:pr-12 lg:items-start lg:w-1/2 lg:mb-0">

                <h2 class="invisible text-sm font-semibold tracking-wide text-gray-700 uppercase transition-none duration-700 ease-out transform translate-y-12 opacity-0 sm:text-base lg:text-sm xl:text-base" data-replace='{ "transition-none": "transition-all", "invisible": "visible", "translate-y-12": "translate-y-0", "scale-110": "scale-100", "opacity-0": "opacity-100" }'>{{ theme('home_headline') }}</h2>
                <h1 class="invisible pb-2 mt-3 text-4xl font-extrabold leading-10 tracking-tight text-transparent transition-none duration-700 ease-out delay-150 transform translate-y-12 opacity-0 bg-clip-text bg-gradient-to-r from-blue-600 via-blue-500 to-purple-600 scale-10 md:my-5 sm:leading-none lg:text-5xl xl:text-6xl" data-replace='{ "transition-none": "transition-all", "invisible": "visible", "translate-y-12": "translate-y-0", "scale-110": "scale-100", "opacity-0": "opacity-100" }'>{{ theme('home_subheadline') }}</h1>
                <p class="invisible max-w-2xl mt-0 text-base text-left text-gray-600 transition-none duration-700 ease-out delay-300 transform translate-y-12 opacity-0 md:text-center lg:text-left sm:mt-2 md:mt-0 sm:text-base lg:text-lg xl:text-xl" data-replace='{ "transition-none": "transition-all", "invisible": "visible", "translate-y-12": "translate-y-0", "scale-110": "scale-100", "opacity-0": "opacity-100" }'>{{ theme('home_description') }}</p>
                <div class="invisible w-full mt-5 transition-none duration-700 ease-out transform translate-y-12 opacity-0 delay-450 sm:mt-8 sm:flex sm:justify-center lg:justify-start sm:w-auto" data-replace='{ "transition-none": "transition-all", "invisible": "visible", "translate-y-12": "translate-y-0", "opacity-0": "opacity-100" }'>
                    <div class="rounded-md">
                        <a href="{{ theme('home_cta_url') }}" class="flex items-center justify-center w-full px-8 py-3 text-base font-medium leading-6 text-white transition duration-150 ease-in-out border border-transparent rounded-md bg-wave-500 hover:bg-wave-600 focus:outline-none focus:border-wave-600 focus:shadow-outline-indigo md:py-4 md:text-lg md:px-10">
                            {{ theme('home_cta') }}
                        </a>
                    </div>
                    <div class="mt-3 sm:mt-0 sm:ml-3">
                        <a href="#" class="flex items-center justify-center w-full px-8 py-3 text-base font-medium leading-6 text-indigo-700 transition duration-150 ease-in-out bg-indigo-100 border-2 border-transparent rounded-md hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-300 md:py-4 md:text-lg md:px-10">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<div class="relative flex items-center z-10 -mt-22 sm:mt-20 xl:-mt-24">
    <div
        class="absolute right-0.5 xl:right-auto xl:inset-x-1/2 invisible transition-none transform translate-x-12 -translate-y-3/7 opacity-0 float-right -z-1"
        data-replace='{ "transition-none": "transition-all duration-1000 delay-100", "invisible": "visible", "translate-x-12": "sm:-translate-x-8 xl:-translate-x-24", "opacity-0": "opacity-100" }'>

        <img src="{{ Voyager::image(theme('home_ship_image')) }}"
            class="w-4/5 mx-auto max-w-xl sm:w-auto lg:max-w-2xl xl:max-w-3xl 2xl:max-w-4xl 4xl:max-w-5xl ship">

    </div>

    <svg viewBox="0 0 120 28" class="-mt-1/2" fill-rule="evenodd">
        <defs>
            {{-- Editted the svg path using https://yqnn.github.io/svg-path-editor/ --}}
            <path id="wave"
                d="M 0 13 C 30 13 30 15 60 15 C 90 15 90 13 120 13 C 150 13 150 15 180 15 C 210 15 210 13 240 13 v 28 h -240 z" />
        </defs>
        <use id="wave3" class="wave" xlink:href="#wave" x="0" y="-0.5"></use>
        <use id="wave2" class="wave" xlink:href="#wave" x="0" y="0"></use>
        <use id="wave1" class="wave" xlink:href="#wave" x="0" y="0.25" />
    </svg>
</div>