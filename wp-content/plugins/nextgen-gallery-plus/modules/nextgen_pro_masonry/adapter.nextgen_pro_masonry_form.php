<?php

class A_NextGen_Pro_Masonry_Form extends Mixin_Display_Type_Form
{
    function get_display_type_name()
    {
        return NGG_PRO_MASONRY;
    }

    /**
     * Returns a list of fields to render on the settings page
     */
    function _get_field_names()
    {
        return array(
            'nextgen_pro_masonry_size',
            'nextgen_pro_masonry_padding'
        );
    }

    function _render_nextgen_pro_masonry_size_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'size',
            __('Maximum image width', 'nextgen-gallery-pro'),
            $display_type->settings['size'],
            __('Measured in pixels', 'nextgen-gallery-pro')
        );
    }

    function _render_nextgen_pro_masonry_padding_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'padding',
            __('Image padding', 'nextgen-gallery-pro'),
            $display_type->settings['padding'],
            __('Measured in pixels', 'nextgen-gallery-pro')
        );
    }
}