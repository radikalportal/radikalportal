jQuery(function($){
	if (Galleria) {

		// Get each gallery on the page
		for (var gallery_id in window.galleries) {

			var displayed_gallery = window.galleries[gallery_id];
			var div_id = "#"+gallery_id;

			// Set the stage height
			jQuery(div_id).height(displayed_gallery.display_settings.height);

			// Load the theme
			Galleria.loadTheme(displayed_gallery.display_settings.theme);

			// Run!
			Galleria.run(div_id, {debug: false});
		}
	}
});
