<x-filament-widgets::widget class="gap-5 fi-filament-info-widget">
    <section class="flex flex-col gap-5 mb-5 space-x-5 w-full xl:flex-row">
        <x-filament::section class="w-full">
            <div class="flex gap-x-3 items-center w-full">
                <div class="flex-1">
                    <a href="/" rel="noopener noreferrer" target="_blank"><x-logo class="w-auto h-6"></x-logo></a>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ wave_version() }}</p>
                </div>
                <div class="flex flex-col gap-y-1 items-end">
                    <x-filament::link color="gray" href="https://wave.devdojo.com/docs" icon="heroicon-m-book-open" icon-alias="panels::widgets.filament-info.open-documentation-button" rel="noopener noreferrer" target="_blank">
                        {{ __('filament-panels::widgets/filament-info-widget.actions.open_documentation.label') }}
                    </x-filament::link>
                    <x-filament::link color="gray" href="https://github.com/thedevdojo/wave" icon-alias="panels::widgets.filament-info.open-github-button" rel="noopener noreferrer" target="_blank">
                        <x-slot name="icon"><svg viewBox="0 0 98 96" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" fill="currentColor" fill-rule="evenodd" d="M48.854 0C21.839 0 0 22 0 49.217c0 21.756 13.993 40.172 33.405 46.69 2.427.49 3.316-1.059 3.316-2.362 0-1.141-.08-5.052-.08-9.127-13.59 2.934-16.42-5.867-16.42-5.867-2.184-5.704-5.42-7.17-5.42-7.17-4.448-3.015.324-3.015.324-3.015 4.934.326 7.523 5.052 7.523 5.052 4.367 7.496 11.404 5.378 14.235 4.074.404-3.178 1.699-5.378 3.074-6.6-10.839-1.141-22.243-5.378-22.243-24.283 0-5.378 1.94-9.778 5.014-13.2-.485-1.222-2.184-6.275.486-13.038 0 0 4.125-1.304 13.426 5.052a46.97 46.97 0 0 1 12.214-1.63c4.125 0 8.33.571 12.213 1.63 9.302-6.356 13.427-5.052 13.427-5.052 2.67 6.763.97 11.816.485 13.038 3.155 3.422 5.015 7.822 5.015 13.2 0 18.905-11.404 23.06-22.324 24.283 1.78 1.548 3.316 4.481 3.316 9.126 0 6.6-.08 11.897-.08 13.526 0 1.304.89 2.853 3.316 2.364 19.412-6.52 33.405-24.935 33.405-46.691C97.707 22 75.788 0 48.854 0z" /></svg></x-slot>
                        {{ __('filament-panels::widgets/filament-info-widget.actions.open_github.label') }}
                    </x-filament::link>
                </div>
            </div>
        </x-filament::section>
        <x-filament::section class="w-full">
            <div class="flex gap-x-3 items-center w-full">
                <div class="flex-1">
                    <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">Welcome to the Wave Admin</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-medium text-blue-600">Active Theme: </span>{{ \Wave\Theme::where('active', 1)->first()->name }}</p>
                </div>
                <x-filament::button color="gray" icon="heroicon-m-arrow-top-right-on-square" icon-alias="panels::widgets.account.logout-button" labeled-from="sm" tag="a" type="submit" href="/" target="_blank">
                    Visit your Site
                </x-filament::button>
            </div>
        </x-filament::section>
    </section>
    <section class="flex gap-5 mb-5">
        <section class="flex flex-col gap-5 items-center w-full xl:flex-row">
            <x-filament::section class="w-full">
                <div class="flex gap-x-5 items-center">
                    <div class="flex-">
                        <x-phosphor-users-duotone class="h-10 text-blue-600 fill-current" />
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="mt-1 text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-200">{{ \Wave\User::count() }}</div>
                    </div>
                </div>
                <div class="mt-2 text-xs font-medium text-gray-500 truncate">User Accounts</div>
            </x-filament::section>
            <x-filament::section class="w-full">
                <div class="flex gap-x-5 items-center">
                    <div class="flex-">
                        <x-phosphor-credit-card-duotone class="h-10 text-blue-600 fill-current" />
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="mt-1 text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-200">{{ \Wave\Subscription::where('status', 'active')->count() }}</div>
                    </div>
                </div>
                <div class="mt-2 text-xs font-medium text-gray-500 truncate">Subscribers</div>
            </x-filament::section>
        </section>
        <section class="flex flex-col gap-5 items-center w-full xl:flex-row">
            <x-filament::section class="w-full">
                <div class="flex gap-x-5 items-center">
                    <div class="hidden lg:inline">
                        <x-phosphor-pencil-line-duotone class="h-10 text-blue-600 fill-current" />
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="mt-1 text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-200">{{ \Wave\Post::count() }}</div>
                    </div>
                </div>
                <div class="mt-2 text-xs font-medium text-gray-500 truncate">Total Post Articles</div>
            </x-filament::section>
            <x-filament::section class="w-full">
                <div class="flex gap-x-5 items-center">
                    <div class="flex-">
                        <x-phosphor-file-text-duotone class="h-10 text-blue-600 fill-current" />
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="mt-1 text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-200">{{ \Wave\Page::count() }}</div>
                    </div>
                </div>
                <div class="mt-2 text-xs font-medium text-gray-500 truncate">Total Pages</div>
            </x-filament::section>
        </section>
    </section>
    <x-filament::section>
        <div class="flex flex-col relative gap-x-3 justify-center space-y-2 items-center min-h-[400px] w-full ">
            <p class="text-center text-gray-400 dark:text-gray-500">Welcome to your Admin Dashboard. Modify this page at:</p> 
            <code class="px-2 py-1 text-xs text-center text-gray-500 rounded-lg dark:text-gray-400 bg-stone-200 dark:bg-stone-800">resources/views/filament/widgets/dashboard-placeholder-widget.blade.php</code>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
