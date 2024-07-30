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
    <x-marketing.hero></x-marketing.hero>
    <x-marketing.features></x-marketing.features>
    <x-marketing.testimonials></x-marketing.testimonials>
    <x-marketing.pricing></x-marketing.pricing>
</x-layouts.marketing> 