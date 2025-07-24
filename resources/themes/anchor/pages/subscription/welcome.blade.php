<?php
    use function Laravel\Folio\{middleware, name};
    name('subscription.welcome');
    middleware('auth');
?>

<x-layouts.app>
	<x-app.container x-data class="space-y-6" x-cloak>
        <div class="w-full">
            <x-app.heading
                title="Achiziție realizată cu succes 🎉"
                description="Mulțumim că ați trecut la un plan de abonament."
            />
            <div class="py-5 space-y-5">
                <p>Aceasta este pagina de bun venit după achiziția cu succes a unui abonament. După ce un utilizator își upgradează contul, va fi redirecționat către această pagină după tranzacția reușită.</p>
                <p>Puteți modifica această vizualizare în folderul temei la <x-code-inline>pages/subscription/welcome</x-code-inline>.</p>
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