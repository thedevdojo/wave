<div class="flex flex-col w-full">
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

    <div class="flex flex-wrap mx-auto my-12 w-full max-w-6xl">

        @foreach(Wave\Plan::all() as $plan)
            @php $features = explode(',', $plan->features); @endphp
            <div class="px-0 mx-auto mb-6 w-full max-w-md lg:w-1/3 lg:px-0 lg:mb-0">
                <div class="flex flex-col mb-10 h-full bg-white rounded-xl border-2  @if($plan->default){{ 'border-zinc-900 scale-105' }}@else{{ 'border-gray-200' }}@endif shadow-sm sm:mb-0">
                    <div class="px-8 pt-8">
                        <span class="px-4 py-1 text-base font-medium text-white rounded-full bg-zinc-900 text-uppercase" data-primary="indigo-700">
                            {{ $plan->name }}
                        </span>
                    </div>

                    <div class="px-8 mt-5">
                        <span class="text-5xl font-bold">${{ $plan->price }}</span>
                        <span class="text-xl font-bold text-gray-500">/mo</span>
                    </div>

                    <div class="px-8 pb-10 mt-3">
                        <p class="text-base leading-7 text-gray-500">{{ $plan->description }}</p>
                    </div>

                    <div class="p-8 mt-auto rounded-b-lg bg-zinc-50">
                        <ul class="flex flex-col">
                            @foreach($features as $feature)
                                <li class="mt-1">
                                    <span class="flex items-center text-green-500">
                                        <svg class="mr-3 w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path></svg>
                                        <span class="text-gray-700">
                                            {{ $feature }}
                                        </span>
                                    </span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-8">
                            @subscribed($plan->slug)
                                <div class="inline-flex justify-center items-center px-4 py-3 w-full text-xs font-semibold tracking-widest text-white uppercase bg-gray-800 rounded-md border border-transparent transition duration-150 ease-in-out cursor-pointer hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25" disabled>
                                    You're subscribed to this plan
                                </div>
                            @notsubscribed
                                @subscriber
                                    <div onclick="switchPlans('{{ $plan->plan_id }}', '{{ $plan->name }}')" class="inline-flex justify-center items-center px-4 py-3 w-full text-xs font-semibold tracking-widest text-white uppercase bg-gray-800 rounded-md border border-transparent transition duration-150 ease-in-out cursor-pointer hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                                        Switch Plans
                                    </div>
                                @notsubscriber
                                    <div data-plan="{{ $plan->plan_id }}" class="inline-flex justify-center items-center px-4 py-3 w-full text-xs font-semibold tracking-widest text-white uppercase bg-gray-800 rounded-md border border-transparent transition duration-150 ease-in-out cursor-pointer hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                                        Get Started
                                    </div>
                                @endsubscriber
                            @endsubscribed

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    @include('theme::partials.switch-plans-modal')

    @if(config('wave.paddle.env') == 'sandbox')
        <div class="mx-auto w-full max-w-6xl">
            <div class="p-10 w-full rounded-xl border border-zinc-200 text-zinc-600">
                <div class="flex items-center pb-4">
                    <div class="flex justify-center items-center mr-3 w-10 h-10 text-white rounded-md bg-zinc-900">
                        <x-phosphor-test-tube-duotone class="w-6 h-6" />
                    </div>
                    
                    <div class="relative">
                        <h2 class="text-sm font-bold text-zinc-700">Testing in Sandbox Mode</h2>
                        <p class="text-xs text-zinc-600">Application billing is in sandbox mode, which means you can test the checkout process using the following credentials:</p>
                    </div>
                </div>
                <div class="pt-1 text-sm font-semibold text-zinc-500">
                    Credit Card Number: <span class="ml-2 font-mono text-black">4242 4242 4242 4242</span>
                </div>
                <div class="pt-1 text-sm font-semibold text-zinc-500">
                    Expiration Date: <span class="ml-2 font-mono text-black">Any future date</span>
                </div>
                <div class="pt-1 text-sm font-semibold text-zinc-500">
                    Security Code: <span class="ml-2 font-mono text-black">Any 3 digits</span>
                </div>
            </div>
        </div>
    @endif
    
    
</div>
