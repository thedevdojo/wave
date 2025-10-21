<div class="overflow-hidden bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center p-6 border-b dark:border-white/5 gap-x-4 bg-gray-50 dark:bg-gray-900 border-gray-900/5">
        <div class="inline-flex items-center justify-center w-10 h-10 text-4xl leading-none text-white bg-black rounded-lg aspect-square">
            <span class="-translate-y-[3px] translate-x-px">»</span>
        </div>
        <div class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">{{ $title }}</div>
        <div x-data="{ open: false }" class="relative ml-auto">
            <button x-on:click="open=!open" type="button" class="block p-2.5 -m-2.5 text-gray-400 hover:text-gray-500" id="options-menu-0-button" x-ref="button">
                <span class="sr-only">Open options</span>
                <x-phosphor-dots-three class="w-5 h-5" />
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 z-10 py-2 mt-0.5 w-32 bg-white dark:bg-gray-800 rounded-md ring-1 shadow-lg origin-top-right dark:ring-white/10 ring-gray-900/5 focus:outline-none" x-cloak>
                <a href="#" class="block px-3 py-1 text-sm leading-6 text-gray-900 dark:text-gray-200">View</a>
                <a href="#" class="block px-3 py-1 text-sm leading-6 text-gray-900 dark:text-gray-200">Edit</a>
            </div>
        </div>
    </div>
    <dl class="px-6 py-4 -my-3 text-sm leading-6 divide-y divide-gray-100 dark:divide-gray-700">
        <div class="flex justify-between py-3 gap-x-4">
            <dt class="text-gray-500 dark:text-gray-400">Project URL:</dt>
            <dd class="flex items-start gap-x-2">
                <div class="font-medium text-gray-900 dark:text-gray-200">{{ $url }}</div>
                @if(isset($env) && $env == 'production')
                    <div class="px-2 py-1 text-xs font-medium text-indigo-700 rounded-md bg-indigo-50 dark:bg-indigo-600 dark:text-white ring-1 ring-inset ring-indigo-600/10">Prod</div>
                @else
                    <div class="px-2 py-1 text-xs font-medium text-orange-700 rounded-md bg-orange-50 dark:bg-orange-500 dark:text-white ring-1 ring-inset ring-orange-600/10">Dev</div>
                @endif
            </dd>
        </div>
        <div class="flex justify-between py-3 gap-x-4">
            <dt class="text-gray-500 dark:text-gray-400">Repository</dt>
            <dd class="text-gray-700 dark:text-gray-300">{{ $repo }}</dd>
            
        </div>
    </dl>
</div>