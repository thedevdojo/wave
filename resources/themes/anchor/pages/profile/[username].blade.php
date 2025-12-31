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
            $user = config('wave.user_model')::where('username', '=', $this->username)->with('roles')->firstOrFail();
            
            // Check privacy settings
            $privacySettings = $user->privacy_settings ?? ['profile_visibility' => 'public'];
            
            // If profile is private and viewer is not the owner, abort
            if ($privacySettings['profile_visibility'] === 'private' && (!auth()->check() || auth()->id() !== $user->id)) {
                abort(404);
            }
            
            return $user;
        }
    }
?>

@php
    $privacySettings = null;
@endphp

<x-dynamic-component :component="((auth()->guest()) ? 'layouts.marketing' : 'layouts.app')" bodyClass="bg-zinc-50">
    @volt('wave.profile')
        @php
            $privacySettings = $this->user->privacy_settings ?? ['allow_search_engines' => true, 'show_email' => false];
        @endphp

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
                        @if($privacySettings['show_email'] ?? false)
                            <p class="text-sm text-zinc-500">{{ $this->user->email }}</p>
                        @endif

                        @if (auth()->check() && auth()->user()->isAdmin())
                            <a href="{{ route('impersonate', $this->user->id) }}" class="px-3 py-1 my-2 text-xs font-medium text-white rounded text-zinc-600 bg-zinc-200">Impersonate</a>
                        @endif
                        <p class="mx-auto max-w-lg text-base text-center text-zinc-500">{{ $this->user->profile('about') }}</p>

                        @if(!empty($this->user->social_links))
                            <div class="flex gap-3 mt-6">
                                @if(!empty($this->user->social_links['twitter']))
                                    <a href="{{ $this->user->social_links['twitter'] }}" target="_blank" rel="noopener noreferrer" class="text-zinc-500 hover:text-zinc-900 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($this->user->social_links['linkedin']))
                                    <a href="{{ $this->user->social_links['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="text-zinc-500 hover:text-zinc-900 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($this->user->social_links['github']))
                                    <a href="{{ $this->user->social_links['github'] }}" target="_blank" rel="noopener noreferrer" class="text-zinc-500 hover:text-zinc-900 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($this->user->social_links['website']))
                                    <a href="{{ $this->user->social_links['website'] }}" target="_blank" rel="noopener noreferrer" class="text-zinc-500 hover:text-zinc-900 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                    </a>
                                @endif
                                @if(!empty($this->user->social_links['youtube']))
                                    <a href="{{ $this->user->social_links['youtube'] }}" target="_blank" rel="noopener noreferrer" class="text-zinc-500 hover:text-zinc-900 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($this->user->social_links['instagram']))
                                    <a href="{{ $this->user->social_links['instagram'] }}" target="_blank" rel="noopener noreferrer" class="text-zinc-500 hover:text-zinc-900 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    </a>
                                @endif
                            </div>
                        @endif
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
