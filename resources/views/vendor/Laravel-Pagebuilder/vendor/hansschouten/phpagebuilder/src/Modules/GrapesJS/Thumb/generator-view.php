<style>
html, body {
    padding: 0;
    margin: 0;
}
/* hide block placeholders */
[phpb-hide-if-not-editable] {
    display:none;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script>
$(document).ready(function() {
    html2canvas($("body"), {
        allowTaint: false,
        useCORS: true,
        height: $("body").outerHeight(),
        onrendered: function(canvas) {
            let data = canvas.toDataURL();
            //document.body.append(canvas);
            $.ajax({
                type: "POST",
                url: "<?= phpb_url('pagebuilder', ['route' => 'thumb_generator', 'action' => 'upload']) ?>",
                data: {
                    block: "<?= $blockSlug ?>",
                    data: data
                },
                success: function() {
                    window.parent.postMessage("thumb-saved", '*');
                },
                error: function() {
                }
            });
        }
    });

});
</script>
