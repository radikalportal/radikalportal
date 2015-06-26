jQuery(function($) {
    $('#tr_photocrati-nextgen_pro_horizontal_filmstrip_thumbnail_crop').hide();

    $('input[name="photocrati-nextgen_pro_horizontal_filmstrip[override_thumbnail_settings]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_horizontal_filmstrip_thumbnail_dimensions'));

    $('input[name="photocrati-nextgen_pro_horizontal_filmstrip[image_crop]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_horizontal_filmstrip_image_pan'))
        .nextgen_radio_toggle_tr('0', $('#tr_photocrati-nextgen_pro_horizontal_filmstrip_border_color'))
        .nextgen_radio_toggle_tr('0', $('#tr_photocrati-nextgen_pro_horizontal_filmstrip_border_size'));

    $('input[name="photocrati-nextgen_pro_horizontal_filmstrip[show_captions]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_horizontal_filmstrip_caption_class'));
});
