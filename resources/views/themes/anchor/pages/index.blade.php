<?php
    use function Laravel\Folio\{name};

    name('wave.home');
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
    <x-marketing.sections.features />
    <x-marketing.sections.testimonials />
    <x-marketing.sections.pricing />

</x-layouts.marketing>
