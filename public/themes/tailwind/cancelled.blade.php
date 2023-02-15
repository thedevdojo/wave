@extends('theme::layouts.app')

@section('content')

	<div class="pt-20 mx-auto prose text-center max-w-7xl">
		<h2>You no longer have an active subscription</h2>
		<p>Please <a href="{{ route('wave.settings', 'plans') }}">Subscribe to a Plan</a> to continue using {{ setting('site.title') }}. Thanks!</p>
		<a href="{{ route('wave.settings', 'plans') }}">View Plans</a>
	</div>

@endsection
