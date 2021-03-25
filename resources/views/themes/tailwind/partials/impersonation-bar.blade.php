<div id="impersonation-bar">
	<div class="uk-container">
		<p>You are currently impersonating {{ Auth::user()->email }}</p>
		<a href="{{ route('impersonate.leave') }}"><span class="uk-margin-small-right" uk-icon="sign-out"></span> Leave impersonation</a>
	</div>
</div>