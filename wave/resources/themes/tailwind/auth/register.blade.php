@extends('theme::layouts.app')

@section('content')


    <div class="sm:mx-auto sm:w-full sm:max-w-md sm:pt-10">
        <h2 class="text-3xl font-extrabold leading-9 text-center text-gray-900 sm:mt-6 lg:text-5xl">
            Sign up Below
        </h2>
        <p class="mt-4 text-sm leading-5 text-center text-gray-600 max-w">
            or, you can
            <a href="{{ route('login') }}" class="font-medium transition duration-150 ease-in-out text-wave-600 hover:text-wave-500 focus:outline-none focus:underline">
                login here
            </a>
        </p>
    </div>

    <div class="flex flex-col justify-center pb-10 sm:pb-20 sm:px-6 lg:px-8">


        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="px-4 py-8 bg-white border shadow border-gray-50 sm:rounded-lg sm:px-10">
                <form role="form" method="POST" action="@if(setting('billing.card_upfront')){{ route('wave.register-subscribe') }}@else{{ route('register') }}@endif">
                    @csrf
                    <!-- If we want the user to purchase before they can create an account -->

                    <div class="pb-3 sm:border-b sm:border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Profile
                        </h3>
                        <p class="max-w-2xl mt-1 text-sm leading-5 text-gray-500">
                            Information about your account.
                        </p>
                    </div>

                    @csrf

                    <div class="mt-6">
                        <label for="name" class="block text-sm font-medium leading-5 text-gray-700">
                            Name
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input id="name" type="text" name="name" required class="w-full form-input" value="{{ old('name') }}" @if(!setting('billing.card_upfront')){{ 'autofocus' }}@endif>
                        </div>
                        @if ($errors->has('name'))
                            <div class="mt-1 text-red-500">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>

                    @if(setting('auth.username_in_registration') && setting('auth.username_in_registration') == 'yes')
                        <div class="mt-6">
                            <label for="username" class="block text-sm font-medium leading-5 text-gray-700">
                                Username
                            </label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <input id="username" type="text" name="username" value="{{ old('username') }}" required class="w-full form-input">
                            </div>
                            @if ($errors->has('username'))
                                <div class="mt-1 text-red-500">
                                    {{ $errors->first('username') }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-6">
                        <label for="email" class="block text-sm font-medium leading-5 text-gray-700">
                            Email Address
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full form-input">
                        </div>
                        @if ($errors->has('email'))
                            <div class="mt-1 text-red-500">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <label for="password" class="block text-sm font-medium leading-5 text-gray-700">
                            Password
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input id="password" type="password" name="password" required class="w-full form-input">
                        </div>
                        @if ($errors->has('password'))
                            <div class="mt-1 text-red-500">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <label for="password_confirmation" class="block text-sm font-medium leading-5 text-gray-700">
                            Confirm Password
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full form-input">
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <div class="mt-1 text-red-500">
                                {{ $errors->first('password_confirmation') }}
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col items-center justify-center text-sm leading-5">
                        <span class="block w-full mt-5 rounded-md shadow-sm">
                            <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out border border-transparent rounded-md bg-wave-600 hover:bg-wave-500 focus:outline-none focus:border-wave-700 focus:shadow-outline-wave active:bg-wave-700">
                                Register
                            </button>
                        </span>
                        <a href="{{ route('login') }}" class="mt-3 font-medium transition duration-150 ease-in-out text-wave-600 hover:text-wave-500 focus:outline-none focus:underline">
                            Already have an account? Login here
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
