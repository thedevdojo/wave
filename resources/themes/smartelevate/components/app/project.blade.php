<x-app.card>
    @if($skeleton ?? false)
        <div class="flex items-start mb-5 space-x-4 text-transparent">
            <div class="p-5 bg-gray-300 rounded-full dark:bg-gray-800"></div>
            <div class="flex flex-col justify-start items-start space-y-1.5">
                <h3 class="font-semibold leading-tight bg-gray-200 rounded dark:bg-gray-800">{{ $title }}</h3>
                <p class="text-sm leading-none bg-gray-200 rounded dark:bg-gray-800">{{ $url }}</p>
            </div>
        </div>
        <div class="flex items-center mb-1 text-sm leading-none text-gray-400">
            <x-phosphor-git-branch-bold class="mr-1 w-3.5 h-3.5" />
            <span class="text-transparent bg-gray-200 rounded dark:bg-gray-800">{{ $repo }}</span>
        </div>
    @else
        <div class="flex items-start mb-5 space-x-4 text-gray-700 dark:text-gray-300">
            <div class="inline-flex items-center justify-center w-10 h-10 text-4xl leading-none text-white bg-black rounded-full aspect-square">
                <span class="-translate-y-[3px] translate-x-px">»</span>
            </div>
            <div class="flex flex-col justify-start items-start space-y-1.5">
                <h3 class="font-semibold leading-tight">{{ $title }}</h3>
                <p class="text-sm leading-none">{{ $url }}</p>
            </div>
        </div>
        <div class="flex items-center text-sm leading-none text-gray-600 dark:text-gray-400 mb-1x">
            <x-phosphor-git-branch-bold class="mr-1 w-3.5 h-3.5" />
            <span>{{ $repo }}</span>
        </div>
    @endif
</x-app.card>