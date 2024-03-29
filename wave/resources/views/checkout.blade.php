<script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>
<script>

    window.client_side_token = '{{ config("wave.paddle.client_side_token") }}';
     
    Paddle.Initialize({ 
        token: client_side_token,
        checkout: {
            settings: {
                displayMode: "overlay",
                frameStyle: "width: 100%; min-width: 312px; background-color: transparent; border: none;",
                locale: "en",
                allowLogout: false
            }
        },
        eventCallback: function(data) {
            if (data.name == "checkout.completed") {
                checkoutComplete(data.data);
            }
        }
    });

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
        if(client_side_token){
            let product =  [{
                priceId: plan_id,
                quantity: 1
            }];
            customer = {};
            @if(!auth()->guest())
                customer = {
                    email: '{{ auth()->user()->email }}'
                };
            @endif
            Paddle.Checkout.open({
                items: product,
                // Customer might be null if the user is not logged in
                customer: customer || null,
                successCallback: "checkoutComplete",
            });
        } else {
            alert('Paddle API keys and tokens must be set');
        }
    }

    function waveUpdate(){
        Paddle.Checkout.open({
            override: this.dataset.url,
            successCallback: "checkoutUpdate",
        });
    }

    function waveCancel(){
        axios.post('/cancel', { _token: csrf })
            .then(function (response) {
                if(parseInt(response.data.status) == 1){
                    window.location = '/settings/subscription';
                }
        });
    }

    window.checkoutComplete = function(data) {
        var checkoutId = data.transaction_id;
        
        addCheckoutOverlay();
        Paddle.Checkout.close();

        axios.post('/checkout', { _token: csrf, checkout_id: checkoutId })
            .then(function (response) {
                console.log(response);


                if(parseInt(response.data.status) == 1){
                    let queryParams = '';
                    if(parseInt(response.data.guest) == 1){
                        queryParams = '?complete=true';
                    }
                    window.location = '/checkout/welcome' + queryParams;
                }
        });
    }

    window.addCheckoutOverlay = function(){
        let overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        
        overlay.style.zIndex = '9999';

        let loader = document.createElement('div');
        loader.innerHTML = `<div class="flex flex-col items-center justify-center text-sm font-medium text-white">
                <svg class="w-5 h-5 mb-3 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <p>Finishing Checkout</p>
            </div>`;

        overlay.appendChild(loader);
        document.body.appendChild(overlay);
    }

    window.checkoutUpdate = function(data){
        if(data.checkout.completed){
            popToast('success', 'Your payment info has been successfully updated.');
        } else {
            popToast('danger', 'Sorry, there seems to be a problem updating your payment info');
        }
    }

    window.checkoutCancel = function(data){
        let subscriptionId = data.id;
        axios.post('/cancel', { _token: csrf, id: subscriptionId })
            .then(function (response) {
                if(parseInt(response.data.status) == 1){
                    window.location = '/settings/subscription';
                }
        });
    }

</script>
