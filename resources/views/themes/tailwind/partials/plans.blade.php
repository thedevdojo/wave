
<div class="flex flex-wrap mx-auto mt-12 max-w-7xl">
    @foreach(Wave\Plan::all() as $plan)
        @php $features = explode(',', $plan->features); @endphp

        <div class="w-full max-w-md px-0 mx-auto mb-6 lg:w-1/3 lg:px-3 lg:mb-0">
            <div class="relative flex flex-col h-full mb-10 bg-white border-2 border-gray-200 rounded-lg shadow-sm sm:mb-0">
                <div class="px-10 pt-10">
                    <span class="px-4 py-1 text-base font-medium text-indigo-700 bg-indigo-100 rounded-full text-uppercase">
                        {{ $plan->name }}
                    </span>
                </div>

                <div class="px-10 mt-5">
                    <span class="text-5xl font-bold">${{ $plan->price }}</span>
                    <span class="text-xl font-bold text-gray-500">/mo</span>
                </div>

                <div class="px-10 pb-10 mt-3">
                    <p class="text-lg leading-7 text-gray-500">{{ $plan->description }}</p>
                </div>

                <div class="relative p-10 mt-auto text-gray-700 bg-gray-100 rounded-b-lg">
                    @if($plan->default)
                        <div class="absolute top-0 left-0 flex items-center inline-block px-3 py-1.5 -ml-0.5 -mt-9 text-xs text-white uppercase bg-gradient-to-r from-blue-500 bg-indigo-500 rounded-r">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            Popular
                        </div>
                    @endif
                    <ul class="flex flex-col">
                        @foreach($features as $feature)
                            <li class="mt-1">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-3 text-green-500 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                    </svg>

                                    <span>
                                        {{ $feature }}
                                    </span>
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8">
                        <div data-plan="{{ $plan->plan_id }}" class="inline-flex items-center justify-center w-full px-4 py-3 text-base font-semibold text-white transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md cursor-pointer checkout hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                            @subscribed($plan->slug)
                                Your subscribed to this plan
                            @notsubscribed
                                @subscriber
                                    Switch Plans
                                @notsubscriber
                                    Get Started
                                @endsubscriber
                            @endsubscribed
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endforeach
</div>

@if(config('wave.paddle.env') == 'sandbox')
    <div class="px-2 mx-auto mt-12 max-w-7xl">
        <div class="w-full p-10 text-gray-600 bg-blue-50 rounded-xl">
            <div class="flex items-center pb-4">
                <svg class="mr-2 w-14 h-14 text-wave-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                <div class="relative">
                    <h2 class="text-base font-bold text-wave-500">Sandbox Mode</h2>
                    <p class="text-sm text-blue-400">Application billing is in sandbox mode, which means you can test the checkout process using the following credentials:</p>
                </div>
            </div>
            <div class="pt-2 text-sm font-bold text-gray-500">
                Credit Card Number: <span class="ml-2 font-mono text-green-500">4242 4242 4242 4242</span>
            </div>
            <div class="pt-2 text-sm font-bold text-gray-500">
                Expiration Date: <span class="ml-2 font-mono text-green-500">Any future date</span>
            </div>
            <div class="pt-2 text-sm font-bold text-gray-500">
                Security Code: <span class="ml-2 font-mono text-green-500">Any 3 digits</span>
            </div>
        </div>
    </div>
@endif
