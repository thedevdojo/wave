@props(['title' => '', 'description' => '', 'border' => true])

<div x-data="{ open: false }" class="md:flex md:items-center relative">
    <div class="md:flex-auto @if($border) pb-5 border-b border-gray-200 dark:border-gray-800 @endif space-y-0.5">
        <div class="flex items-center justify-between md:block">
            <div>
                <h3 class="text-base font-semibold text-gray-900">{{ $title }}</h3>
                <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400">{{ $description }}</p>
            </div>

            <!-- Tablet & Mobile Toggle Button (Hidden on md and larger) -->
            @if($slot->isNotEmpty())
                <button @click="open = !open" class="md:hidden text-gray-500 hover:text-gray-700">
                    <svg x-bind:class="{ 'rotate-180': open }" class="w-5 h-5 transition-transform" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    @if($slot->isNotEmpty())
        <!-- Tablet & Desktop: Show inline -->
        <div class="hidden md:block md:mt-0 md:ml-16 md:flex-none">
            {{ $slot }}
        </div>

        <!-- Mobile: Dropdown for Actions -->
        <div x-show="open" @click.away="open = false"
            class="md:hidden mt-2 bg-white border border-gray-200 rounded-lg shadow-md p-3 w-full absolute left-0"
            x-transition>
            {{ $slot }}
        </div>
    @endif
</div>
