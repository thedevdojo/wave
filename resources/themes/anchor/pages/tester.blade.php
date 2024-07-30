<?php
    use function Laravel\Folio\{name};
?>

<x-layouts.marketing
    :seo="[
        'title'         => setting('site.title', 'Laravel Wave'),
        'description'   => setting('site.description', 'Software as a Service Starter Kit'),
        'image'         => url('/og_image.png'),
        'type'          => 'website'
    ]"
> 
        
       @hasrole('admin')
    I admin
@else
    I not
@endhasrole

</x-layouts.marketing>
