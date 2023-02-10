@extends('theme::layouts.app')

@section('content')

<div class="flex flex-col justify-center py-20 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-3xl font-extrabold leading-9 text-center text-gray-900 lg:text-5xl">
                Reset Password
            </h2>
            <p class="mt-4 text-sm leading-5 text-center text-gray-600 max-w">
                or, return back to
                <a href="{{ route('login') }}" class="font-medium transition duration-150 ease-in-out text-wave-600 hover:text-wave-500 focus:outline-none focus:underline">
                    login
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">

            @if (session('status'))
                <div class="p-3 mb-3 text-sm text-indigo-500 bg-indigo-100">
                    {{ session('status') }}
                </div>
            @endif

            <div class="px-4 py-8 bg-white border shadow border-gray-50 sm:rounded-lg sm:px-10">
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium leading-5 text-gray-700">
                            Email Address
                        </label>
                        <div class="mt-3 rounded-md shadow-sm">
                            <input id="email" type="email" name="email" required class="w-full form-input">
                        </div>
                        @if ($errors->has('email'))
                            <div class="mt-1 text-red-500">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <span class="block w-full rounded-md shadow-sm">
                            <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out border border-transparent rounded-md bg-wave-600 hover:bg-wave-500 focus:outline-none focus:border-wave-700 focus:shadow-outline-wave active:bg-wave-700">
                                Send Password Reset Link
                            </button>
                        </span>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection
