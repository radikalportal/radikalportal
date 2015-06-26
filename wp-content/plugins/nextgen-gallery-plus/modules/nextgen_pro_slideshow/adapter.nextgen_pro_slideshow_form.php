<?php

class A_NextGen_Pro_Slideshow_Form extends Mixin_Display_Type_Form
{
    function get_display_type_name()
	{
		return NGG_PRO_SLIDESHOW;
	}

    function enqueue_static_resources()
    {
        wp_enqueue_script(
            $this->get_display_type_name() . '-js',
            $this->get_static_url('photocrati-nextgen_pro_slideshow#settings.js')
        );
	
				$atp = C_Attach_Controller::get_instance();
	
				if ($atp != null && $atp->has_method('mark_script')) {
					$atp->mark_script($this->object->get_display_type_name() . '-js');
				}
    }

    /**
     * Returns a list of fields to render on the settings page
     */
    function _get_field_names()
    {
        return array(
            'nextgen_pro_slideshow_image_crop',
            'nextgen_pro_slideshow_image_pan',
            'nextgen_pro_slideshow_show_playback_controls',
            'nextgen_pro_slideshow_show_captions',
            'nextgen_pro_slideshow_caption_class',
            'nextgen_pro_slideshow_aspect_ratio',
            'nextgen_pro_slideshow_width_and_unit',
            'nextgen_pro_slideshow_transition',
            'nextgen_pro_slideshow_transition_speed',
            'nextgen_pro_slideshow_slideshow_speed',
            'nextgen_pro_slideshow_border_size',
            'nextgen_pro_slideshow_border_color',
        );
    }

    /**
     * A similiar function is available in photocrati-nextgen_admin but has an inappropriate tooltip
     */
    function _render_nextgen_pro_slideshow_width_and_unit_field($display_type)
    {
        return $this->object->render_partial(
            'photocrati-nextgen_admin#field_generator/nextgen_settings_field_width_and_unit',
            array(
                'display_type_name' => $display_type->name,
                'name' => 'width',
                'label' => __('Gallery width', 'nextgen-gallery-pro'),
                'value' => $display_type->settings['width'],
                'text' => '',
                'placeholder' => '',
                'unit_name' => 'width_unit',
                'unit_value' => $display_type->settings['width_unit'],
                'options' => array('px' => __('Pixels', 'nextgen-gallery-pro'), '%' => __('Percent', 'nextgen-gallery-pro'))
            ),
            TRUE
        );
    }

    function _render_nextgen_pro_slideshow_image_crop_field($display_type)
    {
        return $this->_render_radio_field(
            $display_type,
            'image_crop',
            __('Crop images', 'nextgen-gallery-pro'),
            $display_type->settings['image_crop']
        );
    }

    function _render_nextgen_pro_slideshow_image_pan_field($display_type)
    {
        return $this->_render_radio_field(
            $display_type,
            'image_pan',
            __('Pan images', 'nextgen-gallery-pro'),
            $display_type->settings['image_pan'],
            '',
            empty($display_type->settings['image_crop']) ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_slideshow_show_captions_field($display_type)
    {
        return $this->_render_radio_field(
            $display_type,
            'show_captions',
            __('Show captions', 'nextgen-gallery-pro'),
            $display_type->settings['show_captions']
        );
    }

    function _render_nextgen_pro_slideshow_caption_class_field($display_type)
    {
        return $this->_render_select_field(
            $display_type,
            'caption_class',
            __('Caption location', 'nextgen-gallery-pro'),
            array(
                "caption_above_stage" => __("Top", 'nextgen-gallery-pro'),
                "caption_below_stage" => __("Bottom", 'nextgen-gallery-pro'),
                "caption_overlay_top" => __("Top (Overlay)", 'nextgen-gallery-pro'),
                "caption_overlay_bottom" => __("Bottom (Overlay)", 'nextgen-gallery-pro')
            ),
            $display_type->settings['caption_class'],
            '',
            empty($display_type->settings['show_captions']) ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_slideshow_slideshow_speed_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'slideshow_speed',
            __('Slideshow speed', 'nextgen-gallery-pro'),
            $display_type->settings['slideshow_speed'],
            __('Measured in seconds', 'nextgen-gallery-pro'),
            FALSE,
            __('seconds', 'nextgen-gallery-pro'),
            0
        );
    }

    function _render_nextgen_pro_slideshow_transition_field($display_type)
    {
        return $this->_render_select_field(
            $display_type,
            'transition',
            __('Transition effect', 'nextgen-gallery-pro'),
			array(
                'fade'      => __('Crossfade between images', 'nextgen-gallery-pro'),
                'flash'     => __('Fades into background color between images', 'nextgen-gallery-pro'),
                'pulse'     => __('Quickly move the image into the background color, then fade into the next image', 'nextgen-gallery-pro'),
                'slide'     => __('Slide images depending on image position', 'nextgen-gallery-pro'),
                'fadeslide' => __('Fade between images and slide slightly at the same time', 'nextgen-gallery-pro')
            ),
            $display_type->settings['transition'],
            '',
            FALSE
        );
    }

    function _render_nextgen_pro_slideshow_transition_speed_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'transition_speed',
            __('Transition speed', 'nextgen-gallery-pro'),
            $display_type->settings['transition_speed'],
            __('Measured in seconds', 'nextgen-gallery-pro'),
            FALSE,
            __('seconds', 'nextgen-gallery-pro'),
            0
        );
    }

    function _render_nextgen_pro_slideshow_border_size_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'border_size',
            __('Border size', 'nextgen-gallery-pro'),
            $display_type->settings['border_size'],
            __('Borders will not be applied if "Crop Images" is enabled', 'nextgen-gallery-pro'),
            !empty($display_type->settings['image_crop']) ? TRUE : FALSE,
            '',
            0
        );
    }

    function _render_nextgen_pro_slideshow_border_color_field($display_type)
    {
        return $this->_render_color_field(
            $display_type,
            'border_color',
            __('Border color', 'nextgen-gallery-pro'),
            $display_type->settings['border_color'],
            '',
            !empty($display_type->settings['image_crop']) ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_slideshow_aspect_ratio_field($display_type)
    {
        return $this->_render_select_field(
            $display_type,
            'aspect_ratio',
            __('Stage aspect ratio', 'nextgen-gallery-pro'),
			$this->_get_aspect_ratio_options(),
            $display_type->settings['aspect_ratio'],
            '',
            FALSE
        );
    }

    function _render_nextgen_pro_slideshow_show_playback_controls_field($display_type)
    {
        return $this->_render_radio_field(
            $display_type,
            'show_playback_controls',
            __('Show play controls', 'nextgen-gallery-pro'),
            $display_type->settings['show_playback_controls']
        );
    }
}
