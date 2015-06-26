jQuery(function($) {
    $('input[name="photocrati-nextgen_pro_slideshow[image_crop]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_slideshow_image_pan'))
        .nextgen_radio_toggle_tr('0', $('#tr_photocrati-nextgen_pro_slideshow_border_color'))
        .nextgen_radio_toggle_tr('0', $('#tr_photocrati-nextgen_pro_slideshow_border_size'));

    $('input[name="photocrati-nextgen_pro_slideshow[show_captions]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_slideshow_caption_class'));
});
