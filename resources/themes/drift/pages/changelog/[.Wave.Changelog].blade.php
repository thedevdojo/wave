<?php
    use function Laravel\Folio\{name};
    name('changelog');
    
    // use a dynamic layout based on whether or not the user is authenticated
    $layout = ((auth()->guest()) ? 'layouts.marketing' : 'layouts.app');
?>

<x-dynamic-component 
	:component="$layout"
	bodyClass="bg-zinc-50"
>

    <x-app.heading
        :title="$changelog->title"
        :description="$changelog->description"
    />
    
    <x-app.container class="mt-6">
            
            <x-elements.back-button
                text="View Full Changelog"
                :href="route('changelogs')"
            />
            
            <x-app.card class="p-10">
            
                <article id="changelog-{{ $changelog->id }}" class="w-full max-w-2xl mx-auto">

                    <meta property="name" content="{{ $changelog->title }}">
                    <meta property="author" typeof="Person" content="admin">
                    <meta property="dateModified" content="{{ Carbon\Carbon::parse($changelog->updated_at)->toIso8601String() }}">
                    <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($changelog->created_at)->toIso8601String() }}">

                    <div class="p-5">
                        <p class="mt-5 text-xs font-medium tracking-wider text-gray-800 dark:text-gray-400">Posted on <time datetime="{{ Carbon\Carbon::parse($changelog->created_at)->toIso8601String() }}" class="ml-1">{{ Carbon\Carbon::parse($changelog->created_at)->toFormattedDateString() }}</time></p>
                        <div class="mx-auto mt-5 prose text-gray-600 prose-base dark:text-gray-400 dark:prose-invert">
                            {!! $changelog->body !!}
                        </div>
                    </div>

                </article>
            </x-app.card>
    </x-app.container>

</x-dynamic-component>
