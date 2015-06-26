jQuery(function($){
    $('input[name="photocrati-nextgen_pro_list_album[override_thumbnail_settings]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_list_album_thumbnail_dimensions'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_list_album_thumbnail_crop'));

    $('input[name="photocrati-nextgen_pro_grid_album[override_thumbnail_settings]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_grid_album_thumbnail_dimensions'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_grid_album_thumbnail_crop'));
});
