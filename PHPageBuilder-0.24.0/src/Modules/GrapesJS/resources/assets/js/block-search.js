
$(document).on("input", "#block-search input", function() {
    let term = $(this).val().toLowerCase();

    $(".gjs-block-category").each(function() {
        let atLeastOneMatch = false;

        $(this).find(".gjs-block").each(function() {
            if (! $(this).data('original-html')) {
                $(this).data('original-html', $(this).html());
            }

            let label = $(this).text();
            if (label.toLowerCase().includes(term)) {
                $(this).removeClass("d-none");
                atLeastOneMatch = true;

                let regEx = new RegExp('(' + term + ')', "gi");
                let highlightedText = label.replace(regEx, '<b>$1</b>');

                $(this).find(".gjs-block-label").html(
                    $(this).data('original-html').replace(label.trim(), highlightedText)
                );
            } else {
                $(this).addClass("d-none");
            }
        });

        $(this).removeClass("d-none");
        if (! atLeastOneMatch) {
            $(this).addClass("d-none");
        }
    });
});
