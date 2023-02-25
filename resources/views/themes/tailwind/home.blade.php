@extends('theme::layouts.app')

@section('content')


    <div class="relative flex items-center w-full">
        <div class="relative z-20 px-8 mx-auto xl:px-5 max-w-7xl">

            <div class="flex flex-col items-center h-full pt-16 pb-56 lg:flex-row">

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

                <div class="flex w-full mb-16 lg:w-1/2 lg:mb-0">

                    <div class="relative invisible transition-none duration-1000 delay-100 transform translate-x-12 opacity-0" data-replace='{ "transition-none": "transition-all", "invisible": "visible", "translate-x-12": "translate-y-0", "opacity-0": "opacity-100" }'>
                        <img src="{{ Voyager::image(theme('home_promo_image')) }}" class="w-full max-w-3xl sm:w-auto">
                    </div>

                </div>
            </div>
        </div>



    </div>

    <div class="relative z-40 -mt-64">

        <svg viewBox="0 0 120 28" class="-mt-64">
            <defs>
                <path id="wave" d="M 0,10 C 30,10 30,15 60,15 90,15 90,10 120,10 150,10 150,15 180,15 210,15 210,10 240,10 v 28 h -240 z" />
            </defs>
            <use id="wave3" class="wave" xlink:href="#wave" x="0" y="-2"></use>
            <use id="wave2" class="wave" xlink:href="#wave" x="0" y="0"></use>
            <use id="wave1" class="wave" xlink:href="#wave" x="0" y="1" />
        </svg>
    </div>


    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" class="bg-gray-100" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 1440 156" style="enable-background:new 0 0 1440 126;" xml:space="preserve">
        <style type="text/css">
            .wave-svg{fill:#ff6600;}
            .wave-svg-lighter{fill:#fbfe4c}
        </style>
        <g fill-rule="nonzero">
            <path class="wave-svg" d="M694,94.437587 C327,161.381336 194,153.298248 0,143.434189 L2.01616501e-13,44.1765618 L1440,27 L1440,121 C1244,94.437587 999.43006,38.7246898 694,94.437587 Z" id="Shape" fill="#0069FF" opacity="0.519587054"></path>
            <path class="wave-svg" d="M686.868924,95.4364002 C416,151.323752 170.73341,134.021565 1.35713663e-12,119.957876 L0,25.1467017 L1440,8 L1440,107.854321 C1252.11022,92.2972893 1034.37894,23.7359827 686.868924,95.4364002 Z" id="Shape" fill="#0069FF" opacity="0.347991071"></path>
            <path class="wave-svg-lighter" d="M685.6,30.8323303 C418.7,-19.0491687 170.2,1.94304528 0,22.035593 L0,118 L1440,118 L1440,22.035593 C1252.7,44.2273621 1010,91.4098622 685.6,30.8323303 Z" transform="translate(720.000000, 59.000000) scale(1, -1) translate(-720.000000, -59.000000) "></path>
        </g>
    </svg>
    <!-- BEGINNING OF PRICING SECTION -->
    <div id="pricing" class="relative">

        <div class="relative z-20 px-8 pb-8 mx-auto max-w-7xl xl:px-5">
            <div class="w-full text-left sm:text-center">
                <h2 class="pt-12 text-4xl font-extrabold text-gray-900 lg:text-5xl">Pricing</h2>
                <p class="w-full my-1 text-base text-left text-gray-900 opacity-75 sm:my-2 sm:text-center sm:text-xl">Pricing and billing are currently disabled.</p>
            </div>

            @include('theme::partials.plans')

            <p class="w-full my-8 text-left text-gray-500 sm:my-10 sm:text-center">Beep Boop</p>
        </div>
    </div>
    <!-- END OF PRICING SECTION -->

@endsection
