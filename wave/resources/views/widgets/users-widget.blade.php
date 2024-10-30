<x-filament-widgets::widget class="flex gap-x-5 fi-filament-info-widget">
    <x-filament::section class="w-full">
        <div class="flex gap-x-5 items-center">
            <div class="flex-">
                <x-phosphor-users-duotone class="h-10 text-blue-600 fill-current" />
            </div>
            
            <div class="w-full">
                <div class="flex flex-col">
                    <div class="mt-1 text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-200">{{ \Wave\User::count() }}</div>
                </div>
            </div>
            
        </div>
        <div class="mt-2 text-xs font-medium text-gray-500 truncate">Active User Accounts</div>
    </x-filament::section>
    <x-filament::section class="w-full">
        <div class="flex gap-x-5 items-center">
            <div class="flex-">
                <x-phosphor-credit-card-duotone class="h-10 text-blue-600 fill-current" />
            </div>
            
            <div class="w-full">
                <div class="flex flex-col">
                    <div class="mt-1 text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-200">{{ \Wave\User::count() }}</div>
                </div>
            </div>
            
        </div>
        <div class="mt-2 text-xs font-medium text-gray-500 truncate">Total Subscribers</div>
    </x-filament::section>
</x-filament-widgets::widget>
