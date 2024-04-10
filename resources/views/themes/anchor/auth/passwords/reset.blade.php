<x-layouts.marketing
    bodyClass="bg-zinc-50"
>

    <div class="flex flex-col justify-center py-20 sm:px-6 lg:px-8">
        @php $renderedDescription = blade('<x-link :href="route(\'login\')">login</x-link>'); @endphp
        <x-marketing.elements.heading
            title="Setup Your New Password"
            description="or, return to {!! $renderedDescription !!}"
        />

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            @if (session('status'))
                <div class="mb-3 uk-alert-primary">
                    {{ session('status') }}
                </div>
            @endif
            <div class="px-4 py-8 bg-white border shadow border-zinc-50 sm:rounded-lg sm:px-10">
                <form action="{{ route('password.request') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
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

                    <div class="flex flex-col justify-center items-center text-sm leading-5">
                        <x-button type="submit" class="md:!w-full" size="lg">Reset Password</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.marketing>
