(function($) {
    var is_open  = false;
    var pre_open_callbacks = [];
    var options  = {};
    var defaults = {
        overlay_opacity: 1, // background opacity setting when active
        speed: 'medium'     // see jQuery docs for setting
    };

    // active elements offset is stored in this so we can restore the browser scroll position on closing
    var scroll_top = 0;

    var methods = {
        init: function(parameters) {
            var overlay   = $("<div id='npl_overlay'></div>");
            var overlay2  = $("<div id='npl_overlay2'></div>");
            var wrapper   = $("<div id='npl_wrapper'></div>");
            var spinner   = $("<div id='npl_spinner_container' class='npl-loading-spinner'><i id='npl_spinner' class='fa fa-spin fa-spinner hidden'></i></div>");
            var btn_close = $("<div id='npl_button_close' class='hidden'><i class='fa fa-times-circle'></i></div>");
            var content   = $("<div id='npl_content'></div>");

            overlay.css({background: nplModalRouted.get_setting('background_color')});
            overlay2.css({background: nplModalRouted.get_setting('background_color')});
            spinner.css({color: nplModalRouted.get_setting('icon_color')});
            btn_close.css({color: nplModalRouted.get_setting('overlay_icon_color')});

            $('body').append(overlay);
            $('body').append(overlay2);
            $('body').append(wrapper);
            wrapper.append(spinner);
            wrapper.append(btn_close);
            wrapper.append(content);

            methods.configure(parameters);
            methods.bind_images();
            methods.set_events();
            methods.mobile.init();
        },

        configure: function(parameters) {
            options = $.extend(defaults, parameters);
        },

        run_pre_open_lightbox_callbacks: function(link, params){
            for (var i = 0; i < pre_open_callbacks.length; i++) {
                var callback = pre_open_callbacks[i];
                params = callback(link, params);
            }
            return params;
        },

        add_pre_open_callback: function(callback){
            pre_open_callbacks.push(callback);
        },

        bind_images: function() {
            // in order to handle ajax-pagination events this method is called every time the 'refreshed' signal
            // is emitted. For Galleria to process images we store the selector in nplModalRouted
            var selector = nextgen_lightbox_filter_selector($, $(".nextgen_pro_lightbox"));

            // Modify the selector to exclude any Photocrati Lightboxes
            var new_selector = [];
            for (var index=0; index < selector.length; index++) {
                var el = selector[index];
                if (!$(el).hasClass('photocrati_lightbox_always') && !$(el).hasClass('decoy')) {
                    new_selector.push(el);
                }
            }

            window.nplModalRouted.selector = selector = $(new_selector);

            selector.on('click', function (event) {
                // pass these by
                if ($.inArray($(this).attr('target'), ['_blank', '_parent', '_top']) > -1) {
                    return;
                }

                // NextGEN Basic Thumbnails has an option to link to an imagebrowser display; this disables the effect
                // code (we have no gallery-id) but we may be asked to open it anyway if lightboxes are set to apply
                // to all images. Check for and do nothing in that scenario:
                if ($(this).data('src')
                &&  $(this).data('src').indexOf(nplModalRouted.get_setting('router_slug') + '/image') != -1
                &&  !$(this).data('nplmodal-gallery-id')) {
                    return;
                }

                event.stopPropagation();
                event.preventDefault();

                if (event.handled !== true) {
                    event.handled = true;

                    // cache the current scroll position
                    scroll_top = $(document).scrollTop();

                    if ('ontouchstart' in window) {
                        methods.enter_fullscreen();
                    }

                    // Define parameters for opening the Pro Lightbox
                    var params = {
                        show_sidebar: '',
                        gallery_id: '!',
                        image_id: '!',
                        slug: null,
                        revert_image_id: '!',
                        open_the_lightbox: true
                    };

                    // Determine if we should show the comment sidebar
                    if ($(this).data('nplmodal-show-comments')) params.show_sidebar = '/comments';

                    // Determine the gallery id
                    if ($(this).data('nplmodal-gallery-id')) params.gallery_id = $(this).data('nplmodal-gallery-id');

                    // Determine the image id
                    if ($(this).data('nplmodal-image-id')) params.image_id = $(this).data('nplmodal-image-id');
                    else if ($(this).data('image-id'))     params.image_id = $(this).data('image-id');
                    else if (params.gallery_id == '!')     params.image_id = $(this).attr('href');

                    // Determine the slug
                    if (params.gallery_id != '!') {
                        params.slug = window.nplModalRouted.get_slug(params.gallery_id);
                        if (params.slug != 'undefined' && params.slug != null) {
                            if (params.slug.toString().indexOf('widget-ngg-images-') !== -1) {
                                params.revert_image_id = params.image_id;
                                params.image_id = '!';
                            }
                        }
                    }

                    // Run any registered callbacks for modifying lightbox params
                    params = methods.run_pre_open_lightbox_callbacks(this, params);

                    // Are we to still open the lightbox?
                    if (params.open_the_lightbox) {
                        // open the pro-lightbox manually
                        if (params.gallery_id == '!' || !nplModalRouted.get_setting('enable_routing')) {
                            // set this so we can tell Galleria which image to display first
                            window.nplModalRouted.image_id = params.image_id;
                            methods.open_modal(params.gallery_id, params.image_id);
                        } else {
                            // open the pro-lightbox through our backbone.js router
                            window.nplModalRouted.front_page_pushstate(params.gallery_id, params.image_id);
                            window.nplModalRouted.navigate(
                                window.nplModalRouted.router_slug + '/' + params.slug + '/' + params.image_id + params.show_sidebar,
                                {trigger: true, replace: false}
                            );
                            // some displays (random widgets) may need to disable routing
                            // but still pass an image-id to display on startup
                            if (params.revert_image_id != '!') {
                                window.nplModalRouted.image_id = params.revert_image_id;
                            }
                        }
                    }
                }
            });
        },

        // establishes bindings of events to actions
        set_events: function() {
            $(window).on('refreshed', methods.bind_images);

            $(document).ready(function() {
                // Provide a hook for third-parties to add their own methods
                $(window).trigger('override_nplModal_methods', methods);
            });

            // some display types (pro-slideshow for example) require their trigger buttons image-id
            // attribute to be updated as they display
            $('body').on('nplmodal.update_image_id', function(event, entities, image_id) {
                entities.each(function() {
                    $(this).data('nplmodal-image-id', image_id);
                });
            });

            // keep the display "responsive" by adjusting its dimensions when the browser resizes
            $(window).on('resize orientationchange fullscreenchange mozfullscreenchange webkitfullscreenchange', function (event) {
                if (methods.is_open()) {
                    window.scrollTo(0,0);
                }
            });

            // we really want to prevent scrolling
            $(window).bind('mousewheel DOMMouseScroll touchmove', methods.prevent_scrolling);
            $(window).bind('keydown', methods.handle_keyboard_input);
            $(document).bind('touchmove', methods.prevent_scrolling);

            // handle exit clicks/touch events
            $('#npl_overlay, #npl_overlay2, #npl_button_close').on('touchstart click', function(event) {
                event.stopPropagation();
                event.preventDefault();
                if (event.handled !== true) {
                    event.handled = true;
                    methods.close_modal();
                }
            });
        },

        open_modal: function(gallery_id, image_id) {
            is_open = true;

            // disables browser scrollbar display
            $('html, body').toggleClass('nextgen_pro_lightbox_open');

            // immediately show the overlay; if done after scrolling the page will appear to flash
            $('#npl_overlay, #npl_overlay2')
                .css({display: 'block', opacity: 0})
                .velocity(
                {opacity: 1},
                {duration: options.overlay_opacity,
                    complete: function () {
                        window.scrollTo(0, 0);
                        methods.mobile.open();
                    }
                });

            $('#npl_wrapper')
                .css({display: 'block', opacity: 0})
                .velocity({opacity: 1}, {duration: options.speed});

            $('#npl_spinner, #npl_button_close').removeClass('hidden');

            nplModalRouted.gallery_id = gallery_id;

            var images = nplModalRouted.fetch_images.fetch_images(gallery_id);
            var show_ndx = 0;
            $.each(images, function(index, element) {
                if (image_id == element.image_id) {
                    show_ndx = index;
                }
            });

            Galleria.run('#npl_content', {
                theme: 'nextgen_pro_lightbox',
                dataSource: images,
                show: show_ndx,
                variation:           'nggpl-variant-' + nplModalRouted.get_setting('style', ''),
                transition:          nplModalRouted.get_setting('transition_effect', 'slide'),
                touchTransition:     nplModalRouted.get_setting('touch_transition_effect', 'slide'),
                imagePan:            nplModalRouted.get_setting('image_pan', false),
                pauseOnInteraction:  nplModalRouted.get_setting('interaction_pause', true),
                imageCrop:           nplModalRouted.get_setting('image_crop', true),
                transitionSpeed:    (nplModalRouted.get_setting('transition_speed', 0.4) * 1000)
            });

            $('#npl_spinner').addClass('hidden');
            $('#npl_content').velocity({opacity: 1}, {duration: options.speed});
        },

        // When rotaning or opening the keyboard some mobile browsers increase the user zoom level beyond the default.
        // To handle this we update the viewport setting to disable zooming when open_modal is run and restore it to
        // the original value when calling close_modal()
        mobile: {
            meta: null,
            original: null, // original viewport setting; it's restored at closing
            adjust: true,
            ontouch: ('ontouchstart' in window ? true : false),
            init: function() {
                // suppress a warning in desktop chrome (provided no touch input devices are attached) that the following
                // content meta-attribute we're about to set is invalid. it technically is, but it's the only way
                // to make every mobile browser happy without ridiculous user agent matching that I've come across so far
                if (!this.ontouch) {
                    this.adjust = false;
                }
                var version = this.ios_version();
                if (version && version[0] >= 8) {
                    this.adjust = false;
                }
                var doc = window.document;
                if (!doc.querySelector) { return; } // this isn't available on pre 3.2 safari
                this.meta     = doc.querySelector("meta[name=viewport]");
                this.original = this.meta && this.meta.getAttribute("content");
            },
            open: function() {
                if (this.adjust && this.meta) {
                    this.meta.setAttribute("content", this.original + ', width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1, maximum-scale=1, user-scalable=no;user-scalable=0');
                }
            },
            close: function() {
                if (this.adjust && this.meta) {
                    this.meta.setAttribute("content", this.original);
                }
            },
            ios_version: function() {
                if (/iP(hone|od|ad)/.test(navigator.platform)) {
                    var v = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
                    return [parseInt(v[1], 10), parseInt(v[2], 10), parseInt(v[3] || 0, 10)];
                }
            }
        },

        // hide our content and close up
        close_modal: function() {
            // allow for cleanup handlers to run
            $('#npl_content').trigger('npl.closing');

            methods.exit_fullscreen();

            // for use with Galleria it is important that npl_content never have display:none set
            $("#npl_wrapper, #npl_overlay, #npl_overlay2").velocity({opacity: 0}, {duration: options.speed});
            $('#npl_spinner, #npl_button_close').addClass('hidden');
            $("#npl_wrapper, #npl_overlay, #npl_overlay2").css({display: 'none'});

            // enables displaying browser scrollbars
            $('html, body').toggleClass('nextgen_pro_lightbox_open');

            methods.mobile.close();

            // kills Galleria so it won't suck up memory in the background
            $('#npl_content').data('galleria').destroy();

            // reset our modified url to our original state
            if (nplModalRouted.get_setting('enable_routing')) {
                nplModalRouted.navigate('', {trigger: false, replace: true});
                if (nplModalRouted.get_setting('is_front_page') && history.pushState) {
                    history.pushState({}, document.title, nplModalRouted.initial_url);
                }
            }

            // Fix scrolling position
            $(document).scrollTop(scroll_top);
            setTimeout(function(){
                $(document).scrollTop(scroll_top);
            }, 100);

            is_open = false;
        },

        // make a request to enter fullscreen mode.
        //
        // NOTE: this can only be done in response to a user action; just calling enter_fullscreen() programatically
        // will not work. Firefox & IE will produce errors, but Chrome (presently, 2013-04) silently fails
        enter_fullscreen: function() {
            // do not use a jquery selector, it will not work
            element = document.getElementById('npl_wrapper');

            if (element.requestFullScreen) {
                element.requestFullScreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullScreen) {
                element.webkitRequestFullScreen();
            }
        },

        exit_fullscreen: function() {
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }
        },

        toggle_fullscreen: function() {
            if (document.fullScreen || document.mozfullScreen || document.webkitIsFullScreen) {
                methods.exit_fullscreen();
            } else {
                methods.enter_fullscreen();
            }
        },

        // prevent the mouse wheel from sending events to the parent page
        prevent_scrolling: function(event) {
            if (methods.is_open()) {
                window.scrollTo(0,0);
            }
        },

        // try to prevent the user from scrolling in the parent page
        handle_keyboard_input: function(event) {
            if (methods.is_open()) {
                // escape key closes the modal
                if (event.which == 27) {
                    methods.close_modal();
                }
            }
        },

        is_open: function() {
            return is_open;
        }
    };

    $.fn.nplModal = function(method) {
        // Method calling logic
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' +  method + ' does not exist on jQuery.nplModal');
        }
    };
})(jQuery);

