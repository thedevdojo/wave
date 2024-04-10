<x-layouts.marketing
    bodyClass="bg-zinc-50"
>

    <div class="flex flex-col justify-center py-20 sm:px-6 lg:px-8">
        @php $renderedDescription = blade('<x-link :href="route(\'login\')">login</x-link>'); @endphp
        <x-marketing.elements.heading
            title="Reset Password"
            description="or, return back to {!! $renderedDescription !!}"
        />

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            @if (session('status'))
                <div class="p-3 mb-3 text-sm text-indigo-500 bg-indigo-100">
                    {{ session('status') }}
                </div>
            @endif
            <div class="px-4 py-8 bg-white border shadow border-zinc-50 sm:rounded-lg sm:px-10">
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div class="space-y-2">
                        <x-label for="email">Email Address</x-label>
                        <x-input id="email" type="email" name="email" required autofocus />

                        @if ($errors->has('email'))
                            <div class="mt-1 text-red-500">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <x-button type="submit" class="md:!w-full" size="lg">Send Password Reset Link</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.marketing>
