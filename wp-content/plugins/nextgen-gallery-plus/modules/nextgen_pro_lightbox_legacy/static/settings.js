jQuery(function($) {
    $('input[name="photocrati-nextgen_pro_lightbox[enable_routing]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_lightbox_enable_comments'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_lightbox_enable_sharing'));

    $('input[name="photocrati-nextgen_pro_lightbox[enable_comments]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_lightbox_display_comments'));

    $('input[name="photocrati-nextgen_pro_lightbox[icon_background_enabled]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_lightbox_icon_background'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_lightbox_icon_background_rounded'));

    $('select[name="photocrati-nextgen_pro_lightbox[style]"]').on('change', function() {
        var $this = $(this);

        // TODO: make these dynamicly provided
        if ($this.val() == 'white.css') {
            $('input[name="photocrati-nextgen_pro_lightbox[icon_color]"]').wpColorPicker('color', '#7a7a7a');
            $('input[name="photocrati-nextgen_pro_lightbox[carousel_text_color]"]').wpColorPicker('color', '#7a7a7a');
            $('input[name="photocrati-nextgen_pro_lightbox[background_color]"]').wpColorPicker('color', '#ffffff');
        } else {
            $('input[name="photocrati-nextgen_pro_lightbox[icon_color]"]').siblings().first().trigger('click');
            $('input[name="photocrati-nextgen_pro_lightbox[carousel_text_color]"]').siblings().first().trigger('click');
            $('input[name="photocrati-nextgen_pro_lightbox[background_color]"]').siblings().first().trigger('click');
        }

        $('input[name="photocrati-nextgen_pro_lightbox[carousel_text_color]"]').siblings().first().trigger('click');
        $('input[name="photocrati-nextgen_pro_lightbox[carousel_background_color]"]').siblings().first().trigger('click');
        $('input[name="photocrati-nextgen_pro_lightbox[sidebar_background_color]"]').siblings().first().trigger('click');
        $('input[name="photocrati-nextgen_pro_lightbox[icon_background]"]').siblings().first().trigger('click');
        $('input[name="photocrati-nextgen_pro_lightbox[overlay_icon_color]"]').siblings().first().trigger('click');
        $('input[name="photocrati-nextgen_pro_lightbox[sidebar_button_color]"]').siblings().first().trigger('click');
        $('input[name="photocrati-nextgen_pro_lightbox[sidebar_button_background]"]').siblings().first().trigger('click');
    });
});
