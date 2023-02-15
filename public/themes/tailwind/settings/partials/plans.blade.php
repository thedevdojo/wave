@php $plans = Wave\Plan::all() @endphp

<div class="flex flex-col">

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
