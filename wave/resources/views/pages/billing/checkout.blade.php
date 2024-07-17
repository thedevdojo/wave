<?php
    
    use Livewire\Volt\Component;
    use function Laravel\Folio\{name, middleware};
    
    name('billing.checkout');
    middleware('auth');

	new class extends Component
	{
        public function mount(): void
        {
            //dd('cool');
        }
    }

?>

<x-billing.layout>
    @volt('billing.checkout') 
            <div class="flex justify-center items-start w-full h-full rounded-xl">
                
                <div class="flex flex-col flex-wrap mx-auto w-full lg:max-w-3xl">

                    <div class="mb-5">
                        <h1 class="text-base font-semibold leading-7 text-gray-900 sm:text-base">{{ config('devdojo.billing.language.' . Request::segment(2) . '.header') }}</h1>
                        <p class="mt-1 text-xs leading-5 text-gray-500 sm:leading-6 sm:text-sm">{{ config('devdojo.billing.language.' . Request::segment(2) . '.description') }}</p>    
                    </div>

                    <div @class([
                            'flex relative items-start p-3 sm:p-4 mb-5 w-full rounded-lg border border-transparent',
                            'bg-gray-900 text-white' => config('devdojo.billing.style.color') == 'black',
                            'bg-gray-200 text-black' => config('devdojo.billing.style.color') == 'white',
                            'bg-red-500 text-white' => config('devdojo.billing.style.color') == 'red',
                            'bg-green-600 text-white' => config('devdojo.billing.style.color') == 'green',
                            'bg-blue-600 text-white' => config('devdojo.billing.style.color') == 'blue',
                            'bg-yellow-300 text-yellow-800' => config('devdojo.billing.style.color') == 'yellow',
                            'bg-orange-500 text-white' => config('devdojo.billing.style.color') == 'orange',
                            'bg-pink-500 text-white' => config('devdojo.billing.style.color') == 'pink',
                            'bg-purple-600 text-white' => config('devdojo.billing.style.color') == 'purple',
                        ])>
                        <svg class="flex-shrink-0 w-4 h-4 sm:w-5 sm:h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path></svg>
                        <div class="ml-2 text-xs opacity-80 sm:text-sm">{{ config('devdojo.billing.language.' . Request::segment(2) . '.notification') }}</div>
                    </div>

                    <div class="mb-10 space-y-5 h-full">
                        @foreach(Wave\Plan::all() as $plan)
                            @php $features = explode(',', $plan->features); @endphp
                            <div class="px-0 mx-auto w-full max-w-full group">
                                <div @class([
                                        'flex flex-col mb-10 h-full bg-white rounded-xl ease-out duration-300 border-2 border-gray-200 shadow-sm sm:mb-0',
                                        'group-hover:border-gray-900' => config('devdojo.billing.style.color') == 'black',
                                        'group-hover:border-gray-300' => config('devdojo.billing.style.color') == 'white',
                                        'group-hover:border-red-500' => config('devdojo.billing.style.color') == 'red',
                                        'group-hover:border-green-500' => config('devdojo.billing.style.color') == 'green',
                                        'group-hover:border-blue-500' => config('devdojo.billing.style.color') == 'blue',
                                        'group-hover:border-yellow-300' => config('devdojo.billing.style.color') == 'yellow',
                                        'group-hover:border-orange-500' => config('devdojo.billing.style.color') == 'orange',
                                        'group-hover:border-pink-400' => config('devdojo.billing.style.color') == 'pink',
                                        'group-hover:border-purple-500' => config('devdojo.billing.style.color') == 'purple',
                                    ])>
                                    <div class="p-6 lg:p-8">
                                        <div class="relative">
                                            <span @class([
                                                'text-lg md:text-xl font-semibold rounded-full text-uppercase',
                                                'text-gray-900' => config('devdojo.billing.style.color') == 'black',
                                                'text-black' => config('devdojo.billing.style.color') == 'white',
                                                'text-red-500' => config('devdojo.billing.style.color') == 'red',
                                                'text-green-600' => config('devdojo.billing.style.color') == 'green',
                                                'text-blue-600' => config('devdojo.billing.style.color') == 'blue',
                                                'text-yellow-500' => config('devdojo.billing.style.color') == 'yellow',
                                                'text-orange-500' => config('devdojo.billing.style.color') == 'orange',
                                                'text-pink-500' => config('devdojo.billing.style.color') == 'pink',
                                                'text-purple-600' => config('devdojo.billing.style.color') == 'purple',
                                            ])>
                                                {{ $plan->name }} Plan
                                            </span>
                                        </div>

                                        <div class="my-3 space-y-2 md:my-5">
                                            <div class="relative">
                                                <span class="text-3xl font-bold lg:text-4xl">${{ $plan->price }}</span>
                                                <span class="inline-block text-xl font-bold text-gray-500 -translate-y-0.5 lg:text-2xl">/mo</span>
                                            </div>
                                            <p class="text-sm leading-7 text-gray-500 lg:text-base">{{ $plan->description }}</p>
                                        </div>

                                        <ul class="flex flex-col mt-5">
                                            @foreach($features as $feature)
                                                <li class="mt-1 text-sm">
                                                    <span class="flex items-center text-green-500">
                                                        <svg class="mr-3 w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path></svg>
                                                        <span class="text-gray-600">
                                                            {{ $feature }}
                                                        </span>
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    

                                    <div class="px-6 py-5 mt-auto bg-gray-50 rounded-b-xl">

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
                                                <div class="flex justify-end items-center w-full">
                                                    <div class="relative w-full md:w-auto">
                                                        <x-billing.elements.button rounded="md" color="{{ config('devdojo.billing.style.color') }}">
                                                            Subscribe to this Plan
                                                        </x-billing.elements.button>
                                                    </div>
                                                </div>
                                                {{-- <div data-plan="{{ $plan->plan_id }}" class="inline-flex justify-center items-center px-4 py-3 w-full text-xs font-semibold tracking-widest text-white uppercase bg-gray-800 rounded-md border border-transparent transition duration-150 ease-in-out cursor-pointer hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                                                    
                                                </div> --}}
                                            @endsubscriber
                                        @endsubscribed

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
    @endvolt
</x-billing.layout>
