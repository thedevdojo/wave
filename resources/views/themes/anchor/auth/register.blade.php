<x-layouts.marketing
    bodyClass="bg-zinc-50"
>

    <div class="flex flex-col justify-center py-20 sm:px-6 lg:px-8">
        @php $renderedDescription = blade('<x-link :href="route(\'login\')">login here</x-link>'); @endphp
        <x-marketing.elements.heading
            title="Sign up Below"
            description="or, you can {!! $renderedDescription !!}"
        />

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="px-4 py-8 bg-white border shadow border-zinc-50 sm:rounded-lg sm:px-10">
                <form role="form" method="POST" action="@if(setting('billing.card_upfront')){{ route('wave.register-subscribe') }}@else{{ route('register') }}@endif">
                    @csrf

                    <div class="pb-3 sm:border-b sm:border-zinc-200">
                        <h3 class="text-lg font-medium leading-6 text-zinc-900">Profile</h3>
                        <p class="mt-1 max-w-2xl text-sm leading-5 text-zinc-500">Information about your account.</p>
                    </div>

                    <div class="mt-6 space-y-2">
                        <x-label for="name">Name</x-label>
                        <x-input id="name" name="name" required autofocus />
                        @if ($errors->has('name'))
                            <div class="mt-1 text-red-500">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    @if(setting('auth.username_in_registration') && setting('auth.username_in_registration') == 'yes')
                        <div class="mt-6 space-y-2">
                            <x-label for="username">Username</x-label>
                            <x-input id="username" name="username" required autofocus />
                            @if ($errors->has('username'))
                                <div class="mt-1 text-red-500">{{ $errors->first('username') }}</div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-6 space-y-2">
                        <x-label for="email">Email Address</x-label>
                        <x-input id="email" type="email" name="email" required />
                        @if ($errors->has('email'))
                            <div class="mt-1 text-red-500">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="mt-6 space-y-2">
                        <x-label for="password">Password</x-label>
                        <x-input id="password" type="password" name="password" required />
                        @if ($errors->has('password'))
                            <div class="mt-1 text-red-500">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div class="mt-6 space-y-2">
                        <x-label for="password_confirmation">Confirm Password</x-label>
                        <x-input id="password_confirmation" type="password" name="password_confirmation" required />
                        @if ($errors->has('password_confirmation'))
                            <div class="mt-1 text-red-500">{{ $errors->first('password_confirmation') }}</div>
                        @endif
                    </div>

                    <div class="flex flex-col justify-center items-center mt-6 space-y-2 text-sm leading-5">
                        <x-button type="submit" class="md:!w-full" size="lg">Register</x-button>
                        <x-link :href="route('login')">Already have an account? Login here</x-link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.marketing>
