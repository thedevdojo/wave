<?php
    use function Laravel\Folio\{middleware, name};
    name('subscription.cancelled');
    middleware('auth');
?>

<x-layouts.app>

	<div class="pt-20 mx-auto max-w-7xl text-center prose">
		<h2>You no longer have an active subscription</h2>
		<p>Please <a href="{{ route('wave.settings', 'plans') }}">Subscribe to a Plan</a> to continue using {{ setting('site.title') }}. Thanks!</p>
		<a href="{{ route('wave.settings', 'plans') }}">View Plans</a>
	</div>

</x-layouts.app>
