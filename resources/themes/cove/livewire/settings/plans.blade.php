<div>

    {{-- Check whether or not this page is on the settings or the home and pricing page --}}
    @php
        $settingsPage = false;
        if( request()->is('settings/plans') ){
            $settingsPage = true;
        }
    @endphp

    {{-- If we are not on the homepage we show a message at the top of pricing --}}
    @if( $settingsPage )
        @if( auth()->user() && auth()->user()->onTrial() )
            <p class="px-6 py-3 text-sm text-red-500 bg-red-100">You are currently on a trial subscription. Select a plan below to upgrade.</p>
        @elseif(auth()->user() &&  auth()->user()->subscribed('main'))
            <h5 class="px-6 py-5 text-sm font-bold border-t border-b text-zinc-500 bg-zinc-100 border-zinc-150">Switch Plans</h5>
        @else
            <h5 class="px-6 py-5 text-sm font-bold border-t border-b text-zinc-500 bg-zinc-100 border-zinc-150">Select a Plan</h5>
        @endif
    @endif

    <div class="flex flex-wrap w-full mx-auto @if( $settingsPage ) divide-x divide-zinc-100 divide-solid @else max-w-7xl my-12 @endif">
        @foreach(Wave\Plan::all() as $plan)
            @php $features = explode(',', $plan->features); @endphp


            <div class="w-full max-w-md px-0 mx-auto mb-6 lg:w-1/3 lg:mb-0 @if( !$settingsPage ) lg:px-3 @endif">
                <div class="relative flex flex-col h-full mb-10 bg-white sm:mb-0 @if( !$settingsPage ) border border-zinc-200 rounded-xl overflow-hidden shadow-xl border-b-none @endif">
                    <div class="px-10 pt-7">
                        <div class="inline-block absolute right-0 mr-6 transform">
                            <h2 class="relative z-20 w-full h-full px-2 py-1 text-xs font-bold leading-tight tracking-wide text-center uppercase bg-white border-2 @if($plan->default){{ 'border-blue-400 text-blue-500' }}@else{{ 'border-zinc-900 text-zinc-800' }}@endif rounded">{{ $plan->name }}</h2>

                        </div>
                    </div>

                    <div class="px-10 mt-5">
                        <span class="font-mono text-5xl font-bold">${{ $plan->price }}</span>
                        <span class="text-lg font-bold text-zinc-500">per month</span>
                    </div>

                    <div class="px-10 pb-9 mt-6">
                        <p class="text-lg leading-7 text-zinc-500">{{ $plan->description }}</p>
                    </div>

                    <div class="relative px-10 pt-0 pb-12 mt-auto text-zinc-700">

                        <ul class="flex flex-col space-y-2.5">
                            @foreach($features as $feature)
                                <li class="relative">
                                    <span class="flex items-center">
                                        <svg class="mr-3 w-4 h-4 text-green-500 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                        </svg>

                                        <span>
                                            {{ $feature }}
                                        </span>
                                    </span>
                                </li>
                            @endforeach
                        </ul>


                    </div>

                    <div class="relative p-4 pt-0">
                        <div data-plan="{{ $plan->plan_id }}" class="inline-flex items-center justify-center w-full px-4 py-4 text-base font-semibold rounded-lg text-white transition duration-150 ease-in-out @if($plan->default){{ ' bg-gradient-to-r from-blue-600 to-indigo-500 hover:from-blue-500 hover:to-indigo-400' }}@else{{ 'bg-zinc-800 hover:bg-zinc-700 active:bg-zinc-900 focus:border-zinc-900 focus:shadow-outline-gray' }}@endif border border-transparent cursor-pointer checkout focus:outline-none disabled:opacity-25">
                            Get Started
                        </div>
                    </div>

                </div>
            </div>

        @endforeach
    </div>

    @include('theme::partials.switch-plans-modal')

    @if(config('wave.paddle.env') == 'sandbox')
        <div class="mx-auto max-w-7xl">
            <div class="p-10 w-full bg-blue-50 text-zinc-600">
                <div class="flex items-center pb-4">
                    <svg class="mr-2 w-14 h-14 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                    <div class="relative">
                        <h2 class="text-base font-bold text-blue-500">Sandbox Mode</h2>
                        <p class="text-sm text-blue-400">Application billing is in sandbox mode, which means you can test the checkout process using the following credentials:</p>
                    </div>
                </div>
                <div class="pt-2 text-sm font-bold text-zinc-500">
                    Credit Card Number: <span class="ml-2 font-mono text-green-500">4242 4242 4242 4242</span>
                </div>
                <div class="pt-2 text-sm font-bold text-zinc-500">
                    Expiration Date: <span class="ml-2 font-mono text-green-500">Any future date</span>
                </div>
                <div class="pt-2 text-sm font-bold text-zinc-500">
                    Security Code: <span class="ml-2 font-mono text-green-500">Any 3 digits</span>
                </div>
            </div>
        </div>
    @endif
</div>
