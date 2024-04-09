<?php
	// use a dynamic layout based on whether or not the user is authenticated
    $layout = ((auth()->guest()) ? 'layouts.marketing' : 'layouts.app');
?>

<x-dynamic-component 
	:component="$layout"
	bodyClass="bg-zinc-50"
>

	<x-container class="flex space-x-5">

		<x-card class="flex flex-col justify-center items-center p-10 w-full lg:w-1/3">
				<img src="{{ $user->avatar }}" class="w-24 h-24 rounded-full border-4 border-zinc-200">
				<h2 class="mt-8 text-2xl font-bold">{{ $user->name }}</h2>
				<p class="my-1 font-medium text-blue-blue">{{ '@' . $user->username }}</p>
				<div class="px-3 py-1 my-2 text-xs font-medium text-white rounded text-zinc-600 bg-zinc-200">{{ $user->roles->first()->name }}</div>
				<p class="mx-auto mt-3 max-w-lg text-base text-center text-zinc-500">{{ $user->profile('about') }}</p>
		</x-card>

		<x-card class="p-10 lg:w-2/3 lg:flex-2">
			<p class="text-base text-zinc-600">Your application info about {{ $user->name }} here</p>
		    <p class="mt-5 text-sm text-zinc-600">You can edit this template inside of <code class="px-2 py-1 font-mono text-base font-medium bg-indigo-100 rounded-md text-zinc-600">resources/views/{{ theme_folder('/profile.blade.php') }}</code></p>
		</x-card>

	</x-container>

</x-dynamic-component>