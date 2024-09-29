<?php
    use function Laravel\Folio\{name};
    name('changelogs');

    $logs = \Wave\Changelog::orderBy('created_at', 'desc')->paginate(10);

    // use a dynamic layout based on whether or not the user is authenticated
    $layout = ((auth()->guest()) ? 'layouts.marketing' : 'layouts.app');
?>

<x-dynamic-component 
	:component="$layout"
>

    
    <x-app.container>
        <x-card class="lg:p-10">
            <x-app.heading
                title="Changelog"
                description="This is your application changelog where users can visit to stay in the loop about your latest updates and improvements."
            />

        <div class="max-w-full mt-8 prose-sm prose dark:prose-invert">
                @foreach($logs as $changelog)
                    <div class="flex flex-col items-start space-y-3 lg:flex-row lg:space-y-0 lg:space-x-5">
                        <div class="flex-shrink-0 px-2 py-1 text-xs translate-y-1 rounded-full bg-zinc-100 dark:bg-zinc-600">
                            <time datetime="{{ Carbon\Carbon::parse($changelog->created_at)->toIso8601String() }}" class="ml-1">{{ Carbon\Carbon::parse($changelog->created_at)->toFormattedDateString() }}</time>
                        </div>
                        <div class="relative">
                            <a href="{{ route('changelog', ['changelog' => $changelog->id]) }}" class="text-xl no-underline hover:underline" wire:navigate>{{ $changelog->title }}</a>
                            <div class="mx-auto mt-5 prose-sm prose text-zinc-600 dark:text-zinc-300">
                                {!! $changelog->body !!}
                            </div>
                            @if(!$loop->last)
                                <hr class="block my-10 border-dashed">
                            @endif
                        </div>
                    </div>
                    
                @endforeach
            </div>
        </x-card>

    </x-app.container>

</x-dynamic-component>