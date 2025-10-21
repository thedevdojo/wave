<section 
    x-data="{ 
        active: 'authentication',
        sections: ['authentication', 'roles-permissions', 'payments', 'subscriptions'],
        featureInterval: null,
        featureIntervalTimer: 6000, // in milliseconds
        percentageRemainingInterval: null,
        percentageRemaining: 100,
        start(){
            clearInterval(this.featureInterval);
            let that = this;
            this.featureInterval = setInterval(function(){
                that.next();
            }, this.featureIntervalTimer);
        },
        hasBeenActive(sectionName){
            let currentIndex = this.sections.indexOf(this.active);
            let targetIndex = this.sections.indexOf(sectionName);
            return targetIndex < currentIndex;
        },
        startCountdown(){
            this.percentageRemaining = 100;
            clearInterval(this.percentageRemainingInterval); // Ensure the interval is cleared before starting a new countdown
            let that = this;
            this.percentageRemainingInterval = setInterval(function(){
                that.percentageRemaining -= 100 / (that.featureIntervalTimer / 120); // Assuming 6000ms interval and 1000ms = 1s
                that.percentageRemaining = parseInt(that.percentageRemaining);
                if (that.percentageRemaining <= 0) {
                    clearInterval(that.percentageRemainingInterval); // Clear the interval when percentageRemaining reaches 0
                }
            }, 120); // Update percentageRemaining every 100ms
        },
        next(){
            let currentIndex = this.sections.indexOf(this.active);
            let nextIndex = (currentIndex + 1) % this.sections.length;
            this.active = this.sections[nextIndex];
            this.startCountdown(); // Start a new countdown for the next feature
        },
        setActive(sectionName){
            this.active = sectionName;
            this.start();
            this.startCountdown(); // Start a new countdown when the active section is set
        },
        getWidth(sectionName){
            if(sectionName == this.active){
                return (100-this.percentageRemaining);
            } 
            return 0;
        }
    }"
    x-init="
        start();
        startCountdown();
    "
    class="pt-6 sm:pb-8 sm:pt-10 md:pt-12 lg:pt-16 bg-background">
    <div class="px-5 mx-auto max-w-7xl md:px-8">
        <x-marketing.elements.heading
            subheading="Power Up Your Productivity - Ship in Days"
            heading="Features Built for Speed"
            description="Our platform has a full-set of functionality. Each feature has been crafted to support your journey and help you quickly build your next great idea."
        />
        <div class="flex lg:flex-row flex-col flex-wrap border-b-[3px] border-gray-200 dark:border-neutral-800 justify-center dark:text-neutral-400 mb-2 lg:mb-6 w-full">
            <button 
                data-active="authentication"
                @click="setActive($el.dataset.active)"
                :class="{ 'active-feature dark:text-white text-black flex flex-shrink-0': active == $el.dataset.active, 'dark:hover:text-white lg:flex hidden dark:text-neutral-400 text-gray-700 hover:text-gray-900' : active != $el.dataset.active }" 
                class="relative items-center justify-start gap-2 px-0 py-4 font-medium transition-all duration-300 cursor-pointer lg:py-6 lg:px-6 lg:justify-center group lg:w-1/4">
                <x-phosphor-lock-key-duotone class="w-6 h-6" />
                <span>Authentication</span>
                <span x-show="hasBeenActive('authentication')" class="absolute z-10 bottom-0 translate-y-[3px] w-full left-0 h-[3px] bg-indigo-600"><span class="absolute inset-0 w-full h-full bg-white/70 dark:bg-black/40"></span></span>
                <span :class="{ 'left-1/2' : !hasBeenActive('authentication'), 'right-0' : hasBeenActive('authentication') }" class="absolute z-20 bottom-0 w-0 translate-y-[3px] group-hover:w-full duration-300 ease-out group-[.active-feature]:w-full group-[.active-feature]:left-0 group-hover:left-0 h-[3px] bg-indigo-600">
                    <span class="block absolute group-[.active-feature]:opacity-100 opacity-0 top-0 left-0 w-full h-full bg-white/70 dark:bg-black/40 ease-out duration-300" :style="{ width: getWidth('authentication') + '%' }"></span>
                </span>
            </button>
            <button 
                data-active="roles-permissions"
                @click="setActive($el.dataset.active)"
                :class="{ 'active-feature dark:text-white text-black flex flex-shrink-0': active == $el.dataset.active, 'dark:hover:text-white lg:flex hidden dark:text-neutral-400 text-gray-700 hover:text-gray-900' : active != $el.dataset.active }" 
                class="relative items-center justify-start w-full gap-2 p-6 px-0 py-4 font-medium transition-all duration-300 cursor-pointer lg:py-6 lg:px-6 lg:justify-center group lg:w-1/4">
                <x-phosphor-key-duotone class="w-6 h-6" />
                <span>Roles & Permissions</span>
                <span x-show="hasBeenActive('roles-permissions')" class="absolute z-10 bottom-0 translate-y-[3px] w-full left-0 h-[3px] bg-indigo-600"><span class="absolute inset-0 w-full h-full bg-white/70 dark:bg-black/40"></span></span>
                <span :class="{ 'left-1/2' : !hasBeenActive('roles-permissions'), 'right-0' : hasBeenActive('roles-permissions') }" class="absolute z-20 bottom-0 w-0 translate-y-[3px] group-hover:w-full duration-300 ease-out group-[.active-feature]:w-full group-[.active-feature]:left-0 group-hover:left-0 h-[3px] bg-indigo-600">
                    <span class="block absolute group-[.active-feature]:opacity-100 opacity-0 top-0 left-0 w-full h-full bg-white/70 dark:bg-black/40 ease-out duration-300" :style="{ width: getWidth('roles-permissions') + '%' }"></span>
                </span>
            </button>
            <button 
                data-active="payments"
                @click="setActive($el.dataset.active)"
                :class="{ 'active-feature dark:text-white text-black flex flex-shrink-0': active == $el.dataset.active, 'dark:hover:text-white lg:flex hidden dark:text-neutral-400 text-gray-700 hover:text-gray-900' : active != $el.dataset.active }" 
                class="relative items-center justify-start w-full gap-2 p-6 px-0 py-4 font-medium transition-all duration-300 cursor-pointer lg:py-6 lg:px-6 lg:justify-center group lg:w-1/4">
                <x-phosphor-bank-duotone class="w-6 h-6" />
                <span>Payments</span>
                <span x-show="hasBeenActive('payments')" class="absolute z-10 bottom-0 translate-y-[3px] w-full left-0 h-[3px] bg-indigo-600"><span class="absolute inset-0 w-full h-full bg-white/70 dark:bg-black/40"></span></span>
                <span :class="{ 'left-1/2' : !hasBeenActive('payments'), 'right-0' : hasBeenActive('payments') }" class="absolute z-20 bottom-0 w-0 translate-y-[3px] group-hover:w-full duration-300 ease-out group-[.active-feature]:w-full group-[.active-feature]:left-0 group-hover:left-0 h-[3px] bg-indigo-600">
                    <span class="block absolute group-[.active-feature]:opacity-100 opacity-0 top-0 left-0 w-full h-full bg-white/70 dark:bg-black/40 ease-out duration-300" :style="{ width: getWidth('payments') + '%' }"></span>
                </span>
            </button>
            <button 
                data-active="subscriptions"
                @click="setActive($el.dataset.active)"
                :class="{ 'active-feature dark:text-white text-black flex flex-shrink-0': active == $el.dataset.active, 'dark:hover:text-white lg:flex hidden dark:text-neutral-400 text-gray-700 hover:text-gray-900' : active != $el.dataset.active }" 
                class="relative items-center justify-start gap-2 p-6 px-0 py-4 font-medium transition-all duration-300 cursor-pointer lg:py-6 lg:px-6 lg:justify-center group lg:w-1/4">
                <x-phosphor-credit-card-duotone class="w-6 h-6" />
                <span>Subscriptions</span>
                <span x-show="hasBeenActive('subscriptions')" class="absolute z-10 bottom-0 translate-y-[3px] w-full left-0 h-[3px] bg-indigo-600"><span class="absolute inset-0 w-full h-full bg-white/70 dark:bg-black/40"></span></span>
                <span :class="{ 'left-1/2' : !hasBeenActive('subscriptions'), 'right-0' : hasBeenActive('subscriptions') }" class="absolute z-20 bottom-0 w-0 translate-y-[3px] group-hover:w-full duration-300 ease-out group-[.active-feature]:w-full group-[.active-feature]:left-0 group-hover:left-0 h-[3px] bg-indigo-600">
                    <span class="block absolute group-[.active-feature]:opacity-100 opacity-0 top-0 left-0 w-full h-full bg-white/70 dark:bg-black/40 ease-out duration-300" :style="{ width: getWidth('subscriptions') + '%' }"></span>
                </span>
            </button>
        </div>
        
        <div x-show="active=='authentication'" class="w-full py-6">
            @include('theme::partials.feature-content', [
                'title' => 'Authentication',
                'description' => 'All the authentication features you need to build your next great idea.',
                'icon' => 'phosphor-lock-key-duotone',
                'items' => [
                    [
                        'icon' => 'phosphor-sign-in-duotone',
                        'title' => 'User Login',
                        'description' => 'Our authentication features offer user login, registration, and more.',
                    ],
                    [
                        'icon' => 'phosphor-password-duotone',
                        'title' => 'Reset Password',
                        'description' => 'Allow users and customers the ability to easily set and reset their password.',
                    ],
                    [
                        'icon' => 'phosphor-user-circle-check-duotone',
                        'title' => 'Email Verification',
                        'description' => 'Enforce that users verify their email before gaining access to your app.',
                    ],
                    [
                        'icon' => 'phosphor-shield-star-duotone',
                        'title' => 'Two-Factor Auth',
                        'description' => 'Allow your users to secure their account with a simple 2FA implementation.',
                    ],
                ]
            ])
        </div>
        <div x-show="active=='roles-permissions'" class="w-full py-6" x-cloak>
            @include('theme::partials.feature-content', [
                'title' => 'Roles & Permissions',
                'description' => 'Allow or deny access to features with user roles and permissions.',
                'icon' => 'phosphor-key-duotone',
                'items' => [
                    [
                        'icon' => 'phosphor-users-duotone',
                        'title' => 'Manage Roles',
                        'description' => 'Create and manage user roles to control access to different parts of your application.',
                    ],
                    [
                        'icon' => 'phosphor-lock-duotone',
                        'title' => 'Permission Control',
                        'description' => 'Define and enforce granular permissions to ensure users have access only to the features they need.',
                    ],
                    [
                        'icon' => 'phosphor-shield-check-duotone',
                        'title' => 'Role-Based Access',
                        'description' => 'Easily assign roles to users and manage access control throughout your application.',
                    ],
                    [
                        'icon' => 'phosphor-user-gear-duotone',
                        'title' => 'User Management',
                        'description' => 'Handle user roles and permissions with an intuitive interface and automated workflows.',
                    ],
                ]
            ])
        </div>
        <div x-show="active=='payments'" class="w-full py-6" x-cloak>
            @include('theme::partials.feature-content', [
                'title' => 'Payments',
                'description' => 'Accept payments using the Stripe or Paddle payment platform.',
                'icon' => 'phosphor-bank-duotone',
                'items' => [
                    [
                        'icon' => 'phosphor-credit-card-duotone',
                        'title' => 'Multiple Payment Methods',
                        'description' => 'Support for various payment methods, including credit cards, bank transfers, and digital wallets.',
                    ],
                    [
                        'icon' => 'phosphor-receipt-duotone',
                        'title' => 'Subscription Billing',
                        'description' => 'Easily manage recurring payments and subscriptions with flexible billing cycles.',
                    ],
                    [
                        'icon' => 'phosphor-bank-duotone',
                        'title' => 'One-Time Payments',
                        'description' => 'Accept one-time payments for products, services, or donations seamlessly.',
                    ],
                    [
                        'icon' => 'phosphor-lock-laminated-duotone',
                        'title' => 'Secure Transactions',
                        'description' => 'Ensure all transactions are secure with PCI-compliant payment processing.',
                    ],
                ]
            ])
        </div>
        <div x-show="active=='subscriptions'" class="w-full py-6" x-cloak>
            @include('theme::partials.feature-content', [
                'title' => 'Subscriptions',
                'description' => 'Assign user a specific role when they subscribe to a plan..',
                'icon' => 'phosphor-credit-card-duotone',
                'items' => [
                    [
                        'icon' => 'phosphor-clock-duotone',
                        'title' => 'Flexible Plans',
                        'description' => 'Create flexible subscription plans to fit various user needs and pricing tiers.',
                    ],
                    [
                        'icon' => 'phosphor-arrows-clockwise-duotone',
                        'title' => 'Automatic Renewal',
                        'description' => 'Enable automatic subscription renewals to ensure uninterrupted service for your users.',
                    ],
                    [
                        'icon' => 'phosphor-bell-ringing-duotone',
                        'title' => 'Subscription Notifications',
                        'description' => 'Send automated notifications to users about subscription status, renewals, and expirations.',
                    ],
                    [
                        'icon' => 'phosphor-file-text-duotone',
                        'title' => 'Invoice Management',
                        'description' => 'Generate and manage invoices for all subscription transactions to keep track of billing history.',
                    ],
                ]
            ])
        </div>

    </div>
</section>
