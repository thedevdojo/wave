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

<x-dynamic-component :component="((auth()->guest()) ? 'layouts.marketing' : 'layouts.app')" bodyClass="bg-zinc-50">
    @volt('wave.profile')

        <x-dynamic-component :component="((auth()->guest()) ? 'container' : 'app.container')">

            @guest
                <x-marketing.elements.heading
                    level="h2"
                    class="mt-5"
                    :title="$this->user->name"
                    :description="'Currently viewing ' . $this->user->username . '\'s profile'" 
                    align="left"
                />
            @endguest

            <div class="flex lg:flex-row flex-col @guest pb-20 pt-10 @endguest space-x-5 h-full">
                <x-card class="flex flex-col justify-center items-center p-10 w-full lg:w-1/3">
                        <img src="{{ $this->user->avatar() }}" class="w-24 h-24 rounded-full border-4 border-zinc-200">
                        <h2 class="mt-8 text-2xl font-bold dark:text-zinc-100">{{ $this->user->name }}</h2>
                        <p class="my-1 font-medium text-blue-blue">{{ '@' . $this->user->username }}</p>

                        @if (auth()->check() && auth()->user()->isAdmin())
                            <a href="{{ route('impersonate', $this->user->id) }}" class="px-3 py-1 my-2 text-xs font-medium text-white rounded text-zinc-600 bg-zinc-200">Impersonate</a>
                        @endif
                        <p class="mx-auto max-w-lg text-base text-center text-zinc-500">{{ $this->user->profile('about') }}</p>
                </x-card>

                <x-card class="lg:p-10 lg:text-left text-center lg:w-2/3 lg:flex-2">
                    <p class="text-sm text-zinc-600">This is the application user profile page.</p>
                    <p class="mt-2 text-sm text-zinc-600">You can modify this file from your template <strong>resources/themes/anchor</strong> at:</p>
                    <code class="inline-block px-2 py-1 mt-2 font-mono text-sm font-medium bg-gray-100 rounded-md text-zinc-600">{{ 'pages/profile/[username].blade.php' }}</code>
                </x-card>
            </div>

        </x-dynamic-component>
    @endvolt

</x-dynamic-component>
