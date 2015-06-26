jQuery(function($) {
    // Yes this is a lot of repitition but base NextGen's _render_input_field() methods don't yet have an option
    // for giving additional classes to fields.
    $('input[name="photocrati-nextgen_pro_masonry[captions_enabled]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_masonry_captions_animation'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_masonry_captions_display_title'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_masonry_captions_display_sharing'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_masonry_captions_display_description'));
    $('input[name="photocrati-nextgen_pro_thumbnail_grid[captions_enabled]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_thumbnail_grid_captions_animation'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_thumbnail_grid_captions_display_sharing'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_thumbnail_grid_captions_display_title'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_thumbnail_grid_captions_display_description'));
    $('input[name="photocrati-nextgen_pro_blog_gallery[captions_enabled]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_blog_gallery_captions_animation'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_blog_gallery_captions_display_sharing'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_blog_gallery_captions_display_title'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_blog_gallery_captions_display_description'));
    $('input[name="photocrati-nextgen_pro_film[captions_enabled]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_film_captions_animation'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_film_captions_display_sharing'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_film_captions_display_title'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_film_captions_display_description'));
});
