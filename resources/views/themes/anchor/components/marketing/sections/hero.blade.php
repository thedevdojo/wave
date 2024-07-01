<section class="flex relative justify-center items-center @if(config('wave.demo') && Request::is('/')){{ 'translate-y-12' }}@endif -mt-24 w-full h-screen min-h-screen bg-white">
    <div class="px-8 mx-auto max-w-6xl md:px-12 lg:px-20">
        <div class="grid grid-cols-1 gap-6 items-center lg:grid-cols-2 lg:gap-24">
            <div class="md:order-first">
                <h1 class="text-6xl font-bold tracking-tighter text-gray-900 text-balance">
                    Ship In Days, <br>Not Months
                </h1>
                <p class="pr-0 mt-5 text-xl font-normal text-gray-500">
                    Wave will save you hundreds of hours and allow you to rapidly ship your next great product.
                </p>
                <div class="flex flex-col gap-2 items-center mx-auto mt-8 md:flex-row">
                    <x-button size="lg">Primary Button</x-button>
                    <x-button size="lg" color="secondary">Secondary Button</x-button>
                </div>
            </div>
            <div class="block order-first mt-12 w-full lg:mt-0">
                <img alt="Wave Character" class="relative w-full" src="/wave/img/character.png" style="max-width:450px;">
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