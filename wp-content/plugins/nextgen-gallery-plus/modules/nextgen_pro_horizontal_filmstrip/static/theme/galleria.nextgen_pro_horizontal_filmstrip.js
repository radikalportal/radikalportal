(function($) {

// Set defaults
var defaults = $.extend({}, Galleria.configure.options, {
	carousel: true,
	thumbnails: true,
	autoplay: true,
	showInfo: false,
	fullscreenDoubleTap: false,
	trueFullscreen: false
});

// Show captions if a caption_class has been provided
if (defaults.show_captions && defaults.caption_class.length > 0) {
	defaults.showInfo = true;
}

    Galleria.get_ngg_setting = function(name, def) {
        var tmp = '';
        if (typeof window.ngg_settings != 'undefined') {
            tmp = window.ngg_settings[name];
        } else {
            tmp = def;
        }
        if (tmp == 1) tmp = true;
        if (tmp == 0) tmp = false;
        return tmp;
    };

Galleria.addTheme({
    name: 'nextgen_pro_horizontal_filmstrip',
    author: 'Photocrati Media',
    css: 'galleria.nextgen_pro_horizontal_filmstrip.css',
    defaults: defaults,
    init: function(options) {
        Galleria.requires(1.28, 'This version of Classic theme requires Galleria 1.2.8 or later');

    this._fullscreen._enter = function (t) {};

		// Provides a function which sets the width of an image
		this.set_image_width = function($img, width) {
			$img.attr('width', width);
			$img.width(width);
		};

		// Provides a function which sets the height of an image
		this.set_image_height = function($img, height){
			$img.attr('height', height);
			$img.height(height);
		};

		// some stuff for non-touch browsers
        if (! Galleria.TOUCH ) {
            this.addIdleState( this.get('image-nav-left'), { left:-50 });
            this.addIdleState( this.get('image-nav-right'), { right:-50 });
            this.addIdleState( this.get('counter'), { opacity:0 });
        }

		{
			var jstage = $(this._target).find('.galleria-stage');
			var jthumbs = $(this._target).find('.galleria-thumbnails-container');
			var jpar = $(this._target);

            // enlarge the thumbnail bar if we must
            var thumbheight = options.thumbnailHeight;
            for (var i = 0; i <= (this.getDataLength() - 1); i++) {
                var img = this.getData(i);
                if (img.thumb_dimensions.height > thumbheight) {
                    thumbheight = img.thumb_dimensions.height;
                }
            }
			jthumbs.height(thumbheight);
			
			var par_off = jpar.offset();
			var thumbs_off = jthumbs.offset();
			var par_bott = par_off.top + jpar.outerHeight();
			jstage.css({
				bottom : (par_bott - thumbs_off.top) + 7
			});
		}

		// Add the caption class to the Galleria container
		if (options.showInfo && options.captionClass.length > 0) 
		{
			$(this._target).find('.galleria-info').show();
			$(this._target).addClass(options.captionClass);
			
			var jcap = $(this._target).find('.galleria-info');
			var jcap_par = jcap.parent();
			var jstage = $(this._target).find('.galleria-stage');
			var jthumb = $(this._target).find('.galleria-thumbnails-container');
			var jpar = jstage.parent();
			
			var cap_off = jcap.offset();
			var cap_par_off = jcap_par.offset();
			var stage_off = jstage.offset();
			var par_off = jpar.offset();
			
			var cap_par_bott = cap_par_off.top + jcap_par.outerHeight();
			var stage_top = stage_off.top - par_off.top;
			var stage_bott = stage_off.top + jstage.outerHeight();
			var stage_right = stage_off.left + jstage.outerWidth();
			var par_right = par_off.left + jpar.outerWidth();
			
			switch (options.captionClass)
			{
				case 'caption_above_stage':
				{
                    var adjustment = jcap.outerHeight() + 10;
					jstage.css({ top: adjustment });
					jcap.css({
						top : 0,
						bottom : 'auto',
						left : stage_off.left,
						right : par_right - stage_right
					});
                    this._stageHeight = this._stageHeight - adjustment;
                    this.rescale();
					break;
				}
				case 'caption_below_stage':
				{
					var thumb_height = 0;
					
					if (jthumb && jthumb.size() > 0)
					{
						jthumb.css({ bottom: jcap.outerHeight() });
						thumb_height = jthumb.outerHeight();
					}
				
                    var adjustment = jcap.outerHeight() + thumb_height + 7;	
					jstage.css({ bottom: adjustment });
					jcap.css({
						top : 'auto',
						bottom : 0,
						left : stage_off.left,
						right : par_right - stage_right
					});
                    this._stageHeight = this._stageHeight - adjustment;
                    this.rescale();
					break;
				}
				case 'caption_overlay_top':
				{
					jcap.css({
						top : stage_top,
						left : stage_off.left,
						right : par_right - stage_right
					});
					
					break;
				}
				case 'caption_overlay_bottom':
				{
					jcap.css({
						bottom : cap_par_bott - stage_bott,
						left : stage_off.left,
						right : par_right - stage_right
					});
					
					break;
				}
			}
			
			if (options.captionClass == 'caption_above_stage' || options.captionClass == 'caption_below_stage')
			{
				var imgs = jstage.find('.galleria-image');
				imgs.each(function () {
					$(this).css({ height : jstage.height() + 'px' });
					$(this).height(jstage.height());
				});
			}
			
			jcap.hover(
				function () {
					var self = jQuery(this);
					var text = self.find('.galleria-info-text');
					var diff = self.outerHeight() - text.outerHeight();

					if (diff < 0)
					{
						self.stop().animate({ scrollTop: -diff }, ((-diff) / 17) * 450);
					}
				},
				function () {
					var self = jQuery(this);
					var text = self.find('.galleria-info-text');
					var diff = self.outerHeight() - text.outerHeight();

					if (diff < 0)
					{
						self.stop().animate({ scrollTop: 0 }, 'fast');
					}
				}
			);
		}

		// add playback controls if we're to do so
		if (options.showPlaybackControls) {

			// Add playback controls
			var playback_button = $('<div/>').addClass('galleria-playback-button');
			if (this._playing) playback_button.removeClass('play').addClass('pause');
			else playback_button.removeClass('pause').addClass('play');
			$(this._dom.stage).append(playback_button);

			// Add clickable button
			var button = $('<a/>').hover(
				function(){ $(this).parent().css('opacity', 0.9); },
				function(){ $(this).parent().css('opacity', 0.7); }
			).click(this.proxy(function(e){
				var controls = $(e.target).parent();
				if (this._playing) {
					this.pause();
					controls.removeClass('pause').addClass('play');
				}
				else {
					this.play().next();
					controls.removeClass('play').addClass('pause');
				}
			}));
			playback_button.append(button);

			// Show the controls on hover
			playback_button.hover(
				function(){ $(this).css('opacity', 0.7); },
				function(){ $(this).animate({opacity: 0.0}); }
			);
		}
		
		// thumbnail navigation shown on hover
		var thumbCont = this.get('thumbnails-container');
		var navList = [ this.get('thumb-nav-left'), this.get('thumb-nav-right') ];
		jQuery(navList).css({ display : 'none' });
		jQuery(navList).hover(
			function () {
				if (!jQuery(this).hasClass('disabled'))
				{
					jQuery(this).stop().animate({
						opacity: 1
					}, 'fast');
				}
			},
			function () {
				if (!jQuery(this).hasClass('disabled'))
				{
					jQuery(this).stop().animate({
						opacity: 0.8
					}, 'fast');
				}
			}
		);

		jQuery(thumbCont).hover(
			this.proxy(function() {
				var navList = [ this.get('thumb-nav-left'), this.get('thumb-nav-right') ];
				jQuery(navList).css({ display : 'block' });
				jQuery(navList).stop().animate({
					opacity: 0.8
				});
			}),
			this.proxy(function() {
				var navList = [ this.get('thumb-nav-left'), this.get('thumb-nav-right') ];
				jQuery(navList).stop().animate({
					opacity: 0
				}, function () {
					jQuery(this).css({ display: 'none' });
				});
			})
		);

		// set slideshow speed
		if (options.slideshowSpeed) {
			this.setPlaytime(options.slideshowSpeed);
		}

		this.bind('loadfinish', this.proxy(function(e){
			var $img = $(e.imageTarget);
			var jstage = $(this._target).find('.galleria-stage');

			// If a border has been specified, adjust the dimensions of the image
			// to accomodate
			// The stage height is accomodated for the height of the
			// counter (10px) and the border width
			if (this._options.border_size > 0 && this._options.imageCrop !== true) {
				var borders_size = (this._options.border_size * 2);
				var img_width = $img.width();
				var img_height = $img.height();
			
				this.set_image_width($img, img_width - borders_size);
				this.set_image_height($img, img_height - borders_size);
			}
			
			// This doesn't seem required, galleria handles positioning already?
//			$img.parent().height(jstage.height());
//			$img.css({
//				top : ($img.parent().height() - $img.height()) / 2
//			});

			// If the image is clicked, open fullscreen mode
			$img.on('click', this.proxy(function(){
				this.enterFullscreen();
			}));

			// The current thumbnail should have full opacity
			$(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity', 0.6);
		}));
		
			$(this._target).find('.galleria-thumbnails').find('.galleria-image').each(function () {
				var jthis = $(this);
				jthis.width(options.thumbnailWidth);
				jthis.height(options.thumbnailHeight);
				jthis.find('img').css({
					'maximum-width' : options.thumbnailWidth
				})
			});
			
		// bind some stuff
        this.bind('thumbnail', this.proxy(function(e) {
					var $img = $(e.thumbTarget);
					var $par = $img.parent();
					var w = $img.width();
					var h = $img.height();
					var ow = $par.outerWidth();
					var oh = $par.outerHeight();

					// If a border has been specified, adjust the dimensions of the image
					// to accomodate
					if (ow > w) {
						//$img.width(w - (ow - w));
						//$par.width($img.width());
					}
					if (oh > h) {
						//$img.height(h - (oh - h));
						//$par.height($img.height());
					}

          if (! Galleria.TOUCH ) {
              // fade thumbnails
              $(e.thumbTarget).css('opacity', 0.6).parent().hover(function() {
                  $(this).not('.active').children().stop().fadeTo(100, 1);
              }, function() {
                  $(this).not('.active').children().stop().fadeTo(400, 0.6);
              });

              if ( e.index === this.getIndex() ) {
                  $(e.thumbTarget).css('opacity',1);
              }
          } else {
              $(e.thumbTarget).css('opacity', this.getIndex() ? 1 : 0.6);
          }
        }));

        this.bind('loadfinish', this.proxy(function (event) {
            var self = this;
            var gallery_id = window.Galleria_Instance.displayed_gallery.ID;
            top.jQuery('#displayed_gallery_' + gallery_id).siblings('div.ngg-trigger-buttons').each(function() {
                top.jQuery('body').trigger('nplmodal.update_image_id', [jQuery(this).find('i'), $(self.getData(self.getIndex()).original).data('image-id')]);
            });
        }));

        if (Galleria.get_ngg_setting('protect_images', false)) {
            this.addElement('image-protection');
            document.oncontextmenu = function(event) {
                event = event || window.event;
                event.preventDefault();
            };
            this.prependChild('images', 'image-protection');
            $('.galleria-image').bind('dragstart', function(event) {
                event.preventDefault();
            });
        }
    }
});

}(jQuery));
