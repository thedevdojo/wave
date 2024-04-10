<x-layouts.marketing
    bodyClass="bg-zinc-50"
>

    <div class="flex flex-col justify-center py-20 sm:px-6 lg:px-8">
        @php $renderedDescription = blade('<x-link :href="route(\'register\')">signup here</x-link>'); @endphp
        <x-marketing.elements.heading
            title="Login Below"
            description="or, you can {!! $renderedDescription !!}"
        />
        
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="px-4 py-8 bg-white border shadow border-zinc-50 sm:rounded-lg sm:px-10">
                <form action="#" method="POST">
                    @csrf
                    <div class="space-y-2">
                        @if(setting('auth.email_or_username') && setting('auth.email_or_username') == 'username')
                            <x-label for="username">Username</x-label>
                            <x-input id="username" name="username" required autofocus />

                            @if ($errors->has('username'))
                                <div class="mt-1 text-red-500">{{ $errors->first('username') }}</div>
                            @endif
                        @else
                            <x-label for="email">Email Address</x-label>
                            <x-input id="email" type="email" name="email" required autofocus />

                            @if ($errors->has('email'))
                                <div class="mt-1 text-red-500">{{ $errors->first('email') }}</div>
                            @endif
                        @endif
                    </div>

                    <div class="mt-6 space-y-2">
                        <x-label for="password">Password</x-label>
                        <x-input id="password" type="password" name="password" required />
                        @if ($errors->has('password'))
                            <div class="mt-1 text-red-500">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <x-checkbox name="remember">Remember me</x-checkbox>
                        <div class="text-sm leading-5">
                            <x-link :href="route('password.request')">Forgot your password?</x-link>
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-button type="submit" class="md:!w-full" size="lg">Sign in</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.marketing>
