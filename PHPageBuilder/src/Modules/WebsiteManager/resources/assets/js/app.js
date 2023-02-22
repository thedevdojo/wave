$(function() {
    window.addEventListener("message", onMessage, false);

    function onMessage(event) {
        if (event.data === 'thumb-saved') {
            $("iframe.thumb-renderer").attr("src", $("iframe.thumb-renderer").attr('src'));
        }
    }

    $('select').selectpicker();
});
