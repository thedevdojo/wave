<a href="{{ url('auth/' . $slug . '/redirect') }}" class="flex @if(config('devdojo.auth.settings.center_align_social_provider_button_content')){{ 'justify-center' }}@endif items-center px-4 py-3 space-x-2.5 w-full h-auto text-sm rounded-md border border-zinc-200 text-zinc-600 hover:bg-zinc-100">
    <span class="w-5 h-5">
        @if(isset($provider->svg) && !empty(trim($provider->svg)))
            {!! $provider->svg !!}
        @else
            <span class="block w-full h-full rounded-full bg-zinc-200"></span>
        @endif
    </span>
    <span>Continue with {{ $provider->name }}</span>
</a>