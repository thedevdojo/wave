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

window.searchFocused = function(focused){
    if(focused){
        document.getElementById('sidebar').classList.remove('overflow-scroll');
        document.getElementById('bg-fade').classList.remove('invisible');
        document.getElementById('bg-fade').classList.remove('opacity-0');
        document.getElementById('bg-fade').classList.add('opacity-25');
    } else {
        document.getElementById('sidebar').classList.add('overflow-scroll');
        document.getElementById('bg-fade').classList.add('invisible');
        document.getElementById('bg-fade').classList.add('opacity-0');
        document.getElementById('bg-fade').classList.remove('opacity-25');
    }
}
