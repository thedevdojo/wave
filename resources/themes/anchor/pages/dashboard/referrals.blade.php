<?php
    use function Laravel\Folio\{middleware, name};
	middleware('auth');
    name('dashboard.referrals');
?>

<x-layouts.app>
	<x-app.container x-data class="lg:space-y-6" x-cloak>
        
		<x-app.alert id="referrals_alert" class="hidden lg:flex">
            Share your referral link and earn commissions when your friends subscribe! 
            You'll receive 20% of their subscription fees as rewards.
        </x-app.alert>

        <x-app.heading
            title="Referral Program"
            description="Invite friends and earn rewards for every successful subscription"
            :border="false"
        />

        @livewire('referrals.dashboard')

    </x-app.container>
</x-layouts.app>
