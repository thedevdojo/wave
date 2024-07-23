<?php
    use function Laravel\Folio\{name};
    name('home');
?>

<x-layouts.marketing
    :seo="[
        'title'         => setting('site.title', 'Laravel Wave'),
        'description'   => setting('site.description', 'Software as a Service Starter Kit'),
        'image'         => url('/og_image.png'),
        'type'          => 'website'
    ]"
>
    <section class="px-5 pt-24 mx-auto max-w-5xl text-center">
        <div class="space-y-5">
            <h1 class="text-4xl font-bold tracking-tighter text-gray-900 lg:text-7xl">Ship in Days, Not Months</h1>
            <p class="text-base font-medium text-gray-500 text-balance">Quickly bring your your application to market with Tons of features out of the box. Wave will empower you to ship your next idea faster than ever before.</p>
            <div class="space-x-3 w-full text-center">
                <x-button size="lg">Primary Button</x-button>
                <x-button size="lg" color="secondary">Secondary Button</x-button>
            </div>
        </div>
        <div class="w-full mt-20 px-5 mx-auto bg-gray-200 h-[600px]"></div>
    </section>
</x-layouts.marketing> 