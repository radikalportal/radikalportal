(function() {

var jQuery;
var scripts = document.getElementsByTagName("script");
var src = scripts[scripts.length-1].src;
var gss_url = src.substring(0, src.indexOf('gallery-slideshow'));
var embed = scripts[scripts.length-1].getAttribute('data-embed');
var data_target = scripts[scripts.length-1].getAttribute('data-target');
var target = (data_target != null) ? '#' + data_target : '#gss-embed';

/* load jQuery if not present */
if (window.jQuery === undefined || window.jQuery.fn.jquery < '1.8') {
    var script_tag = document.createElement('script');
    script_tag.setAttribute("type","text/javascript");
    script_tag.setAttribute("src","//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js");
    if (script_tag.readyState) {
      script_tag.onreadystatechange = function () { // For old versions of IE
          if (this.readyState == 'complete' || this.readyState == 'loaded') {
              scriptLoadHandler();
          }
      };
    } else {
      script_tag.onload = scriptLoadHandler;
    }
    // Try to find the head, otherwise default to the documentElement
    (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
} else {
    // The jQuery version on the window is the one we want to use
    jQuery = window.jQuery;
    main();
}

/* load scripts */
function scriptLoadHandler() {
    // jQuery = window.jQuery.noConflict(true);
	jQuery = $.noConflict();
	jQuery.when(
    	jQuery.getScript( gss_url + "gallery-slideshow/jquery.cycle2.min.js" ),
    	jQuery.getScript( gss_url + "gallery-slideshow/gss.js" ),
    	jQuery.Deferred(function( deferred ){
        	jQuery( deferred.resolve );
    	})
	).done(function(){
		jQuery.getScript( gss_url + "gallery-slideshow/jquery.cycle2.center.min.js" );
		jQuery.getScript( gss_url + "gallery-slideshow/jquery.cycle2.carousel.min.js" );
    	main();
	});	
}

/* main function */
function main() { 
    jQuery(document).ready(function(jQuery){ 
        var css_link = jQuery("<link>",{ 
            rel: "stylesheet", 
            type: "text/css", 
            href: gss_url + "gallery-slideshow/gss.css" 
        });
        css_link.appendTo('head');          
        /* html */
        var jsonp_url = gss_url + "gallery-slideshow/embed.php?callback=?&embed_meta=" + embed; // + "&options=" + opts;
		jQuery.getJSON(jsonp_url)
			.done(function(data) {
				jQuery(target).html(data);
				jQuery('.cycle-slideshow').cycle();
				jQuery(document).ready(function(jQuery){
					gss_info_height();
				});
			})
			.fail(function(jqxhr, textStatus, error) {
				var err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
		})
	});
}

})();