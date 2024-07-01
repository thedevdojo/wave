<?php
    use function Laravel\Folio\{name};
    name('changelogs');

    $logs = \Wave\Changelog::paginate(10);

    // use a dynamic layout based on whether or not the user is authenticated
    $layout = ((auth()->guest()) ? 'layouts.marketing' : 'layouts.app');
?>

<x-dynamic-component 
	:component="$layout"
	bodyClass="bg-zinc-50"
>

    
    <x-app.container>
        <x-card class="p-10">
            <x-app.heading
                title="Changelog"
                description="This is your application changelog where users can visit to stay in the loop about your latest updates and improvements."
            />

            <div class="mt-8 max-w-full prose prose-sm">
                <div class="space-y-5">
                    @foreach($logs as $changelog)
                        <div class="flex items-start space-x-5">
                            <div class="flex-shrink-0 px-2 py-1 text-xs rounded-full bg-zinc-100">
                                <time datetime="{{ Carbon\Carbon::parse($changelog->created_at)->toIso8601String() }}" class="ml-1">{{ Carbon\Carbon::parse($changelog->created_at)->toFormattedDateString() }}</time>
                            </div>
                            <div class="relative">
                                <a href="{{ route('changelog', ['changelog' => $changelog->id]) }}" wire:navigate>{{ $changelog->title }}</a>
                                <span class="block mt-0 text-zinc-600">{{ $changelog->description }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-card>

    </x-app.container>

</x-dynamic-component>