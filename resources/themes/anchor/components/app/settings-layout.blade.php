<x-card class="flex flex-col w-full max-w-4xl mx-auto lg:my-10">
    <div class="flex flex-wrap items-center justify-between pb-3 mt-5 border-b lg:mt-0 sm:mt-8 border-zinc-200 dark:border-zinc-800 sm:flex-no-wrap">
        <div class="relative p-2">
            <div class="space-y-0.5">
                <h2 class="text-xl font-semibold tracking-tight dark:text-zinc-100">{{ $title ?? '' }}</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $description ?? '' }}</p>
            </div>
        </div>
    </div>
    <div class="flex flex-col pt-5 lg:flex-row lg:space-x-8">
        <aside class="flex-shrink-0 pb-8 lg:pt-4 lg:pb-0 lg:w-48">
            <nav class="flex items-start justify-start lg:flex-col lg:space-y-1">
                <div class="px-2.5 pb-1.5 text-xs lg:block hidden font-semibold leading-6 text-zinc-500">Settings</div>
                <div class="flex items-center w-auto space-x-2 lg:items-stretch lg:flex-col lg:w-full lg:space-y-1 lg:space-x-0">
                    <x-settings-sidebar-link :href="route('settings.profile')" icon="phosphor-user-circle-duotone">Profile</x-settings-sidebar-link>
                    <x-settings-sidebar-link :href="route('settings.security')" icon="phosphor-lock-duotone">Security</x-settings-sidebar-link>
                    <x-settings-sidebar-link :href="route('settings.api')" icon="phosphor-code-duotone">API Keys</x-settings-sidebar-link>
                </div>
                <div class="px-2.5 pt-3.5 pb-1.5 text-xs lg:block hidden font-semibold leading-6 text-zinc-500">Billing</div>
                <div class="flex items-center w-full ml-2 space-x-2 lg:items-stretch lg:flex-col lg:ml-0 lg:space-y-1 lg:space-x-0">
                    <x-settings-sidebar-link :href="route('settings.subscription')" icon="phosphor-credit-card-duotone">Subscription</x-settings-sidebar-link>
                    <x-settings-sidebar-link :href="route('settings.invoices')" icon="phosphor-invoice-duotone">Invoices</x-settings-sidebar-link>
                </div>
            </nav>
        </aside>

        <div class="py-3 lg:px-6 lg:w-full">
            {{ $slot }}
        </div>
    </div>
</x-card>