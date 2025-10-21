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

    <x-app.heading
        title="Changelog"
        description="This is your application changelog where users can visit to stay in the loop about your latest updates and improvements."
    />
    
    <x-app.container class="w-full mt-6">
        <x-back-button text="Back to Dashboard" href="/dashboard"></x-back-button>

        <x-app.card>

            <div class="max-w-full p-2 lg:p-10 prose-sm prose">
                @foreach($logs as $changelog)
                    <div class="flex flex-col items-start max-w-3xl mx-auto space-y-5 md:space-y-0 md:space-x-5 md:flex-row">
                        <div class="flex-shrink-0 px-2 py-1 text-xs translate-y-1 rounded-full bg-zinc-100 dark:bg-gray-700 dark:text-white">
                            <time datetime="{{ Carbon\Carbon::parse($changelog->created_at)->toIso8601String() }}" class="ml-1">{{ Carbon\Carbon::parse($changelog->created_at)->toFormattedDateString() }}</time>
                        </div>
                        <div class="relative">
                            <a href="{{ route('changelog', ['changelog' => $changelog->id]) }}" class="text-xl no-underline dark:text-gray-200 hover:underline" wire:navigate>{{ $changelog->title }}</a>
                            <div class="mx-auto mt-5 prose-sm prose text-gray-600 dark:text-gray-400 dark:prose-invert">
                                {!! $changelog->body !!}
                            </div>
                            @if(!$loop->last)
                                <hr class="block my-10 border dark:border-gray-800">
                            @endif
                        </div>
                    </div>
                    
                @endforeach
            </div>
        </x-app.card>

    </x-app.container>

</x-dynamic-component>