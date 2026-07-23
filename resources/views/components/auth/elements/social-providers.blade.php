@props([
    'socialProviders' => \Devdojo\Auth\Helper::activeProviders(),
    'separator' => true,
    'separator_text' => 'or',
    'passkey' => null,
    'passkeyReloadOnSuccess' => false,
])

@php
    $passkeyEnabled = config('devdojo.auth.settings.enable_passkeys', false) && ($passkey ?? true);
    $hasSocialProviders = count($socialProviders) > 0;
    $showSection = $hasSocialProviders || $passkeyEnabled;
@endphp

@if($showSection)
    @if($separator && config('devdojo.auth.settings.social_providers_location') != 'top')
        <x-auth::elements.separator class="my-6">{{ $separator_text }}</x-auth::elements.separator>
    @endif
    <div class="relative space-y-2 w-full @if(config('devdojo.auth.settings.social_providers_location') != 'top' && !$separator){{ 'mt-3' }}@endif">
        @if($passkeyEnabled)
            <x-auth::elements.passkey-verify :reload-on-success="$passkeyReloadOnSuccess" />
        @endif
        @foreach($socialProviders as $slug => $provider)
            <x-auth::elements.social-button :$slug :$provider />
        @endforeach
    </div>
    @if($separator && config('devdojo.auth.settings.social_providers_location') == 'top')
        <x-auth::elements.separator class="my-6">{{ $separator_text }}</x-auth::elements.separator>
    @endif
@endif
