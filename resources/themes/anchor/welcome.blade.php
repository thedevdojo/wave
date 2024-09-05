@extends('theme::layouts.app')

@section('content')

	<div class="py-20 mx-auto max-w-7xl text-center bg-[#4545DD]">
        <div class="space-y-2 w-full">
            <h1 class="mb-5 text-5xl font-medium">Welcome Aboard!</h1>
            <p class="py-0 my-0">Thanks for subscribing and welcome aboard.

                @if(Request::get('complete')){{ 'Please finish completing your profile information below.' }} @endif</p>
            <p class="py-0 my-0">This file can be modified inside of your <x-code-inline>resources/views/{{ theme_folder('/welcome.blade.php') }}</x-code-inline> file ✌️</p>
        </div>

        @if(Request::get('complete'))
            <div class="flex flex-col justify-center py-10 sm:py-5 sm:px-6 lg:px-8">
                <div class="mt-8 text-left sm:mx-auto sm:w-full sm:max-w-md">
                    <div class="px-4 py-8 bg-white border shadow border-zinc-50 sm:rounded-lg sm:px-10">
                        <form role="form" method="POST" action="{{ route('wave.register-complete') }}">
                            @csrf
                            <!-- If we want the user to purchase before they can create an account -->

                            <div class="pb-3 sm:border-b sm:border-zinc-200">
                                <h3 class="text-lg font-medium leading-6 text-zinc-900">
                                    Profile
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm leading-5 text-zinc-500">
                                    Finish filling out your profile information.
                                </p>
                            </div>

                            @csrf

                            <div class="mt-6">
                                <label for="name" class="block text-sm font-medium leading-5 text-zinc-700">
                                    Name
                                </label>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <input id="name" type="text" name="name" required class="w-full form-input" value="{{ old('name') }}" autofocus>
                                </div>
                                @if ($errors->has('name'))
                                    <div class="mt-1 text-red-500">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>

                            @if(setting('auth.username_in_registration') && setting('auth.username_in_registration') == 'yes')
                                <div class="mt-6">
                                    <label for="username" class="block text-sm font-medium leading-5 text-zinc-700">
                                        Username
                                    </label>
                                    <div class="mt-1 rounded-md shadow-sm">
                                        <input id="username" type="text" name="username" value="@if(old('username')){{ old('username') }}@else{{ auth()->user()->username }}@endif" required class="w-full form-input">
                                    </div>
                                    @if ($errors->has('username'))
                                        <div class="mt-1 text-red-500">
                                            {{ $errors->first('username') }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-6">
                                <label for="password" class="block text-sm font-medium leading-5 text-zinc-700">
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

                            <div class="flex flex-col justify-center items-center text-sm leading-5">
                                <span class="block mt-5 w-full rounded-md shadow-sm">
                                    <button type="submit" class="flex justify-center px-4 py-2 w-full text-sm font-medium text-white bg-blue-600 rounded-md border border-transparent transition duration-150 ease-in-out hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-wave active:bg-blue-700">
                                        Submit
                                    </button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        @else
            <div class="justify-center items-center mt-12 w-full text-center">
                <a href="{{ route('wave.dashboard') }}" class="inline-block px-4 py-2 w-auto text-sm font-medium text-white bg-blue-600 rounded-md border border-transparent transition duration-150 ease-in-out hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-wave active:bg-blue-700">
                    Go to my Dashboard
                </a>
            </div>
        @endif

	</div>

@endsection
