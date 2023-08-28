<script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>
<script>

    window.vendor_id = parseInt('{{ config("wave.paddle.vendor") }}');

    if(vendor_id){
        Paddle.Setup({
            seller: vendor_id,
            eventCallback: function(data) {
                if (data.name == "checkout.completed") {
                    console.log(data);
                    // Wait 2 seconds to allow Paddle to update their end
                    setTimeout(function(){
                        checkoutComplete(data.data);
                    }, 2000);
                    Paddle.Checkout.close();
                }
            }
        });
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
            let product =  [{
                priceId: plan_id,
                quantity: 1
            }];
            Paddle.Checkout.open({
                items: product,
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
