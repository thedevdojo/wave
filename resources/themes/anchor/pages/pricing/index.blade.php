<?php
    use function Laravel\Folio\{middleware, name};
    name('pricing');
?>

<x-layouts.marketing>

    <x-container class="py-10">
        <x-marketing.sections.pricing />
    </x-container>

</x-layouts.marketing>
