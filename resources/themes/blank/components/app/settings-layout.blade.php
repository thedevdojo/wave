<x-card class="flex flex-col mx-auto w-full max-w-4xl">
    <div class="flex flex-wrap justify-between items-center pb-3 border-b border-gray-400 dark:border-zinc-800 sm:flex-no-wrap">
        <div class="relative p-2">
            <div class="space-y-0.5">
                <h2 class="text-xl font-semibold tracking-tight dark:text-zinc-100">{{ $title ?? '' }}</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $description ?? '' }}</p>
            </div>
        </div>
    </div>
    <div class="flex pt-5 space-x-8">
        <aside class="flex-shrink-0 pt-4 lg:w-48">
            <nav class="flex space-x-2 lg:flex-col lg:space-x-0 lg:space-y-1">
                <div class="px-2.5 pb-1.5 text-xs font-semibold leading-6 text-zinc-500">Settings</div>
                <x-settings-sidebar-link :href="route('settings.profile')" icon="phosphor-user-circle-duotone">Profile</x-settings-sidebar-link>
                <x-settings-sidebar-link :href="route('settings.security')" icon="phosphor-lock-duotone">Security</x-settings-sidebar-link>
                <x-settings-sidebar-link :href="route('settings.api')" icon="phosphor-code-duotone">API Keys</x-settings-sidebar-link>
                

                <div class="px-2.5 pt-3.5 pb-1.5 text-xs font-semibold leading-6 text-zinc-500">Billing</div>
                <x-settings-sidebar-link :href="route('settings.plans')" icon="phosphor-storefront-duotone">Plans</x-settings-sidebar-link>
                <x-settings-sidebar-link :href="route('settings.subscription')" icon="phosphor-credit-card-duotone">Subscription</x-settings-sidebar-link>
                <x-settings-sidebar-link :href="route('settings.invoices')" icon="phosphor-invoice-duotone">Invoices</x-settings-sidebar-link>
            </nav>
        </aside>

        <div class="px-6 py-3 lg:w-full">
            {{ $slot }}
        </div>
    </div>
</x-card>