<div class="flex flex-col">
	<label class="block mb-2 text-sm font-medium leading-5 text-gray-700">Cardholders Name</label>
	<input type="text" class="w-full form-input" name="cardholder" id="cardholder-name">
	<label for="card-element" class="block mt-6 mb-2 text-sm font-medium leading-5 text-gray-700">
		Credit or debit card
	</label>
	<div id="card-element" class="w-full form-input">
		<!-- A Stripe Element will be inserted here. -->
	</div>

	<!-- Used to display form errors. -->
	<div id="card-errors" class="mt-2 text-sm text-red-500" role="alert"></div>
</div>


<script src="https://js.stripe.com/v3/"></script>
<script>

	// Create a Stripe client.
	@if(env('STRIPE_MODE') == 'live')
		var stripe = Stripe('{{ env('STRIPE_LIVE_KEY') }}');
	@else
		var stripe = Stripe('{{ env('STRIPE_TEST_KEY') }}');
	@endif

	// Create an instance of Elements.
	var elements = stripe.elements();
	var cardholderName = document.getElementById('cardholder-name');

	// Custom styling can be passed to options when creating an Element.
	// (Note that this demo uses a wider set of styles than the guide below.)
	var style = {
		base: {
		color: '#32325d',
		lineHeight: '28px',
		fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
		fontSmoothing: 'antialiased',
		fontSize: '16px',
		'::placeholder': {
			color: '#aab7c4'
		}
		},
		invalid: {
		color: '#fa755a',
		iconColor: '#fa755a'
		}
	};

	// Create an instance of the card Element.
	var card = elements.create('card', {style: style});

	// Add an instance of the card Element into the `card-element` <div>.
	card.mount('#card-element');

	// Handle real-time validation errors from the card Element.
	card.addEventListener('change', function(event) {
		var displayError = document.getElementById('card-errors');
		if (event.error) {
		displayError.textContent = event.error.message;
		} else {
		displayError.textContent = '';
		}
	});

	// Handle form submission.
	var form = document.getElementById('payment-form');
	form.addEventListener('submit', function(event) {
		event.preventDefault();

		stripe.confirmCardSetup(
			"{{ $intent ?? ''->client_secret }}",
			{
			payment_method: {
				card: card,
				billing_details: {
					name: cardholderName.value,
				},
			},
			}
		).then(function(result) {
			if (result.error) {
			// Display error.message in your UI.
			} else {
				stripeTokenHandler(result.setupIntent.payment_method);
			}
		});
	});

	function stripeTokenHandler(paymentMethod) {
		// Insert the token ID into the form so it gets submitted to the server
		var form = document.getElementById('payment-form');
		var hiddenInput = document.createElement('input');
		hiddenInput.setAttribute('type', 'hidden');
		hiddenInput.setAttribute('name', 'paymentMethod');
		hiddenInput.setAttribute('value', paymentMethod);
		form.appendChild(hiddenInput);

		// Submit the form
		form.submit();
	}
</script>