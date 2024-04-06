@extends('theme::layouts.app')

@section('content')
    
    <section class="relative py-24 w-full bg-white">
        <div class="px-8 py-24 mx-auto max-w-6xl md:px-12 lg:px-20">
            <div class="grid grid-cols-1 gap-6 items-center lg:grid-cols-2 lg:gap-24">
                <div class="md:order-first">
                    <h1 class="text-6xl font-bold tracking-tighter text-gray-900 text-balance">
                        Ship In Days, <br>Not Months
                    </h1>
                    <p class="pr-0 mt-5 text-xl font-normal font-medium text-gray-500">
                       Wave will save you hundreds of hours and allow you to rapidly ship your next great product.
                    </p>
                    <div class="flex flex-col gap-2 items-center mx-auto mt-8 md:flex-row">
                        <x-button>Primary Button</x-button>
                        <x-button color="secondary">Secondary Button</x-button>
                    </div>
                </div>
                <div class="block order-first mt-12 w-full lg:mt-0">
                <img alt="#_" class="relative w-full" src="/wave/img/character.png">
                {{-- <img alt="#_" class="relative w-full" src="https://cdn.devdojo.com/images/april2024/character.jpeg"> --}}
                        {{-- <img alt="#_" class="relative w-full" src="https://cdn.devdojo.com/images/april2024/pirate-character.jpeg"> --}}
                    
                </div>
            </div>
            <dl class="grid grid-cols-1 mt-20 divide-x divide-zinc-200 lg:grid-cols-3 text-balance">
                <div class="">
                    <dt class="flex items-center font-medium text-gray-900">
                        Authentication and Roles
                    </dt>
                    <dd class="mt-2 text-sm font-medium text-gray-500">
                        Login, Register, Forgot Password, and More. Users have roles with permissions.
                    </dd>
                </div>
                <div class="pl-10">
                    <dt class="font-medium text-gray-900">User Dashboard</dt>
                    <dd class="mt-2 text-sm text-gray-500">
                        Dashboard views with user settings, billing page, and more.
                    </dd>
                </div>
                <div class="pl-10">
                    <dt class="font-medium text-gray-900">Billing and Plans</dt>
                    <dd class="mt-2 text-sm text-gray-500">
                        Easily integrate with Paddle, Stripe, or LemonSqueezy and start taking payments.
                    </dd>
                </div>
            </dl>
        </div>
        
    </section>



    <section class="relative">
        <div class="relative top-0 mx-auto w-full max-w-6xl h-px bg-gradient-to-r from-zinc-100 via-zinc-300 to-zinc-100"></div>
        <div class="px-8 py-24 mx-auto max-w-7xl md:px-12 lg:px-32">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tighter lg:text-5xl text-balance">
                    Master the Seas of <br> Product Development
                </h1>
                <p class="mx-auto mt-4 max-w-xl text-base font-medium text-gray-500 text-balance">
                    Harness the power of Wave's extensive features to fast-track your SaaS projects. Get ready to fall in love with the future of SaaS development, today.
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



    <section class="flex relative flex-col justify-center items-center text-center">
        <div class="relative top-0 mx-auto w-full max-w-6xl h-px bg-gradient-to-r from-zinc-100 via-zinc-300 to-zinc-100"></div>
        <div class="py-24">
            <div class="relative w-full text-center">
            <h1 class="text-4xl font-bold tracking-tighter lg:text-5xl text-balance">
                        Oceans of Approval
                    </h1>
                    <p class="mx-auto mt-4 max-w-2xl text-base font-medium text-gray-500 text-balance">
                        Find out why users are on board with Wave, through their own words and success tales.
                    </p>
                </div>
            <div class="px-8 py-12 mx-auto max-w-7xl text-left md:px-12 lg:px-32">
                <ul role="list" class="grid grid-cols-1 gap-12 mx-auto max-w-2xl lg:max-w-none lg:grid-cols-3">
                    <li>
                        <figure class="flex flex-col justify-between h-full">
                            <blockquote class="">
                                <p class="text-base font-medium text-gray-500">
                                    Being in the financial industry, we were always looking for ways
                                    to enhance our transactions' security and efficiency.
                                </p>
                            </blockquote>
                            <figcaption class="flex flex-col justify-between mt-6">
                                <img alt="#_" src="https://pbs.twimg.com/profile_images/1677042510839857154/Kq4tpySA_400x400.jpg" class="object-cover rounded-full grayscale size-14">
                                <div class="mt-4">
                                    <div class="font-medium text-gray-900">Michael Andreuzza</div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        Creator of Windstatic.com
                                    </div>
                                </div>
                            </figcaption>
                        </figure>
                    </li>
                    <li>
                        <figure class="flex flex-col justify-between h-full">
                            <blockquote class="">
                                <p class="text-base font-medium text-gray-500">
                                    Implementing Semplice's blockchain technology has been a
                                    game-changer for our supply chain management.
                                </p>
                            </blockquote>
                            <figcaption class="flex flex-col justify-between mt-6">
                                <img alt="#_" src="https://pbs.twimg.com/profile_images/1748020965995335681/WTNy9HSl_400x400.jpg" class="object-cover rounded-full grayscale size-14">
                                <div class="mt-4">
                                    <div class="font-medium text-gray-900">Gege Piazza</div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        Creator of Pizza Piazza
                                    </div>
                                </div>
                            </figcaption>
                        </figure>
                    </li>
                    <li>
                        <figure class="flex flex-col justify-between h-full">
                            <blockquote class="">
                                <p class="text-base font-medium text-gray-500">
                                    We were initially hesitant about integrating blockchain technology
                                    into our existing systems, fearing the complexity of the process.
                                </p>
                            </blockquote>
                            <figcaption class="flex flex-col justify-between mt-6">
                                <img alt="#_" src="https://pbs.twimg.com/profile_images/1694737709166899200/EQkjv0gi_400x400.jpg" class="object-cover rounded-full grayscale size-14">
                                <div class="mt-4">
                                    <div class="font-medium text-gray-900">Jenson Button</div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        Founder of Benji and Tom
                                    </div>
                                </div>
                            </figcaption>
                        </figure>
                    </li>
                </ul>
            </div>
        </div>
    </section>



    <!-- BEGINNING OF PRICING SECTION -->
    <div id="pricing" class="relative">
        <div class="relative top-0 mx-auto w-full max-w-6xl h-px bg-gradient-to-r from-zinc-100 via-zinc-300 to-zinc-100"></div>
        <div class="relative z-20 px-8 py-24 mx-auto max-w-7xl xl:px-5">
            
        
            <div class="relative w-full text-center">
                <h1 class="text-4xl font-bold tracking-tighter lg:text-5xl text-balance">
                        Chart Your Course
                    </h1>
                    <p class="mx-auto mt-4 max-w-2xl text-base font-medium text-gray-500 text-balance">
                        Set sail and discover the riches of our value-packed plans, meticulously designed to offer you the very best features for less on your SaaS expedition. 
                    </p>
            </div>

            @livewire('wave.settings.plans')

            <p class="my-8 w-full text-left text-zinc-500 sm:my-10 sm:text-center">All plans are fully configurable in the Admin Area.</p>
        </div>
    </div>
    <!-- END OF PRICING SECTION -->



@endsection
