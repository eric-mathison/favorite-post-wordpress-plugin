(function($) {
    $('.savepost-modal-wrapper').click(function() {
        $(this).hide();
    });

    $('.savepost-modal').click(function(e) {
        e.stopPropagation();
    });

    $('.saved-post span.delete').click(function(e) {
        e.preventDefault();
        var postID = $(this).closest('.saved-post').data('id');
        $(this).parent().addClass('deleted');
        $(this).parent().click(function(e) {
            e.preventDefault();
        });
        $(this).hide();
        var data = {
            action: 'deletesaved_ajax',
            postID: postID
        };
        jQuery.post(recentpostsAjax.url, data, function(response) {
            console.log(response);
        });
    });
})(jQuery);