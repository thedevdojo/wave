<?php
    use function Laravel\Folio\{middleware, name};
    middleware('auth');
    name('dashboard');
?>

<x-layouts.app>

    <x-app.heading
        title="Dashboard"
        description="Welcome to an example applicaction dashboard. Find more resources below."
    />

    <section class="py-5 overflow-hidden">
        <x-app.container>

            @subscriber
                <div class="relative w-full mb-5 rounded-lg border border-transparent bg-blue-600 p-4 [&amp;>svg]:absolute [&amp;>svg]:text-foreground [&amp;>svg]:left-4 [&amp;>svg]:top-4 [&amp;>svg+div]:translate-y-[-3px] [&amp;:has(svg)]:pl-11 text-white">
                    <x-phosphor-heart class="w-5 h-5 -translate-y-0.5" />
                    <h5 class="mb-1 font-medium leading-none tracking-tight">Thanks for being a subscriber!</h5>
                    <div class="text-sm opacity-80">You've successfully subscribed to a plan. You can change this message from inside your theme folder.</div>
                </div>
            @endsubscriber  

            <div class="grid grid-cols-1 gap-5 mx-auto max-w-7xl sm:grid-cols-2 lg:grid-cols-4">
                <x-app.card>
                    <div class="relative z-20 flex flex-wrap justify-between px-2 gap-y-2 gap-x-4">
                        <p class="flex items-center text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">
                            <x-phosphor-users-duotone class="mr-1.5 w-5 h-5" />
                            <span>New Customers</span>
                        </p>
                        <div class="text-xs font-medium text-green-600">+45</div>
                        <div class="flex-none w-full text-3xl font-medium leading-10 tracking-tight text-gray-900 dark:text-gray-200"">25,645</div>
                    </div>
                </x-app.card>
                <x-app.card>
                    <div class="relative z-20 flex flex-wrap justify-between px-2 gap-y-2 gap-x-4">
                        <p class="flex items-center text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">
                            <x-phosphor-money-duotone class="mr-1.5 w-5 h-5" />
                            <span>Revenue</span>
                        </p>
                        <div class="text-xs font-medium text-green-600">+5.25%</div>
                        <div class="flex-none w-full text-3xl font-medium leading-10 tracking-tight text-gray-900 dark:text-gray-200">$201,075.00</div>
                    </div>
                </x-app.card>
                <x-app.card>
                    <div class="flex flex-wrap justify-between px-2 gap-y-2 gap-x-4">
                        <p class="flex items-center text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">
                            <x-phosphor-invoice-duotone class="mr-1.5 w-5 h-5" />
                            <span>Overdue Invoices</span>
                        </p>
                        <div class="text-xs font-medium text-green-600">+0.95%</div>
                        <div class="flex-none w-full text-3xl font-medium leading-10 tracking-tight text-gray-900 dark:text-gray-200"">$10,420.00</div>
                    </div>
                </x-app.card>
                <x-app.card>
                    <div class="flex flex-wrap justify-between px-2 gap-y-2 gap-x-4d">
                        <p class="flex items-center text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">
                            <x-phosphor-receipt-duotone class="mr-1.5 w-5 h-5" />
                            <span>Expenses</span>
                        </p>
                        <div class="text-xs font-medium text-red-600">-0.75%</div>
                        <div class="flex-none w-full text-3xl font-medium leading-10 tracking-tight text-gray-900 dark:text-gray-200"">$35,071.00</div>
                    </div>
                </x-app.card>
            </div>

        
            <x-app.card class="mt-5">    
                <div class="max-w-2xl px-2 py-1 mx-auto lg:mx-0 lg:max-w-none">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold leading-7 text-gray-700 dark:text-gray-400"">Latest Projects</h2>
                        <a href="/projects" wire:navigate class="text-sm font-semibold leading-6 text-blue-600 hover:text-blue-500 dark:text-blue-500 hover:dark:text-blue-400">View all projects</a>
                    </div>
                    <ul role="list" class="grid grid-cols-1 mt-6 gap-x-7 gap-y-8 lg:grid-cols-3">
                        <li>
                            <x-app.project-card-02
                                title="Project Name Here"
                                url="project-url.com"
                                repo="username/repository"
                            />
                        </li>
                        <li>
                            <x-app.project-card-02
                                title="Project Name Here"
                                url="project-url.com"
                                repo="username/repository"
                                env="production"
                            />
                        </li>
                        <li>
                            <x-app.project-card-02
                                title="Project Name Here"
                                url="project-url.com"
                                repo="username/repository"
                                env="production"
                            />
                        </li>
                    </ul>
                </div>
            </x-app.card>
        </x-app.container>
    </section>
</x-layouts.app>