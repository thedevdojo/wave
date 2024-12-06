@extends('theme::layouts.app')

@section('content')
<div class="flex flex-col justify-center py-20 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-3xl font-extrabold leading-9 text-center text-gray-900 lg:text-5xl">
            Verify Your Email
        </h2>
        <p class="mt-4 text-sm leading-5 text-center text-gray-600 max-w">
            Please check your email for the verification link.
            If you didn't receive the email, you can request a new one.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="px-4 py-8 bg-white border shadow border-gray-50 sm:rounded-lg sm:px-10">
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <div class="mt-6">
                    <span class="block w-full rounded-md shadow-sm">
                        <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out border border-transparent rounded-md bg-wave-600 hover:bg-wave-500 focus:outline-none focus:border-wave-700 focus:shadow-outline-wave active:bg-wave-700">
                            Resend Verification Email
                        </button>
                    </span>
                </div>
            </form>

            <div class="mt-6">
                <p class="text-sm text-center">
                    <a href="{{ route('login') }}" class="font-medium transition duration-150 ease-in-out text-wave-600 hover:text-wave-500 focus:outline-none focus:underline">
                        Return to login
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
