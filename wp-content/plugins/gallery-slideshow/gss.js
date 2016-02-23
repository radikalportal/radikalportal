function gss_info_height(){
	// set caption area height
	jQuery('.gss-long-cap').each(function() {
		jQuery(this).show();
		var long_cap_height = (jQuery(this).height())+1;
		jQuery(this).hide();
		jQuery(this).next('.gss-captions').css('min-height', long_cap_height);
	});
};

jQuery(document).on( 'cycle-bootstrap', '.carousel-pager', function(e, opts, API){
	// opts.carousel-visible = 4000;
	var cp_vis_set = jQuery(this).attr('data-car-vis-set');
	if (cp_vis_set != 'true'){
		var cp_count = jQuery('img', this).length;
		var cp_width = jQuery(this).width();
		if (typeof cp_width !== 'undefined'){
			var cp_imgs_vis = Math.round((cp_width / 65));
			if( cp_imgs_vis < cp_count){ opts.carouselVisible = cp_imgs_vis; }
		}
	}
});

jQuery(document).on('cycle-post-initialize', '.cycle-slideshow', function(){
	if (!jQuery(this).hasClass('carousel-pager')){
		var centering = jQuery(this).data('cycle-center-horz');	
		var ssw = jQuery(this).width();	
		jQuery('img', this).each(function() {
			jQuery(this).load(function() {
				imgw = jQuery(this).width();
				if(centering == true && imgw < (ssw*0.9)){
					margin = Math.round((ssw - imgw)/2);
					jQuery(this).css('margin-left', margin);
				}
				else if(imgw != ssw && imgw > (ssw*0.9)){
					jQuery(this).css('margin-left', '');
					jQuery(this).css('width', '100%');
				}
				else if(imgw == ssw){
					jQuery(this).css('margin-left', '');
				}
			});
		});
	}
	jQuery(this).on('cycle-next cycle-prev cycle-pager-activated', function(e, opts) {
		jQuery(this).parent().find('.carousel-pager').cycle('goto', opts.currSlide);
	});
	jQuery(this).on('cycle-after', function( e, opts, out_slide, in_slide) {
    	if (opts['cycleTimeout'] != 0 ){
			var indx = jQuery(this).data('cycle.API').getSlideIndex( in_slide );
			jQuery(this).parent().children('.gss-info').children('.carousel-pager').cycle('goto', indx);
		}
	});
	jQuery('.gss-next, .gss-prev, .gss-pager > a, .carousel-pager .cycle-slide').click(function() {
		jQuery(this).closest('.gss-container').children('.cycle-slideshow').cycle('pause');
	});
});

jQuery(document).on('cycle-post-initialize', '.carousel-pager', function(){
	var x = 0;
	var cp = jQuery(this);
	var cp_sentinel_exists = setInterval(function () {
		if (jQuery('> .cycle-sentinel', cp).length) {
			clearInterval(cp_sentinel_exists);
			// set thumbnail height
			var car_slide_w = jQuery('> .cycle-sentinel', cp).width();
			var car_slide_h = Math.round(car_slide_w * .8) + 'px';
			jQuery('.cycle-slide', cp).css('height',car_slide_h);
			// black bg behind vertical thumbnails
			jQuery('.cycle-slide > img', cp).each(function() {
				jQuery(this).one('load', function() {
					var img_w = jQuery(this).width();
					// console.log(img_w);
					if( img_w < car_slide_w * .9 ){
						var pad = (Math.floor((car_slide_w - img_w) / 2)) + 'px';
						jQuery(this).css({'padding-left':pad,'padding-right':pad, 'background-color':'#000'});
					}
				}).each(function() {
  					if(this.complete) jQuery(this).load();
				});
				/* jQuery(this).load(function() {
					var img_w = jQuery(this).width();
					alert(img_w);
					if( img_w < car_slide_w * .9 ){
						var pad = (Math.floor((car_slide_w - img_w) / 2)) + 'px';
						jQuery(this).css({'padding-left':pad,'padding-right':pad, 'background-color':'#000'});
					}
				}); */
			});
		}
		if (++x === 50) {
			window.clearInterval(cp_sentinel_exists);
			console.log('carousel pager sentinel not found');
		}
	}, 100);
	jQuery('.cycle-slide', this).click(function(){
		var carousel_pager_ss = jQuery(this).closest('.cycle-slideshow');
		var index = carousel_pager_ss.data('cycle.API').getSlideIndex(this);
		jQuery(this).closest('.gss-container').children('.cycle-slideshow').cycle('goto', index);
		carousel_pager_ss.cycle('goto', index);
	});
});

jQuery(document).ready(function(jQuery){
    gss_info_height();
});