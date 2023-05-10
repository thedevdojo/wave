<script src="https://js.stripe.com/v3/"></script>

<script>

    if(!"{{ config('payment.stripe.key') }}"){
        alert('Stripe key is not set, please see the docs and learn how to setup billing.');
    }

    var stripe = Stripe("{{ config('payment.stripe.key') }}");

    let checkoutBtns = document.getElementsByClassName("checkout");
    for( var i=0; i < checkoutBtns.length; i++ ){
        checkoutBtns[i].addEventListener('click', function(){
            redirectStripeCheckout(this.dataset.plan)
        }, false);
    }

    let updateBtns = document.getElementsByClassName("checkout-update");
    for( var i=0; i < updateBtns.length; i++ ){
        updateBtns[i].addEventListener('click', billingPortal, false);
    }

    let cancelBtns = document.getElementsByClassName("checkout-cancel");
    for( var i=0; i < cancelBtns.length; i++ ){
        cancelBtns[i].addEventListener('click', billingPortal, false);
    }

    function billingPortal() {
        window.location.href = "{{ route('stripe.billing-portal') }}"
    }

    function redirectStripeCheckout(planId) {

        document.getElementById('fullscreenLoaderMessage').innerText = 'Starting your journey...';
        document.getElementById('fullscreenLoader').classList.remove('hidden');

        var data = {
            planId: planId,
            customer: "@if(!auth()->guest()){{ auth()->user()->stripe_id }}@endif"
        }
        fetch('/stripe/create-checkout-session', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'content-type': "application/json",
            },
        })
            .then(function(response) {
                if (!response.ok) {
                    document.getElementById('fullscreenLoader').classList.add('hidden');
                    return response.json().then(function(errors) {
                        popToast("warning", Object.values(errors.errors)[0][0] ?? 'validation error');
                    });
                }
                return response.json();
            })
            .then(function(session) {
                console.info(session)
                return stripe.redirectToCheckout({ sessionId: session.id });
            })
            .then(function(result) {
                // If `redirectToCheckout` fails due to a browser or network
                // error, you should display the localized error message to your
                // customer using `error.message`.
                if (result.error) {
                    alert(result.error.message);
                }
            });
    }
</script>
