@extends('theme::layouts.app')


@section('content')

	<div class="max-w-4xl px-5 mx-auto mt-10 lg:px-0">
        <a href="{{ route('wave.dashboard') }}" class="flex items-center mb-6 font-mono text-sm font-bold leading-tight cursor-pointer text-wave-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to the dashboard
        </a>
    </div>

	<div class="max-w-4xl px-5 mx-auto mt-10 lg:px-0">



		<h1 class="flex items-center text-3xl font-bold text-gray-700">
			<svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
			All Notifications
		</h1>
		<div class="uk-align-center">
			@include('theme::partials.notifications', ['show_all_notifications' => true])
		</div>

	</div>

@endsection