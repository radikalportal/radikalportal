(function($) {
    $(document).ready(function() {
        $('.ngg-pro-masonry').each(function() {
            var $self = $(this);
            $self.masonry({
                itemSelector: '.ngg-pro-masonry-item',
                gutterWidth: parseInt(nextgen_pro_masonry_settings.gutterWidth),
                columnWidth: parseInt(nextgen_pro_masonry_settings.columnWidth)
            });
        });
    });
})(jQuery);
