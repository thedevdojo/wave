<?php
    use function Laravel\Folio\{middleware, name};
    middleware('auth');
    name('analytics');
?>

<x-layouts.app>

    <x-app.heading
        title="Analytics"
        description="View the analytics for your application and sites"
    />

    <section class="py-5 overflow-hidden">
        <x-app.container>
            <x-app.card>
                This is a temporary view that you may use to add your custom page here.
            </x-app.card>
        </x-app.container>
    </section>
</x-layouts.app>