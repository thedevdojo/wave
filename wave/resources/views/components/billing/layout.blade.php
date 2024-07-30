<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <link rel="stylesheet" href="/billing/main.css"> --}}
</head>
<body>
    <div class="flex flex-col items-stretch w-screen h-screen bg-gradient-to-br from-gray-50 to-gray-100 lg:flex-row">
        <div class="flex justify-end w-full h-auto bg-white lg:flex-1 lg:h-full lg:max-w-sm xl:max-w-md item-stretch">
            <div class="h-auto lg:h-full rounded-2xl p-5 sm:p-8 md:p-10 lg:p-8 lg:pr-20 w-full lg:w-[400px] flex flex-row lg:flex-col items-center lg:items-start justify-between lg:justify-center">
                <a href="{{ route('home') }}">
                    <x-logo class="w-auto scale-90 md:scale-95 xl:h-scale-100" style="height: {{ config('devdojo.billing.style.logo_height') }}px"></x-logo>
                </a>
                <div class="hidden py-3 lg:block lg:py-5">
                    <h2 class="text-sm font-bold lg:text-base">Billing Checkout</h2>
                    <div class="py-3 space-y-1.5 text-sm text-gray-500">
                        <p>Signed in as {{ auth()->user()->name }}</p>
                        <p>Managing for {{ auth()->user()->name }}</p>
                    </div>
                    <p class="hidden pt-3 text-sm text-gray-500 md:block lg:pt-0">{{ config('devdojo.billing.language.' . Request::segment(2) . '.sidebar_description') }}</p>
                </div>
                <a href="/settings/subscriptions" class="flex relative items-center px-3 py-2.5 pl-7 font-bold text-gray-600 rounded-md opacity-90 sm:pl-8 sm:px-4 sm:py-3 bg-gray-200/80 hover:opacity-100 hover:text-gray-800 group lg:w-auto lg:items-center lg:justify-center">
                    <span class="overflow-hidden absolute left-0 w-4 h-4 transition duration-150 ease-out transform translate-x-3.5 translate-y-px sm:translate-x-4 group-hover:translate-x-3 sm:group-hover:translate-x-3.5 group-hover:w-4">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                    </span>
                    <span class="mx-auto ml-1 text-xs leading-none select-none">Return Back</span>
                </a>
            </div>
        </div>
        <div class="overflow-y-scroll relative flex-1 p-5 w-full h-full sm:p-8 md:p-10">
        {{ $slot }}
        </div>
    </div>
    <script src="/billing/main.js"></script>
</body>
</html>