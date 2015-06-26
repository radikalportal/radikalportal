// Append iframe to galleria container
(function($) {
    $(document).ready(function () {
        $('.galleria').each(function() {
            var $this = $(this);
            var id = $this.attr('id').match(/^displayed_gallery_(\w+$)/).pop();
            var $iframe = $('<iframe/>').attr({
                src: galleries['gallery_'+id].iframe_url,
                width: $this.parent().width(),
                frameborder: 0,
                marginheight: 0,
                marginwidth: 0,
                name: id,
                scrolling: 'no',
                seamless: true,
                allowfullscreen: 'yes',
                webkitallowfullscreen: 'yes',
                mozallowfullscreen: 'yes',
                'class': 'galleria-iframe',
                style: 'visibility: hidden',
                onload: 'this.style.visibility="visible"'
            });
            $iframe.css({
                'margin': '0px',
                'padding': '0px',
                'border': 'none'
            });
            $this.prepend($iframe);
        });
    });
})(jQuery);