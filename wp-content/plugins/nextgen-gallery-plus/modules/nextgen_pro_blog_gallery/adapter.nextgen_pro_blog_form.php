<?php

class A_NextGen_Pro_Blog_Form extends Mixin_Display_Type_Form
{
    function get_display_type_name()
    {
        return NGG_PRO_BLOG_GALLERY;
    }

    function enqueue_static_resources()
    {
        wp_enqueue_script(
            $this->object->get_display_type_name() . '-js',
            $this->get_static_url('photocrati-nextgen_pro_blog_gallery#settings.js')
        );

        $atp = C_Attach_Controller::get_instance();

        if ($atp != null && $atp->has_method('mark_script'))
            $atp->mark_script($this->object->get_display_type_name() . '-js');
    }

    /**
     * Returns a list of fields to render on the settings page
     */
    function _get_field_names()
    {
        return array(
            'image_override_settings',
            'nextgen_pro_blog_gallery_image_display_size',
            'nextgen_pro_blog_gallery_image_max_height',
            'nextgen_pro_blog_gallery_spacing',
            'nextgen_pro_blog_gallery_border_size',
            'nextgen_pro_blog_gallery_border_color',
            'nextgen_pro_blog_gallery_display_captions',
            'nextgen_pro_blog_gallery_caption_location'
        );
    }

    function _render_nextgen_pro_blog_gallery_border_size_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'border_size',
            __('Border size', 'nextgen-gallery-pro'),
            $display_type->settings['border_size'],
            '',
            FALSE,
            '',
            0
        );
    }

    function _render_nextgen_pro_blog_gallery_border_color_field($display_type)
    {
        return $this->_render_color_field(
            $display_type,
            'border_color',
            __('Border color', 'nextgen-gallery-pro'),
            $display_type->settings['border_color']
        );
    }

    function _render_nextgen_pro_blog_gallery_image_display_size_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'image_display_size',
            __('Image display size', 'nextgen-gallery-pro'),
            $display_type->settings['image_display_size'],
            __('Measured in pixels', 'nextgen-gallery-pro'),
            FALSE,
            __('image width', 'nextgen-gallery-pro'),
            0
        );
    }

    function _render_nextgen_pro_blog_gallery_image_max_height_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'image_max_height',
            __('Image maximum height', 'nextgen-gallery-pro'),
            $display_type->settings['image_max_height'],
            __('Measured in pixels. Empty or 0 will not impose a limit.', 'nextgen-gallery-pro'),
            FALSE,
            '',
            0
        );
    }

    function _render_nextgen_pro_blog_gallery_spacing_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'spacing',
            __('Image spacing', 'nextgen-gallery-pro'),
            $display_type->settings['spacing'],
            __('Measured in pixels', 'nextgen-gallery-pro'),
            FALSE,
            '',
            0
        );
    }

    function _render_nextgen_pro_blog_gallery_display_captions_field($display_type)
    {
        return $this->_render_radio_field(
            $display_type,
            'display_captions',
            __('Display captions', 'nextgen-gallery-pro'),
            $display_type->settings['display_captions']
        );
    }

    function _render_nextgen_pro_blog_gallery_caption_location_field($display_type)
    {
        return $this->_render_select_field(
            $display_type,
            'caption_location',
            __('Caption location', 'nextgen-gallery-pro'),
            array(
                'above' => __('Above', 'nextgen-gallery-pro'),
                'below' => __('Below', 'nextgen-gallery-pro')

            ),
            $display_type->settings['caption_location'],
            '',
            !empty($display_type->settings['display_captions']) ? FALSE : TRUE
        );
    }
}
