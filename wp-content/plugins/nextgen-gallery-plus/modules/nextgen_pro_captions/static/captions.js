(function($) {
    // we just can't work with IE8
    if ($.browser.msie && $.browser.version <= 8) {
        return;
    }

    var nggc_get_gallery = function(gallery_id) {
        if ('undefined' == typeof window.galleries) { return null; }
        var retval = null;
        $.each(window.galleries, function(index, gallery) {
            if (gallery.ID == gallery_id) {
                retval = gallery;
            }
        });
        return retval;
    };

    var nggc_get_setting = function(gallery, name, def) {
        var tmp = def;
        if (typeof gallery.display_settings[name] != 'undefined' && gallery.display_settings[name] != '') {
            tmp = gallery.display_settings[name];
        } else {
            tmp = def;
        }
        if (tmp == 1)       tmp = true;
        if (tmp == 0)       tmp = false;
        if (tmp == '1')     tmp = true;
        if (tmp == '0')     tmp = false;
        if (tmp == 'false') tmp = false;
        if (tmp == 'true')  tmp = true;

        return tmp;
    };

    var nggc_allowed_tags = [
        "EM",
        "STRONG",
        'B',
        'DEL',
        'I',
        'INS',
        'MARK',
        'SMALL',
        'STRIKE',
        'SUB',
        'SUP',
        'TT',
        'U'
    ];

    var nggc_sanitize = function(string) {
        var element = document.createElement('div');
        element.innerHTML = string;
        nggc_real_sanitize(element);
        return element.innerHTML;
    };

    var nggc_real_sanitize = function(element) {
        var allowed_tags = Array.prototype.slice.apply(element.getElementsByTagName("*"), [0]);
        for (var i = 0; i < allowed_tags.length; i++) {
            if (nggc_allowed_tags.indexOf(allowed_tags[i].nodeName) == -1) {
                nggc_sanitize_replace(allowed_tags[i]);
            }
        }
    };

    var nggc_sanitize_replace = function(element) {
        var last = element;
        for (var i = element.childNodes.length - 1; i >= 0; i--) {
            var tmp = element.removeChild(element.childNodes[i]);
            element.parentNode.insertBefore(tmp, last);
            last = tmp;
        }
        element.parentNode.removeChild(element);
    };

    nggc_share_icons = {
        strip_html: function(html) {
            var tmp = document.createElement('div');
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || "";
        },

        create: function(target, image) {
            var image_id = image.data('image-id');
            var gallery_id = image.data('nplmodal-gallery-id');

            var base_url = encodeURIComponent(this.get_share_url(gallery_id, image_id));
            var url = encodeURIComponent(window.location.toString());
            var title = this.strip_html(image.data('title'));
            var summary = this.strip_html(image.data('description'));

            var twitter_icon = $('<i/>', {
                'data-href': 'https://twitter.com/share?url=' + this.get_share_url(gallery_id, image_id, 'full'),
                'class': 'ngg-caption-icon fa fa-twitter-square'
            });

            var googlep_icon = $('<i/>', {
                'data-href': 'https://plus.google.com/share?url=' + base_url,
                'class': 'ngg-caption-icon fa fa-google-plus-square'
            });

            var facebook_url = '&p[url]=' + encodeURIComponent(this.get_share_url(gallery_id, image_id, 'full'));
            if (title.length > 0)   facebook_url += '&p[title]=' + title.trim();
            if (summary.length > 0) facebook_url += '&p[summary]=' + summary.trim();
            facebook_url += '&p[images][0]=' + encodeURIComponent(image.attr('href')).trim();
            var facebook_icon = $('<i/>', {
                'data-href': 'https://www.facebook.com/sharer/sharer.php?s=100' + facebook_url,
                'class': 'ngg-caption-icon fa fa-facebook-square'
            });

            var pinterest_url = encodeURIComponent(this.get_share_url(gallery_id, image_id, 'full'));
            pinterest_url += '&url=' + url;
            pinterest_url += '&media=' + image.attr('href');
            pinterest_url += '&description=' + summary;
            var pinterest_icon = $('<i/>', {
                'data-href': 'http://pinterest.com/pin/create/button/?s=100' + pinterest_url,
                'class': 'ngg-caption-icon fa fa-pinterest-square'
            });

            var icons = [twitter_icon, googlep_icon, facebook_icon, pinterest_icon];
            $(icons).each(function() {
                $(this).on('click', function(event) {
                    event.preventDefault();
                    var share_window = window.open($(this).data('href'), '_blank');
                    share_window.focus();
                    // improper, but otherwise the pro lightbox will open
                    return false;
                });
            });
            target.html('').append(icons);
        },
        get_share_url: function(gallery_id, image_id, named_size) {
            if (typeof(named_size) == 'undefined') {
                named_size = 'thumb';
            }

            var base_url = nplModalRouted.get_setting('share_url')
                .replace('{gallery_id}', gallery_id)
                .replace('{image_id}', image_id)
                .replace('{named_size}', named_size);
            var site_url_link = $('<a/>').attr('href', nplModalRouted.get_setting('wp_site_url'))[0];
            var parent_link = $('<a/>').attr('href', window.location.toString())[0];
            var base_link = $('<a/>').attr('href', base_url)[0];

            // check if site is in a sub-directory and shorten the prefix
            if (parent_link.pathname.indexOf(site_url_link.pathname) >= 0) {
                parent_link.pathname = parent_link.pathname.substr(site_url_link.pathname.length);
            }
            // shorten url by removing their common prefix
            if (parent_link.pathname.indexOf(base_link.pathname) >= 0) {
                parent_link.pathname = parent_link.pathname.substr(parent_link.pathname.length);
            }

            // this is odd but it's just how the 'share' controller expects it
            base_link.search = parent_link.search;
            if (base_link.search.length > 0) {
                base_link.search += '&';
            }
            base_link.search += 'uri=' + parent_link.pathname;

            return base_link.href;
        }
    };

    $.fn.nggcaption = function() {
        return this.each(function() {
            var $this = $(this);

            var gallery = nggc_get_gallery($this.data('ngg-captions-id'));
            var style   = nggc_get_setting(gallery, 'captions_animation', 'slideup');

            var $img        = $this.find('img').first();
            var $figure     = $('<figure class="ngg-figure"></figure>');
            var $figcaption = $('<figcaption class="ngg-figcaption"></figcaption>');

            var $header = $('<h6>' + nggc_sanitize($this.data('title')) + '</h6>');
            if (!nggc_get_setting(gallery, 'captions_display_title', true)) {
                $figure.addClass('nggc_no_title');
            }

            var $body_div = $('<div/>').addClass('nggc-body');
            var $body = $('<p data-ngg-original-description="' + nggc_sanitize($this.data('description')) + '">' + '</p>');

            // only assign the P content if titles are disabled; we later assign content if the title doesn't overflow
            if (!nggc_get_setting(gallery, 'captions_display_title', true)) {
                $body.html(nggc_sanitize($this.data('description')));
            }

            $body_div.append($body);
            if (!nggc_get_setting(gallery, 'captions_display_description', true)) {
                $figure.addClass('nggc_no_description');
            }

            // remove any styles: they will be assigned to the new <figure>
            var classList    = $this.attr('class');
            var inlineStyles = $this.attr('style');
            var imageStyles  = $img.attr('style');
            $this.removeAttr('class');
            $this.removeAttr('style');
            $this.removeAttr('title');

            // each class is responsible for a different animation
            $figure.addClass('ngg-figure-' + style);
            $figcaption.addClass('ngg-figcaption-' + style);

            // add share icons if the NextGen Pro Lightbox is active & allows routing
            if ($.inArray(gallery.source, ['albums', 'random', 'random_images']) == -1
            &&  typeof window.nplModalSettings != 'undefined'
            &&  nplModalSettings['enable_routing'] == '1'
            &&  nplModalSettings['enable_sharing'] == '1'
            &&  nggc_get_setting(gallery, 'captions_display_sharing', true)) {
                var slug = gallery.slug;
                if (!slug || slug.indexOf('widget-ngg-images') == -1) {
                    var $iconwrapper = $('<div class="nggc-icon-wrapper"/>');
                    nggc_share_icons.create($iconwrapper, $this);
                    $figcaption.append($iconwrapper);
                }
            } else {
                $figure.addClass('nggc_no_sharing');
            }

            if (nggc_get_setting(gallery, 'captions_display_title', true))
                $figcaption.append($header);

            if (nggc_get_setting(gallery, 'captions_display_description', true))
                $figcaption.append($body_div);

            if ($.browser.msie) {
                if ($.browser.version == 9) {
                    // IE9 does not support flexbox; assign some misc changes that leaves content
                    // vertically aligned at the top
                    $($figure).addClass("nggc-ie9");
                }
                if ($.browser.version == 10) {
                    // IE10 does not support nested flexboxes; it will display description text without wrapping
                    // to fix this "Titlebar" theme uses display:inline-block when the user has IE10
                    $($figure).addClass("nggc-ie10");
                }
            }

            // reassign the anchor & image styles to our figure
            $figure.attr('style', imageStyles);
            $figure.attr('style', inlineStyles);
            $img.removeAttr('style');

            // because the blog gallery wrappers are wider than the image itself
            $figure.css({
                'max-width': $img.width()
            });

            // put our figure object in its place as a wrapper
            $newfigure = $img.wrap($figure);
            $img.after($figcaption);
            $newfigure.addClass(classList);
            $newfigure.parent('p').before($newfigure);
        });
    };

    $(window).load(function() {
        // find and initialize
        $('a').each(function() {
            if ($(this).data('ngg-captions-enabled')) {
                $(this).nggcaption();
            }
        });

        // concatenate our text so it won't overflow
        $('figcaption.ngg-figcaption h6, figcaption.ngg-figcaption .nggc-body').each(function() {
            var $self   = $(this);
            var gallery = nggc_get_gallery($self.parents('a').first().data('ngg-captions-id'));
            var style   = nggc_get_setting(gallery, 'captions_animation', 'slideup');
            $self.dotdotdot({
                callback: function(isTruncated, originalContent) {
                    // only assign the description content when we know the title isn't too long by itself
                    if ($self.prop('tagName') == 'H6') {
                        // 'titlebar' is a special case because of its design
                        if (!isTruncated || style == 'titlebar') {
                            var $nextp = $self.next('div').find('p');
                            $nextp.html(nggc_sanitize($nextp.data('ngg-original-description')));
                        } else if (isTruncated && style != 'titlebar') {
                            $self.next('div').find('p').text('');
                        }
                    }
                    if (isTruncated && !$self.hasClass('nggc-dotdotdot')) {
                        $self.addClass('nggc-dotdotdot');
                    }
                    if (!isTruncated && $self.hasClass('nggc-dotdotdot')) {
                        $self.removeClass('nggc-dotdotdot');
                    }
                }
            });
        });
    });

    // dotdotdot listens for 'update' to reset the shortened text value
    $(window).on('resize fullscreenchange mozfullscreenchange webkitfullscreenchange orientationchange', function() {
        $('figcaption.ngg-figcaption h6, figcaption.ngg-figcaption .nggc-body').each(function() {
            $(this).trigger('update.dot');
        });
    });
})(jQuery);