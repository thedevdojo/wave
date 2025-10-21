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
        
        <x-marketing.sections.hero />
        <x-marketing.sections.marquee />
        <x-marketing.sections.features />
        <x-marketing.elements.separator />
        <x-marketing.sections.testimonials />
        <x-marketing.elements.separator />
        <x-marketing.sections.pricing />

</x-layouts.marketing>
