<?php
    use function Laravel\Folio\{middleware, name};
    name('subscription.welcome');
    middleware('auth');
?>

<x-layouts.app>
	<x-app.container x-data class="space-y-6" x-cloak>
        <div class="w-full">
            <x-app.heading
                title="Successfully Purchased 🎉"
                description="Thanks for upgrading to a subscription plan."
            />
            <div class="py-5 space-y-5">
                <p>This is your customer's successful purchase welcome screen. After a user upgrades their account they will be redirected to this page after a successful transaction.</p>
                <p>You can modify this view inside your theme folder at <x-code-inline>pages/subscription/welcome</x-code-inline>.</p>
            </div>
        </div>
    </x-app.container>
    <x-slot name="javascript">
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
        <script>
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
        </script>
    </x-slot>
</x-layouts.app>