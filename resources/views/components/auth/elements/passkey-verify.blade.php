@props([
    'label' => 'Sign in with a passkey',
    'loadingLabel' => 'Authenticating...',
])

@assets
    @if(config('devdojo.auth.settings.dev_mode'))
        @vite(['vendor/devdojo/auth/resources/js/passkeys.js'])
    @else
        <script src="{{ asset('/auth/build/assets/passkeys.js') }}"></script>
    @endif
@endassets

<div
    x-data="{
        supported: false,
        loading: false,
        error: null,
        updateSupport() {
            this.supported = Boolean(window.Passkeys?.isSupported());
        },
        init() {
            this.updateSupport();
            window.addEventListener('passkeys:ready', () => this.updateSupport(), { once: true });
        },
        async verify() {
            this.loading = true;
            this.error = null;

            try {
                const response = await window.Passkeys.verify({
                    routes: {
                        options: '{{ route('passkey.login-options') }}',
                        submit: '{{ route('passkey.login') }}',
                    },
                });

                const redirect = response?.redirect || '{{ config('devdojo.auth.settings.redirect_after_auth', '/') }}';
                window.location.replace(redirect);
            } catch (e) {
                if (e.constructor?.name !== 'UserCancelledError') {
                    this.error = e.message;
                }
            } finally {
                this.loading = false;
            }
        },
    }"
>
    <template x-if="supported">
        <div>
            <button
                type="button"
                class="flex @if(config('devdojo.auth.settings.center_align_social_provider_button_content')){{ 'justify-center' }}@endif items-center px-4 py-3 space-x-2.5 w-full h-auto text-sm rounded-md border border-zinc-200 text-zinc-600 hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-50"
                x-on:click="verify()"
                x-bind:disabled="loading"
            >
                <span class="w-5 h-5 shrink-0">
                    <x-phosphor-fingerprint class="w-full h-full" />
                </span>
                <span x-show="!loading">{{ $label }}</span>
                <span x-show="loading" x-cloak>{{ $loadingLabel }}</span>
            </button>
            <p x-show="error" x-text="error" x-cloak class="mt-2 text-sm text-center text-red-600"></p>
        </div>
    </template>
</div>
