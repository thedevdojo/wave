import 'alpinejs';

window.inIframe = function() {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
}

document.addEventListener('DOMContentLoaded', function(){
    if(inIframe()){
        hideIframeElements();
    }
});

window.hideIframeElements = function(){
    document.getElementById('backToSiteBtn').classList.add('hidden');
}
