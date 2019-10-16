(function($) {
    $('.savepost-modal-wrapper').click(function() {
        $(this).hide();
    });

    $('.savepost-modal').click(function(e) {
        e.stopPropagation();
    });
})(jQuery);