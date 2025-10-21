<?php
    use function Laravel\Folio\{middleware, name};
    middleware('auth');
    name('support');
?>

<x-layouts.app>

    <x-app.heading
        title="Support"
        description="Need help with a question, we're here to help"
    />

    <section class="py-5 overflow-hidden">
        <x-app.container>
            <x-app.card>
                This is a temporary view that you may use to add your custom page here.
            </x-app.card>
        </x-app.container>
    </section>
</x-layouts.app>