jQuery(document).ready(function($) {

    var nplModalRoutes = Backbone.Router.extend({
        initialize: function() {
            var self = this;

            // the url is restored to this location when the lightbox closes
            this.initial_url = window.location.toString().split('#')[0];

            // cach for galleria to inspect
            this.gallery_id  = null;
            this.image_id    = null;
            this.sidebar     = null;
            this.selector    = null;

            // for client windows to reference
            this.ajax_url = photocrati_ajax.url;

            // users can configure the slug on which the backbone-router takes effect
            this.router_slug = this.get_setting('router_slug');
            this.route(this.router_slug + "/:gallery_id/:image_id", "gallery_and_image");
            this.route(this.router_slug + "/:gallery_id/:image_id/:sidebar", "gallery_and_image");
            this.route(this.router_slug, 'close_modal');
            this.route('', 'close_modal');

            // Galleria theme will listen for this event to determine fullscreen state changes
            $(window).on('fullscreenchange mozfullscreenchange webkitfullscreenchange', function (event) {
                self.trigger('nplModalRouted_fullscreen_change');
            });
        },

        // to prevent slug conflicts inject the wordpress url prefix when we're dealing with the wordpress front-page
        front_page_pushstate: function(gallery_id, image_id) {
            if (!this.get_setting('is_front_page')  || gallery_id == undefined) { return false; }
            if ('undefined' == typeof window.galleries) { return false; }

            var url  = '';
            var slug = gallery_id;

            $.each(galleries, function(index, gallery) {
                if (gallery.ID == gallery_id) {
                    url = gallery.wordpress_page_root;
                    if (gallery.slug) {
                        slug = gallery.slug;
                    }
                }
            });
            url += '#' + Backbone.history.getFragment(this.router_slug + '/' + slug + '/' + image_id);

            // redirect those browsers that don't support history.pushState
            if (history.pushState) {
                history.pushState({}, document.title, url);
                return true;
            } else {
                window.location = url;
                return false;
            }
        },

        // returns the slug string by inspecting galleries by their gallery ID
        get_slug: function (gallery_id) {
            var slug = gallery_id;
            if ('undefined' == typeof window.galleries) { return slug; }

            $.each(galleries, function(index, gallery) {
                if (gallery.slug && gallery.ID == gallery_id) {
                    slug = gallery.slug;
                }
            });

            return slug;
        },

        get_gallery_from_id: function (gallery_id) {
            if ('undefined' == typeof window.galleries) { return null; }
            var retval = null;
            $.each(galleries, function(index, gallery) {
                if (gallery.ID == gallery_id) {
                    retval = gallery;
                }
            });
            return retval;
        },

        get_setting: function (name, def) {
            var tmp = '';
            if (typeof nplModalSettings != 'undefined' && window.nplModalSettings[name] != '') {
                tmp = window.nplModalSettings[name];
            } else {
                tmp = def;
            }
            if (tmp == 1)   tmp = true;
            if (tmp == 0)   tmp = false;
            if (tmp == '1') tmp = true;
            if (tmp == '0') tmp = false;
            return tmp;
        },

        get_id_from_slug: function (slug) {
            var id = slug;
            if ('undefined' == typeof window.galleries) { return id; }

            $.each(galleries, function(index, gallery) {
                if (gallery.slug == slug) {
                    id = gallery.ID;
                }
            });
            return id;
        },

        toggle_fullscreen: function() {
            if ($(document).nplModal('is_open')) {
                $(document).nplModal('toggle_fullscreen');
            }
        },

        close_modal: function() {
            if ($(document).nplModal('is_open')) {
                // backbone's .on() registers callbacks rather than allowing proper event listening
                // so we must unbind what has been registered, lest the same callback be registered
                // multiple times for the same event
                this.unbind();

                $(document).nplModal('close_modal');
            }
        },

        gallery_and_image: function(slug, image_id, sidebar) {
            // determine the ID from our slug. if nothing comes back, assume we're already looking at the ID
            var gallery_id = this.get_id_from_slug(slug);

            if (!gallery_id) {
                gallery_id = slug;
            }

            if (sidebar == '1') {
                sidebar = 'comments';
            }

            // the galleria theme handles url updates between image ids, so if the modal window is already open
            // and is already looking at the same gallery we don't need to do anything here
            if ($(document).nplModal('is_open') && slug == this.gallery_id) {
                this.image_id = image_id;
                this.sidebar  = sidebar;
                return;
            }

            if ($(document).nplModal('is_open')) {
                $(document).nplModal('close_modal');
            }

            // cache these; Galleria will read them to determine which image to load first
            this.slug     = slug;
            this.image_id = image_id;
            this.sidebar  = sidebar;

            $(document).nplModal('open_modal', gallery_id, image_id);
        },

        fetch_images: {
            gallery_image_cache: [],

            fetch_images: function(gallery_id) {
                // we already have a cache; no need to add more
                if (typeof this.gallery_image_cache[gallery_id] != 'undefined'
                &&  this.gallery_image_cache[gallery_id].length > 0) {
                    return this.gallery_image_cache[gallery_id];
                }

                if (typeof this.gallery_image_cache[gallery_id] == 'undefined') {
                    this.gallery_image_cache[gallery_id] = [];
                }

                // scrape the localized list if it exists
                if (typeof(galleries) != 'undefined'
                &&  typeof(galleries['gallery_' + gallery_id]) != 'undefined'
                &&  typeof(galleries['gallery_' + gallery_id].images_list) != 'undefined') {
                    this.gallery_image_cache[gallery_id] = galleries['gallery_' + gallery_id].images_list;
                    if (galleries['gallery_' + gallery_id].images_list_limit_reached) {
                        this.fetch_images_from_ajax(gallery_id, true, true);
                    }
                }
                else {
                    // no luck: we must scrape from the page
                    this.fetch_images_from_page(gallery_id);

                    // Galleria can't start without any images so load them by AJAX
                    if (this.gallery_image_cache[gallery_id].length <= 0 && gallery_id != '!') {
                        this.fetch_images_from_ajax(gallery_id, false, false);
                    }

                    // Make an async request in case there's more images to load
                    if (gallery_id != '!') {
                        this.fetch_images_from_ajax(gallery_id, true, true);
                    }
                }
                return this.gallery_image_cache[gallery_id];
            },

            fetch_images_from_page: function (gallery_id) {
                var self = this;
                var selector = nplModalRouted.selector;

                jQuery(selector).each(function(index, element) {
                    var anchor = $(this);

                    if (anchor.hasClass('ngg-trigger')) {
                        return true; // exclude NextGEN trigger icons
                    }

                    if (gallery_id != '!' && gallery_id != anchor.data('nplmodal-gallery-id') && gallery_id != nplModalRouted.gallery_id) {
                        return true; // exclude images from other galleries
                    }

                    if (nplModalRouted.gallery_id == '!' && anchor.data('nplmodal-gallery-id')) {
                        return true; // when viewing non-nextgen images; exclude nextgen-images
                    }

                    var image = $(this).find('img').first();

                    // when in doubt we id images by their href
                    var gallery_image = {};
                    gallery_image.image    = (anchor.data('fullsize') == undefined) ? anchor.attr('href') : anchor.data('fullsize');
                    gallery_image.image_id = (anchor.data('image-id') == undefined) ? gallery_image.image : anchor.data('image-id');

                    // optional attributes
                    if (anchor.data('thumb') != undefined) gallery_image.thumb = anchor.data('thumb');
                    else if (anchor.data('thumbnail') != 'undefined') gallery_image.thumb = anchor.data('thumbnail');

                    if (anchor.data('title') != undefined) {
                        gallery_image.title = anchor.data('title');
                    } else if (typeof image.attr('title') != 'undefined') {
                        gallery_image.title = image.attr('title');
                    } else if (typeof anchor.siblings('.wp-caption-text').html() != 'undefined') {
                        gallery_image.title = anchor.siblings('.wp-caption-text').html();
                    }

                    if (anchor.data('description') != undefined) {
                        gallery_image.description = anchor.data('description');
                    } else {
                        gallery_image.description = image.attr('alt');
                    }

                    self.gallery_image_cache[gallery_id].push(gallery_image);
                });
            },

            fetch_images_from_ajax: function(gallery_id, async, check_existing) {
                var self = this;
                var gallery = nplModalRouted.get_gallery_from_id(gallery_id);
                gallery = $.extend({}, gallery);
                delete gallery.images_list;
                $.ajax({
                    async: async,
                    url: nplModalRouted.ajax_url,
                    method: 'POST',
                    data: {
                        id: gallery_id,
                        gallery: gallery,
                        action: 'pro_lightbox_load_images',
                        lang: nplModalRouted.get_setting('lang', null)
                    },
                    dataType: 'json',
                    success: function(data, status, jqXHR) {
                        if (check_existing && async) {
                            $.each(data, function (ndx, newimage) {
                                var found = false;
                                $.each(self.gallery_image_cache[gallery_id], function (ndx2, curimage) {
                                    if (newimage.image_id == curimage.image_id) {
                                        found = true;
                                    }
                                });
                                if (!found) {
                                    var galleria = $('#npl_content').data('galleria');
                                    $('#npl_content').trigger('npl.newimage', {image: newimage});
                                    self.gallery_image_cache[gallery_id].push(newimage);
                                    galleria.push(newimage);
                                    // the user has requested an image not on this page; once the ajax request has
                                    // loaded the image the user wants we ask that Galleria show it right away.
                                    // We use setTimeout() here because Galleria requires a minor pause after .push()
                                    if (newimage.image_id == nplModalRouted.image_id) {
                                        var ndxtoshow = self.gallery_image_cache[gallery_id].length - 1;
                                        setTimeout(function() {
                                            galleria.show(ndxtoshow);
                                        }, 20);
                                    }
                                }
                            });
                        } else if (!check_existing && !async) {
                            $.each(data, function(ndx, newimage) {
                                $('#npl_content').trigger('npl.newimage', {image: newimage});
                                self.gallery_image_cache[gallery_id].push(newimage);
                            });
                        }
                    }
                });

                return self.gallery_image_cache[gallery_id];
            }
        }
    });

    window.nplModalRouted = new nplModalRoutes();
    $(document).nplModal();
    Backbone.history.start({
        pushState: false
    });
});
