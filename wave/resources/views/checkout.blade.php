@if(env('CASHIER_VENDOR') == 'stripe')
    <script src="https://js.stripe.com/v3/"></script>

    <script>

        var stripe = Stripe("{{ env('STRIPE_KEY') }}");

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
@else
    <script src="https://cdn.paddle.com/paddle/paddle.js"></script>
    <script>

        window.vendor_id = parseInt('{{ config("wave.paddle.vendor") }}');

        if(vendor_id){
            Paddle.Setup({ vendor: vendor_id });
        }

        if("{{ config('wave.paddle.env') }}" == 'sandbox') {
            Paddle.Environment.set('sandbox');
        }

        let checkoutBtns = document.getElementsByClassName("checkout");
        for( var i=0; i < checkoutBtns.length; i++ ){
            checkoutBtns[i].addEventListener('click', function(){
                waveCheckout(this.dataset.plan)
            }, false);
        }

        let updateBtns = document.getElementsByClassName("checkout-update");
        for( var i=0; i < updateBtns.length; i++ ){
            updateBtns[i].addEventListener('click', waveUpdate, false);
        }

        let cancelBtns = document.getElementsByClassName("checkout-cancel");
        for( var i=0; i < cancelBtns.length; i++ ){
            cancelBtns[i].addEventListener('click', waveCancel, false);
        }


        function waveCheckout(plan_id) {
            if(vendor_id){
                let product = parseInt(plan_id);
                Paddle.Checkout.open({
                    product: product,
                    email: '@if(!auth()->guest()){{ auth()->user()->email }}@endif',
                    successCallback: "checkoutComplete",
                });
            } else {
                alert('Paddle Vendor ID is not set, please see the docs and learn how to setup billing.');
            }
        }

        function waveUpdate(){
            Paddle.Checkout.open({
                override: this.dataset.url,
                successCallback: "checkoutUpdate",
            });
        }

        function waveCancel(){
            Paddle.Checkout.open({
                override: this.dataset.url,
                successCallback: "checkoutCancel",
            });
        }

    </script>
@endif