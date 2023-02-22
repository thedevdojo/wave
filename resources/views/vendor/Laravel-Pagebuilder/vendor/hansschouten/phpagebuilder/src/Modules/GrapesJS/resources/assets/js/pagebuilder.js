$(document).ready(function() {

    $(".gjs-editor").append($("#toggle-sidebar"));
    $(".gjs-pn-panels").prepend($("#sidebar-header"));
    $(".gjs-pn-panels").append($("#sidebar-bottom-buttons"));

    $("#toggle-sidebar").click(function() {
        $("#gjs").toggleClass('sidebar-collapsed');
        triggerEditorResize();
    });
    autoCollapseSidebar();

    window.editor.on('run:open-sm', function(editor) {
        $(".gjs-trt-traits").parent().parent().css('display', 'none');
        $(".gjs-sm-sectors").parent().parent().css('display', 'block');
        // move element classes editor to advanced section
        $("#gjs-sm-advanced .gjs-sm-properties").append($(".gjs-clm-tags"));
    });
    window.editor.on('run:open-tm', function(editor) {
        $(".gjs-sm-sectors").parent().parent().css('display', 'none');
        $(".gjs-trt-traits").parent().parent().css('display', 'block');
    });

    window.editor.on('block:drag:start', function(block) {
        autoCollapseSidebar();
    });

    function autoCollapseSidebar() {
        if ($(window).width() < 1000) {
            $("#gjs").addClass('sidebar-collapsed');
            triggerEditorResize();
        }
    }

    function triggerEditorResize() {
        window.editor.trigger('change:canvasOffset canvasScroll');
    }

    // prevent exiting page builder with backspace button
    let backspaceIsPressed = false;
    $(document).keydown(function(event) {
        if (event.which === 8) backspaceIsPressed = true;
    }).keyup(function(event) {
        if (event.which === 8) backspaceIsPressed = false;
    });
    $(window).on('beforeunload', function(event) {
        if (backspaceIsPressed) event.preventDefault();
    });
});

function addBlockSearch() {
    $(".gjs-blocks-cs").prepend($("#block-search"));
}

// listen to messages from iframe
window.addEventListener("message", onMessage, false);

function onMessage(event) {
    // if the page is loaded, remove loading element
    if (event.data === 'page-loaded') {
        $("#phpb-loading").addClass('loaded');
        addBlockSearch();
        window.isLoaded = true;
        $(window).trigger('pagebuilder-page-loaded');
    } else if(event.data === 'touch-start') {
        window.touchStart();
    }
}
