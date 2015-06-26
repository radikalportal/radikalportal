<?php

class A_Image_Protection_Form extends Mixin
{
    function get_model()
    {
        return C_Settings_Model::get_instance();
    }

    function get_title()
    {
        return __('Image Protection', 'nextgen-gallery-pro');
    }

    function _get_field_names()
    {
        return array(
            'nextgen_pro_image_protection_enable',
            'nextgen_pro_image_protection_global',
        );
    }

    function enqueue_static_resources()
    {
        wp_enqueue_style(
            'nextgen_pro_image_protection_admin_settings_style',
            $this->object->get_static_url('photocrati-image_protection#settings.css')
        );
        wp_enqueue_script(
            'nextgen_pro_image_protection_admin_settings_script',
            $this->get_static_url('photocrati-image_protection#settings.js'),
            array('jquery.nextgen_radio_toggle')
        );
    }

    function _render_nextgen_pro_image_protection_enable_field($settings)
    {
        $model = new stdClass;
        $model->name = 'image_protection';
        $field = $this->object->_render_radio_field(
            $model,
            'protect_images',
            __('Protect images', 'nextgen-gallery-pro'),
            C_NextGen_Settings::get_instance()->protect_images,
            __('Protect images from being downloaded both by right click or drag &amp; drop', 'nextgen-gallery-pro')
        );

        return $field;
    }

    function _render_nextgen_pro_image_protection_global_field($settings)
    {
        $model = new stdClass;
        $model->name = 'image_protection';
        $field = $this->object->_render_radio_field(
            $model,
            'protect_images_globally',
            __('Disable right click menu completely', 'nextgen-gallery-pro'),
            C_NextGen_Settings::get_instance()->protect_images_globally,
            __('By default the right click menu is only disabled for NextGEN images. Enable this to disable the right click menu on the whole page.', 'nextgen-gallery-pro'),
            !empty(C_NextGen_Settings::get_instance()->protect_images) ? FALSE : TRUE
        );

        return $field;
    }

    function save_action($options)
    {
        if (!empty($options))
        {
            $settings = C_NextGen_Settings::get_instance();
            $settings->protect_images          = $options['protect_images'];
            $settings->protect_images_globally = $options['protect_images_globally'];
            $settings->save();
        }
    }
}