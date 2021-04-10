import 'alpinejs'
window.axios = require('axios');
window.url = document.querySelector("meta[name='url']").getAttribute("content");
window.csrf = document.querySelector("meta[name='csrf-token']").getAttribute("content");

/** Adds some simple class replacers, see the following article to learn more:
 * https://devdojo.com/tnylea/animating-tailwind-transitions-on-page-load
 */

document.addEventListener("DOMContentLoaded", function(){
    var replacers = document.querySelectorAll('[data-replace]');
    for(var i=0; i<replacers.length; i++){
        let replaceClasses = JSON.parse(replacers[i].dataset.replace.replace(/'/g, '"'));
        Object.keys(replaceClasses).forEach(function(key) {
            replacers[i].classList.remove(key);
            replacers[i].classList.add(replaceClasses[key]);
        });
    }
});


/********** NOTIFICATION FUNCTIONALITY **********/

var markAsRead = document.getElementsByClassName("mark-as-read");
var removedNotifications = 0;
var unreadNotifications =  markAsRead.length;
for (var i = 0; i < markAsRead.length; i++) {
    markAsRead[i].addEventListener('click', function(){
        var notificationId = this.dataset.id;
        var notificationListId = this.dataset.listid;

        var notificationRequest = new XMLHttpRequest();
        notificationRequest.open("POST", url + "/notification/read/" + notificationId, true);
        notificationRequest.onreadystatechange = function () {
            if (notificationRequest.readyState != 4 || notificationRequest.status != 200) return;
            var response = JSON.parse(notificationRequest.responseText);
            document.getElementById('notification-li-' + response.listid).remove();
            removedNotifications += 1;
            var notificationCount = document.getElementById('notification-count');
            if(notificationCount){
                notificationCount.innerHTML = parseInt(notificationCount.innerHTML) - 1;
            }
            if(removedNotifications >= unreadNotifications){
                if(document.getElementById('notification-count')){
                    document.getElementById('notification-count').classList.add('opacity-0');
                }
            }
        };
        notificationRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        notificationRequest.send("_token=" + csrf + "&listid=" + notificationListId );
    });
}

/********** END NOTIFICATION FUNCTIONALITY **********/

/********** START TOAST FUNCTIONALITY **********/

window.popToast = function(type, message){
    document.getElementById('toast').__x.$data.type = type;
    document.getElementById('toast').__x.$data.message = message;
    document.getElementById('toast').__x.$data.show = true;

    setTimeout(function(){
        document.getElementById('toast_bar').classList.remove('w-full');
        document.getElementById('toast_bar').classList.add('w-0');
    }, 150);
    // After 4 seconds hide the toast
    setTimeout(function(){
        document.getElementById('toast').__x.$data.show = false;
        setTimeout(function(){
            document.getElementById('toast_bar').classList.remove('w-0');
            document.getElementById('toast_bar').classList.add('w-full');
        }, 300);
    }, 4000);
}

/********** END TOAST FUNCTIONALITY **********/

/********** Start Billing Checkout Functionality ***********/

/***** Payment Success Functionality */

window.checkoutComplete = function(data) {
    var checkoutId = data.checkout.id;

    Paddle.Order.details(checkoutId, function(data) {
        // Order data, downloads, receipts etc... available within 'data' variable.
        document.getElementById('fullscreenLoaderMessage').innerText = 'Finishing Up Your Order';
        document.getElementById('fullscreenLoader').classList.remove('hidden');
        axios.post('/checkout', { _token: csrf, checkout_id: data.checkout.checkout_id })
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
    });
}

window.checkoutUpdate = function(data){
    if(data.checkout.completed){
        popToast('success', 'Your payment info has been successfully updated.');
    } else {
        popToast('danger', 'Sorry, there seems to be a problem updating your payment info');
    }
}

window.checkoutCancel = function(data){
    let subscriptionId = data.checkout.id;
    axios.post('/cancel', { _token: csrf, id: subscriptionId })
        .then(function (response) {
            if(parseInt(response.data.status) == 1){
                window.location = '/settings/subscription';
            }
    });
}

/***** End Payment Success Functionality */

/********** End Billing Checkout Functionality ***********/


/********** Switch Plans Button Click ***********/

window.switchPlans = function(plan_id, plan_name){
    document.getElementById('switchPlansModal').__x.$data.open = true;
    document.getElementById('switchPlansModal').__x.$data.plan_name = plan_name;
    document.getElementById('switchPlansModal').__x.$data.plan_id = plan_id;
}


/********** Switch Plans Button Click ***********/
