@php $plans = Wave\Plan::all() @endphp

<div class="flex flex-col">

	@subscriber
		@if (env('CASHIER_VENDOR') == 'stripe' && optional(auth()->user()->subscription('default'))->onGracePeriod())
			<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 my-4">
				<div class="flex">
					<div class="flex-shrink-0">
					<!-- Heroicon name: solid/exclamation -->
					<svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
						<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
					</svg>
					</div>
					<div class="ml-3">
					<p class="text-sm text-yellow-700">
						Your plan is in grace period.
						<a href="{{ route('stripe.billing-portal') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
							Resume your subscription here
						</a>
						<br>
						<span>
							You have <b>{{ auth()->user()->subscription('default')->ends_at->diffInDays(now()) }} days left.</b>
						</span>
					</p>
					</div>
				</div>
			</div>
		@endif
	@endsubscriber

	@if( auth()->user()->onTrial() )
		<p class="px-6 py-3 text-sm text-red-500 bg-red-100">You are currently on a trial subscription. Select a plan below to upgrade.</p>
	@elseif(auth()->user()->subscribed('main'))
		<h5 class="px-6 py-5 text-sm font-bold text-gray-500 bg-gray-100 border-t border-b border-gray-150">Switch Plans</h5>
	@else
		<h5 class="px-6 py-5 text-sm font-bold text-gray-500 bg-gray-100 border-t border-b border-gray-150">Select a Plan</h5>
	@endif

	<form id="@if(auth()->user()->subscribed('main')){{ 'update-plan-form' }}@else{{ 'payment-form' }}@endif" role="form" method="POST" action="@if(auth()->user()->subscribed('main')){{ route('wave.update_plan') }}@else{{ route('wave.subscribe') }}@endif">
		@include('theme::partials.plans-minimal')

		{{ csrf_field() }}
	</form>


    @include('theme::partials.switch-plans-modal')


</div>
