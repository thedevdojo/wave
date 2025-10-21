<?php

    use function Laravel\Folio\{middleware, name};
    use Livewire\Volt\Component;
    use Livewire\Attributes\Computed;
    name('wave.profile');

    new class extends Component
    {
        public $username;

        #[Computed]
        public function user()
        {
            return config('wave.user_model')::where('username', '=', $this->username)->with('roles')->firstOrFail();
        }
    }
?>

<x-dynamic-component :component="((auth()->guest()) ? 'layouts.marketing' : 'layouts.app')">
    @volt('wave.profile')

        <x-dynamic-component :component="((auth()->guest()) ? 'container' : 'app.container')">

            @guest
                <x-marketing.elements.heading-full
                    :heading="$this->user->name"
                    :description="'Currently viewing ' . $this->user->username . '\'s profile'"
                    align="left"
                    level="h1"
                />
            @endguest

            <div class="flex lg:flex-row flex-col @auth pt-5 @endguest space-y-5 lg:space-y-0 lg:space-x-5 h-full">
                <x-app.card class="flex flex-col items-center justify-center w-full p-12 lg:w-1/4">
                        <x-avatar :src="$this->user->avatar()" :alt="$this->user->name" size="lg" />
                        <h2 class="mt-8 text-2xl font-bold">{{ $this->user->name }}</h2>
                        <p class="my-1 font-medium text-blue-blue">{{ '@' . $this->user->username }}</p>
        
                        @if (auth()->check() && auth()->user()->isAdmin())
                            <a href="{{ route('impersonate', $this->user->id) }}" class="px-3 py-1 my-2 text-xs font-medium text-white rounded text-zinc-600 bg-zinc-200">Impersonate</a>
                        @endif
                        <p class="max-w-lg mx-auto mt-3 text-sm text-center text-zinc-500">{{ $this->user->profile('about') }}</p>
                </x-app.card>

                <x-app.card class="p-12 lg:w-3/4 lg:flex-2">
                    <p class="text-sm text-zinc-600">This is the application user profile page. You can modify this file from your template <strong>resources/themes/drift</strong> at:</p>
                    <code class="inline-block px-2 py-1 mt-2 font-mono text-sm font-medium bg-gray-100 rounded-md text-zinc-600">{{ 'pages/profile/[username].blade.php' }}</code>
                </x-app.card>
            </div>

        </x-dynamic-component>
    @endvolt

</x-dynamic-component>
