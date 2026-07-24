<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public string $username;

    #[Computed]
    public function user()
    {
        $user = config('wave.user_model')::where('username', '=', $this->username)->with('roles')->firstOrFail();

        $privacySettings = $user->privacy_settings ?? ['profile_visibility' => 'public'];

        if ($privacySettings['profile_visibility'] === 'private' && (! auth()->check() || auth()->id() !== $user->id)) {
            abort(404);
        }

        return $user;
    }

    public function render()
    {
        $layout = auth()->guest()
            ? 'theme::components.layouts.marketing'
            : 'theme::components.layouts.app';

        $layoutData = auth()->guest()
            ? ['bodyClass' => 'bg-zinc-50']
            : [];

        return $this->view()->layout($layout, $layoutData);
    }
}

?>

@php
    $privacySettings = $this->user->privacy_settings ?? ['allow_search_engines' => true, 'show_email' => false];
@endphp

<div>
    @if (auth()->guest())
        <x-container>
            <x-marketing.elements.heading
                level="h2"
                class="mt-5"
                :title="$this->user->name"
                :description="'Currently viewing ' . $this->user->username . '\'s profile'"
                align="left"
            />

            <div class="flex lg:flex-row flex-col pb-20 pt-10 space-x-5 h-full">
                @include('theme::pages.profile.partials.content')
            </div>
        </x-container>
    @else
        <x-app.container>
            <div class="flex lg:flex-row flex-col space-x-5 h-full">
                @include('theme::pages.profile.partials.content')
            </div>
        </x-app.container>
    @endif
</div>
