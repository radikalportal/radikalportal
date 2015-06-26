<?php

class A_NextGen_Pro_Captions_Form extends Mixin
{
    function _get_field_names()
    {
        $fields = $this->call_parent('_get_field_names');
        $fields[] = 'nextgen_pro_captions_enabled';
        $fields[] = 'nextgen_pro_captions_display_sharing';
        $fields[] = 'nextgen_pro_captions_display_title';
        $fields[] = 'nextgen_pro_captions_display_description';
        $fields[] = 'nextgen_pro_captions_animation';
        return $fields;
    }

    function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');

	    wp_enqueue_script(
            'photocrati-nextgen_pro_captions_settings-js',
            $this->get_static_url('photocrati-nextgen_pro_captions#settings.js'),
            array('jquery.nextgen_radio_toggle')
        );
    }

    function _render_nextgen_pro_captions_enabled_field($display_type)
    {
        return $this->_render_radio_field(
            $display_type,
            'captions_enabled',
            __('Enable caption overlay', 'nextgen-gallery-pro'),
            isset($display_type->settings['captions_enabled']) ? $display_type->settings['captions_enabled'] : FALSE
        );
    }

    function _render_nextgen_pro_captions_display_sharing_field($display_type)
    {
        return $this->_render_radio_field(
            $display_type,
            'captions_display_sharing',
            __('Display share icons', 'nextgen-gallery-pro'),
            isset($display_type->settings['captions_display_sharing']) ? $display_type->settings['captions_display_sharing'] : TRUE,
            '',
            empty($display_type->settings['captions_enabled']) ? TRUE : FALSE
        );
    }
    
    function _render_nextgen_pro_captions_display_title_field($display_type)
    {
        return $this->_render_radio_field(
            $display_type,
            'captions_display_title',
            __('Display image title', 'nextgen-gallery-pro'),
            isset($display_type->settings['captions_display_title']) ? $display_type->settings['captions_display_title'] : TRUE,
            '',
            empty($display_type->settings['captions_enabled']) ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_captions_display_description_field($display_type)
    {
        return $this->_render_radio_field(
            $display_type,
            'captions_display_description',
            __('Display image description', 'nextgen-gallery-pro'),
            isset($display_type->settings['captions_display_description']) ? $display_type->settings['captions_display_description'] : TRUE,
            '',
            empty($display_type->settings['captions_enabled']) ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_captions_animation_field($display_type)
    {
        return $this->_render_select_field(
            $display_type,
            'captions_animation',
            __('Animation type', 'nextgen-gallery-pro'),
            array(
                'fade'      => __('Fade in',    'nextgen-gallery-pro'),
                'slideup'   => __('Slide up',   'nextgen-gallery-pro'),
                'slidedown' => __('Slide down', 'nextgen-gallery-pro'),
                'slideleft' => __('Slide left', 'nextgen-gallery-pro'),
                'slidedown' => __('Slide down', 'nextgen-gallery-pro'),
                'titlebar'  => __('Titlebar',   'nextgen-gallery-pro'),
                'plain'     => __('Plain',      'nextgen-gallery-pro')
            ),
            isset($display_type->settings['captions_animation']) ? $display_type->settings['captions_animation'] : 'slideup',
            '',
            empty($display_type->settings['captions_enabled']) ? TRUE : FALSE
        );
    }
}
