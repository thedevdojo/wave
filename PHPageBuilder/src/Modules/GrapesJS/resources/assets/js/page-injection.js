/* disable ctrl + s, since this is handled in iframe parent */
window.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        return false;
    }
});

/* post message to iframe parent that page has been loaded */
window.parent.postMessage("page-loaded", '*');

document.addEventListener('touchstart', e => {
    window.parent.postMessage("touch-start", '*');
});
