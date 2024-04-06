@extends('theme::layouts.app')

@section('content')
    
    <section class="pt-24 w-full h-screen min-h-screen">
        <div class="px-8 py-24 mx-auto max-w-7xl md:px-12 lg:px-20">
            <div class="grid grid-cols-1 gap-6 items-center lg:grid-cols-2 lg:gap-24">
                <div class="md:order-first">
                    <h1 class="text-7xl font-bold tracking-tighter text-gray-900 text-balance">
                        Ship In Days, Not Months
                    </h1>
                    <p class="pr-20 mt-5 text-xl font-normal text-gray-500">
                       Wave will save you hundreds of hours and allow you to rapidly ship your next great product.
                    </p>
                    <div class="flex flex-col gap-2 items-center mx-auto mt-8 md:flex-row">
                        bcd
                        <x-button>asdf</x-button>
                        <button class="inline-flex gap-3 justify-center items-center px-6 py-4 w-full h-12 text-lg font-medium text-white bg-gray-900 rounded-none duration-200 md:w-auto hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-black" aria-label="Primary action">
                            Primary Button
                        </button>
                        <button class="inline-flex gap-3 justify-center items-center px-6 py-4 w-full h-12 text-lg font-medium bg-gray-100 rounded-none duration-200 md:w-auto hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" aria-label="Secondary action">
                            Secondary Button
                        </button>
                    </div>
                </div>
                <div class="block order-first mt-12 w-full lg:mt-0">
                <img alt="#_" class="relative w-full" src="https://cdn.devdojo.com/images/april2024/character.jpeg">
                        {{-- <img alt="#_" class="relative w-full" src="https://cdn.devdojo.com/images/april2024/pirate-character.jpeg"> --}}
                    
                </div>
            </div>
            <dl class="grid grid-cols-1 gap-6 mt-12 lg:grid-cols-3 text-balance">
                <div>
                    <dt class="font-medium text-gray-900">Authentication and Roles</dt>
                    <dd class="mt-2 text-sm font-medium text-gray-500">
                        The licensor provides the work "as is," and users must use it at their
                        own risk.
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-900">User Dashboard</dt>
                    <dd class="mt-2 text-sm text-gray-500">
                        You can adapt, remix, transform, and build upon the licensed work.
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-900">Billing and Plans</dt>
                    <dd class="mt-2 text-sm text-gray-500">
                        You are allowed to use the licensed work for both non-commercial and
                        commercial purposes.
                    </dd>
                </div>
            </dl>
        </div>
    </section>



    <section>
        <div class="px-8 py-24 mx-auto max-w-7xl md:px-12 lg:px-32">
            <div class="text-center">
                <h1 class="text-4xl font-semibold tracking-tighter text-gray-900 lg:text-5xl text-balance">
                    Building one pagers together,
                    <span class="text-gray-600">wherever and anywhere</span>
                </h1>
                <p class="mt-4 text-base font-medium text-gray-500 text-balance">
                    Control and added security. With decentralization, users have more
                    control over their data and transactions, and the platform is less
                    susceptible to malicious attacks.
                </p>

                <div class="grid grid-cols-2 gap-x-6 gap-y-12 mt-12 text-center lg:mt-16 lg:grid-cols-4 lg:gap-x-8 lg:gap-y-16">
                    <div>
                        <div>
                            <span class="flex justify-center items-center mx-auto bg-gray-100 rounded-full size-12"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="text-gray-600 size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path></svg></span>
                        </div>
                        <div class="mt-6">
                            <h3 class="font-medium text-gray-900">Live editing</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Instantly see the result of every change you make.
                            </p>
                        </div>
                    </div>
                    <div>
                        <div>
                            <span class="flex justify-center items-center mx-auto bg-gray-100 rounded-full size-12"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="text-gray-600 size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path></svg></span>
                        </div>
                        <div class="mt-6">
                            <h3 class="font-medium text-gray-900">Autocompletion</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                spotless will suggest the right classes for you as you type.
                            </p>
                        </div>
                    </div>
                    <div>
                        <div>
                            <span class="flex justify-center items-center mx-auto bg-gray-100 rounded-full size-12"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="text-gray-600 size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></span>
                        </div>
                        <div class="mt-6">
                            <h3 class="font-medium text-gray-900">Hide/show classes</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Hide or show classes to see how your design changes.
                            </p>
                        </div>
                    </div>
                    <div>
                        <div>
                            <span class="flex justify-center items-center mx-auto bg-gray-100 rounded-full size-12"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="text-gray-600 size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008z"></path></svg></span>
                        </div>
                        <div class="mt-6">
                            <h3 class="font-medium text-gray-900">Color preview</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                See the color of every class in the autocompletion view.
                            </p>
                        </div>
                    </div>
                    <div>
                        <div>
                            <span class="flex justify-center items-center mx-auto bg-gray-100 rounded-full size-12"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="text-gray-600 size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59"></path></svg></span>
                        </div>
                        <div class="mt-6">
                            <h3 class="font-medium text-gray-900">Easy navigation</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Hover over any element to see its Tailwind classes. Press Space to
                                easily pin and edit the element.
                            </p>
                        </div>
                    </div>
                    <div>
                        <div>
                            <span class="flex justify-center items-center mx-auto bg-gray-100 rounded-full size-12"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="text-gray-600 size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"></path></svg></span>
                        </div>
                        <div class="mt-6">
                            <h3 class="font-medium text-gray-900">Persistence</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                spotless will remember all your changes to every element so you
                                can copy all changes at once!
                            </p>
                        </div>
                    </div>
                    <div>
                        <div>
                            <span class="flex justify-center items-center mx-auto bg-gray-100 rounded-full size-12"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="text-gray-600 size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"></path></svg></span>
                        </div>
                        <div class="mt-6">
                            <h3 class="font-medium text-gray-900">Screenshot tool</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Make screenshots of a particular part of the screen to share quick
                                and easy!
                            </p>
                        </div>
                    </div>
                    <div>
                        <div>
                            <span class="flex justify-center items-center mx-auto bg-gray-100 rounded-full size-12"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="text-gray-600 size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"></path></svg></span>
                        </div>
                        <div class="mt-6">
                            <h3 class="font-medium text-gray-900">Breakpoint info</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Instantly know what Tailwind breakpoint you're currently on.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


            

    {{-- FEATURES SECTION --}}
    <section class="relative z-40 pt-10 pb-16 w-full bg-gradient-to-b from-blue-500 via-blue-600 to-blue-400 lg:pt-5">

        <div class="absolute top-0 left-0 z-10 w-full h-full opacity-10 transform -translate-x-1/2">
            <svg class="w-full h-full text-white opacity-25 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 205 205"><defs/><g fill="#FFF" fill-rule="evenodd"><path d="M182.63 37c14.521 18.317 22.413 41.087 22.37 64.545C205 158.68 159.1 205 102.486 205c-39.382-.01-75.277-22.79-92.35-58.605C-6.939 110.58-2.172 68.061 22.398 37a105.958 105.958 0 00-9.15 43.352c0 54.239 39.966 98.206 89.265 98.206 49.3 0 89.265-43.973 89.265-98.206A105.958 105.958 0 00182.629 37z"/><path d="M103.11 0A84.144 84.144 0 01150 14.21C117.312-.651 78.806 8.94 56.7 37.45c-22.105 28.51-22.105 68.58 0 97.09 22.106 28.51 60.612 38.101 93.3 23.239-30.384 20.26-70.158 18.753-98.954-3.75-28.797-22.504-40.24-61.021-28.47-95.829C34.346 23.392 66.723.002 103.127.006L103.11 0z"/><path d="M116.479 13c36.655-.004 67.014 28.98 69.375 66.234 2.36 37.253-24.089 69.971-60.44 74.766 29.817-8.654 48.753-38.434 44.308-69.685-4.445-31.25-30.9-54.333-61.904-54.014-31.003.32-56.995 23.944-60.818 55.28v-1.777C46.99 44.714 78.096 13.016 116.479 13z"/></g></svg>
        </div>

        <div class="flex relative z-20 flex-col justify-start items-start px-8 mx-auto max-w-7xl sm:items-center xl:px-5">
            <h2 class="text-4xl font-medium leading-9 text-white">Awesome Features</h2>
            <p class="mt-4 leading-6 text-blue-200 sm:text-center">Wave has some cool features to help you rapidly build your Software as a Service.<br class="hidden md:block"> Here are a few awesome features you're going to love!</p>

            <div class="grid gap-y-10 mt-16 sm:grid-cols-2 sm:gap-x-8 md:gap-x-12 lg:grid-cols-3 xl:grid-cols-4 lg:gap-20">
                @foreach(config('features') as $feature)
                    <div>
                        <img src="{{ $feature->image }}" class="w-16 rounded sm:mx-auto">
                        <h3 class="mt-6 text-sm font-semibold leading-6 text-blue-100 sm:text-center">{{ $feature->title }}</h3>
                        <p class="mt-2 text-sm leading-5 text-blue-200 sm:text-center">{{ $feature->description }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" class="bg-zinc-100" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 1440 156" style="enable-background:new 0 0 1440 126;" xml:space="preserve">
        <style type="text/css">
            .blue-svg{fill:#0069ff;}
            .blue-svg-lighter{fill:#70a2f3}
        </style>
        <g fill-rule="nonzero">
            <path class="blue-svg" d="M694,94.437587 C327,161.381336 194,153.298248 0,143.434189 L2.01616501e-13,44.1765618 L1440,27 L1440,121 C1244,94.437587 999.43006,38.7246898 694,94.437587 Z" id="Shape" fill="#0069FF" opacity="0.519587054"></path>
            <path class="blue-svg" d="M686.868924,95.4364002 C416,151.323752 170.73341,134.021565 1.35713663e-12,119.957876 L0,25.1467017 L1440,8 L1440,107.854321 C1252.11022,92.2972893 1034.37894,23.7359827 686.868924,95.4364002 Z" id="Shape" fill="#0069FF" opacity="0.347991071"></path>
            <path class="blue-svg-lighter" d="M685.6,30.8323303 C418.7,-19.0491687 170.2,1.94304528 0,22.035593 L0,118 L1440,118 L1440,22.035593 C1252.7,44.2273621 1010,91.4098622 685.6,30.8323303 Z" transform="translate(720.000000, 59.000000) scale(1, -1) translate(-720.000000, -59.000000) "></path>
        </g>
    </svg>

    <!-- BEGINNING OF TESTIMONIALS SECTION -->
    <div id="testimonials">
        <div class="flex relative justify-center items-center pt-32 pb-12 bg-zinc-100 md:pb-32 lg:pb-64 min-w-screen">
            <div class="px-10 pb-20 mx-auto max-w-6xl bg-zinc-100">
                <div class="flex flex-col items-center lg:flex-row">
                    <div class="flex flex-col justify-center mb-10 w-full h-full lg:pr-8 sm:w-4/5 md:items-center lg:mb-0 lg:items-start md:w-3/5 lg:w-1/2">
                        <p class="mb-2 text-base font-medium tracking-tight text-blue-500 uppercase">Our customers love our product</p>
                        <h2
                            class="text-4xl font-extrabold tracking-tight leading-10 text-zinc-900 sm:leading-none lg:text-5xl xl:text-6xl">
                            Testimonials</h2>
                        <p class="pr-5 my-6 text-lg text-zinc-600 md:text-center lg:text-left">This is an example section of where you will add your testimonials for your Software as a Service.</p>
                        <a href="#_" class="flex justify-center items-center px-8 py-3 text-base font-medium leading-6 text-white bg-blue-600 rounded-full border border-transparent shadow transition duration-150 ease-in-out hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-wave md:py-4 md:text-lg md:px-10">View Case Studies</a>
                    </div>
                    <div class="w-full sm:w-4/5 lg:w-1/2">
                        <blockquote class="flex flex-row-reverse col-span-1 justify-between items-center p-6 w-full bg-white rounded-xl shadow sm:flex-row">
                            <div class="flex flex-col pl-5 sm:pr-8">
                                <div class="relative sm:pl-12">
                                    <svg class="hidden absolute left-0 w-10 h-10 text-blue-500 fill-current sm:block"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 125">
                                        <path
                                            d="M30.7 42c0 6.1 12.6 7 12.6 22 0 11-7.9 19.2-18.9 19.2C12.7 83.1 5 72.6 5 61.5c0-19.2 18-44.6 29.2-44.6 2.8 0 7.9 2 7.9 5.4S30.7 31.6 30.7 42zM82.4 42c0 6.1 12.6 7 12.6 22 0 11-7.9 19.2-18.9 19.2-11.8 0-19.5-10.5-19.5-21.6 0-19.2 18-44.6 29.2-44.6 2.8 0 7.9 2 7.9 5.4S82.4 31.6 82.4 42z" />
                                    </svg>
                                    <p class="mt-2 text-base text-zinc-600">Wave allowed me to build the Software as a Service of my dreams!
                                    </p>
                                </div>

                                <h3 class="mt-3 text-base font-medium leading-5 truncate text-zinc-800 sm:pl-12">Jane Cooper <span
                                        class="mt-1 text-sm leading-5 truncate text-zinc-500">- CEO SomeCompany</span></h3>
                                <p class="mt-1 text-sm leading-5 truncate text-zinc-500"></p>
                            </div>
                            <img class="flex-shrink-0 w-24 h-24 rounded-full bg-zinc-300"
                                src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60"
                                alt="">
                        </blockquote>
                        <blockquote
                            class="flex flex-row-reverse col-span-1 justify-between items-center p-6 my-5 w-full bg-white rounded-lg shadow sm:flex-row">
                            <div class="flex flex-col pl-5 sm:pr-10">
                                <div class="relative sm:pl-12">
                                    <svg class="hidden absolute left-0 w-10 h-10 text-blue-500 fill-current sm:block"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 125">
                                        <path
                                            d="M30.7 42c0 6.1 12.6 7 12.6 22 0 11-7.9 19.2-18.9 19.2C12.7 83.1 5 72.6 5 61.5c0-19.2 18-44.6 29.2-44.6 2.8 0 7.9 2 7.9 5.4S30.7 31.6 30.7 42zM82.4 42c0 6.1 12.6 7 12.6 22 0 11-7.9 19.2-18.9 19.2-11.8 0-19.5-10.5-19.5-21.6 0-19.2 18-44.6 29.2-44.6 2.8 0 7.9 2 7.9 5.4S82.4 31.6 82.4 42z" />
                                    </svg>
                                    <p class="mt-2 text-base text-zinc-600">Wave saved us hundreds of development hours. Creating a Software as a Service is now easier than ever with Wave.</p>
                                </div>
                                <h3 class="mt-3 text-base font-medium leading-5 truncate text-zinc-800 sm:pl-12">John Doe <span
                                        class="mt-1 text-sm leading-5 truncate text-zinc-500">- CEO SomeCompany</span></h3>
                                <p class="mt-1 text-sm leading-5 truncate text-zinc-500"></p>
                            </div>
                            <img class="flex-shrink-0 w-24 h-24 rounded-full bg-zinc-300"
                                src="https://images.unsplash.com/photo-1527980965255-d3b416303d12?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&aauto=format&fit=facearea&facepad=4&w=256&h=256&q=60"
                                alt="">
                        </blockquote>
                        <blockquote
                            class="flex flex-row-reverse col-span-1 justify-between items-center p-6 w-full bg-white rounded-lg shadow sm:flex-row">
                            <div class="flex flex-col pl-5 sm:pr-10">
                                <div class="relative sm:pl-12">
                                    <svg class="hidden absolute left-0 w-10 h-10 text-blue-500 fill-current sm:block"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 125">
                                        <path
                                            d="M30.7 42c0 6.1 12.6 7 12.6 22 0 11-7.9 19.2-18.9 19.2C12.7 83.1 5 72.6 5 61.5c0-19.2 18-44.6 29.2-44.6 2.8 0 7.9 2 7.9 5.4S30.7 31.6 30.7 42zM82.4 42c0 6.1 12.6 7 12.6 22 0 11-7.9 19.2-18.9 19.2-11.8 0-19.5-10.5-19.5-21.6 0-19.2 18-44.6 29.2-44.6 2.8 0 7.9 2 7.9 5.4S82.4 31.6 82.4 42z" />
                                    </svg>
                                    <p class="mt-2 text-base text-zinc-600">This is the best solution available for creating your own Software as a Service!</p>
                                </div>

                                <h3 class="mt-3 text-base font-medium leading-5 truncate text-zinc-800 sm:pl-12">John Smith <span
                                        class="mt-1 text-sm leading-5 truncate text-zinc-500">- CEO SomeCompany</span></h3>
                                <p class="mt-1 text-sm leading-5 truncate text-zinc-500"></p>
                            </div>
                            <img class="flex-shrink-0 w-24 h-24 rounded-full bg-zinc-300"
                                src="https://images.unsplash.com/photo-1545167622-3a6ac756afa4?ixlib=rrb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&aauto=format&fit=facearea&facepad=4&w=256&h=256&q=60"
                                alt="">
                        </blockquote>
                    </div>
                </div>
            </div>

            <svg version="1.1" id="Layer_3" xmlns="http://www.w3.org/2000/svg" class="absolute bottom-0 w-full" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 1440 126" style="enable-background:new 0 0 1440 126;" xml:space="preserve">
                <style type="text/css">
                    .blue-svg-light {
                        fill: #ffffff;
                    }
                </style>
                <g id="wave" transform="translate(720.000000, 75.000000) scale(1, -1) translate(-720.000000, -75.000000) " fill-rule="nonzero">
                    <path class="blue-svg-light" d="M694,94.437587 C327,161.381336 194,153.298248 0,143.434189 L2.01616501e-13,44.1765618 L1440,27 L1440,121 C1244,94.437587 999.43006,38.7246898 694,94.437587 Z" id="Shape" fill="#0069FF" opacity="0.519587054"></path>
                    <path class="blue-svg-light" d="M686.868924,95.4364002 C416,151.323752 170.73341,134.021565 1.35713663e-12,119.957876 L0,25.1467017 L1440,8 L1440,107.854321 C1252.11022,92.2972893 1034.37894,23.7359827 686.868924,95.4364002 Z" id="Shape" fill="#0069FF" opacity="0.347991071"></path>
                    <path class="blue-svg-light" d="M685.6,30.8323303 C418.7,-19.0491687 170.2,1.94304528 0,22.035593 L0,118 L1440,118 L1440,22.035593 C1252.7,44.2273621 1010,91.4098622 685.6,30.8323303 Z" id="Shape" fill="url(#linearGradient-1)" transform="translate(720.000000, 59.000000) scale(1, -1) translate(-720.000000, -59.000000) "></path>
                </g>
            </svg>

        </div>
    </div>
    <!-- END OF TESTIMONIALS SECTION -->

    <!-- BEGINNING OF PRICING SECTION -->
    <div id="pricing" class="relative">

        <div class="relative z-20 px-8 pb-8 mx-auto max-w-7xl xl:px-5">
            <div class="w-full text-left sm:text-center">
                <h2 class="pt-12 text-4xl font-extrabold text-zinc-900 lg:text-5xl">Example Pricing</h2>
                <p class="my-1 w-full text-base text-left opacity-75 text-zinc-900 sm:my-2 sm:text-center sm:text-xl">It's easy to customize the pricing of your Software as a Service</p>
            </div>

            @livewire('wave.settings.plans')

            <p class="my-8 w-full text-left text-zinc-500 sm:my-10 sm:text-center">All plans are fully configurable in the Admin Area.</p>
        </div>
    </div>
    <!-- END OF PRICING SECTION -->

@endsection
