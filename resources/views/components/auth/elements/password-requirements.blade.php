@php
$minLength = config('devdojo.auth.settings.password_min_length', 8);
$requireUppercase = config('devdojo.auth.settings.password_require_uppercase', false);
$requireNumeric = config('devdojo.auth.settings.password_require_numeric', false);
$requireSpecial = config('devdojo.auth.settings.password_require_special_character', false);
$requireUncompromised = config('devdojo.auth.settings.password_require_uncompromised', false);

// Only show if there are requirements beyond the default
$hasRequirements = $requireUppercase || $requireNumeric || $requireSpecial || $requireUncompromised || $minLength != 8;
@endphp

@if(config('devdojo.auth.settings.password_show_requirements', true) && $hasRequirements)
<div class="text-xs space-y-1 mt-1" style="color: {{ config('devdojo.auth.appearance.color.text') }}; opacity: 0.6;">
    <p class="font-medium">{{ __('Password must:') }}</p>
    <ul class="list-disc list-inside space-y-0.5 pl-1">
        @if($minLength > 0)
        <li>{{ __('Be at least :length characters', ['length' => $minLength]) }}</li>
        @endif
        @if($requireUppercase)
        <li>{{ __('Include uppercase and lowercase letters') }}</li>
        @endif
        @if($requireNumeric)
        <li>{{ __('Include at least one number') }}</li>
        @endif
        @if($requireSpecial)
        <li>{{ __('Include at least one special character') }}</li>
        @endif
        @if($requireUncompromised)
        <li>{{ __('Not be a commonly compromised password') }}</li>
        @endif
    </ul>
</div>
@endif
