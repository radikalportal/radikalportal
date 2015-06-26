<?php

class A_NextGen_Pro_Captions_Resources extends Mixin
{
    static $_galleries_displayed = array();
    static $_pro_captions_run_once = FALSE;

    function get_effect_code($displayed_gallery)
    {
        // Swap the gallery placeholder
        $retval = $this->call_parent('get_effect_code', $displayed_gallery);

        if (isset($displayed_gallery->display_settings['captions_enabled'])
        &&  $displayed_gallery->display_settings['captions_enabled'])
            $retval .= ' data-ngg-captions-enabled="1" data-ngg-captions-id="' . $displayed_gallery->id() . '"';

        return $retval;
    }

    function enqueue_frontend_resources($displayed_gallery)
    {
        $this->call_parent('enqueue_frontend_resources', $displayed_gallery);

        if (!in_array($displayed_gallery->id(), self::$_galleries_displayed))
        {
            self::$_galleries_displayed[] = $displayed_gallery->id();

            $ds = $displayed_gallery->display_settings;
            if (!empty($ds['captions_enabled']) && $ds['captions_enabled'])
            {
                $this->object->_add_script_data(
                    'ngg_common',
                    'galleries.gallery_' . $displayed_gallery->id() . '.captions_enabled',
                    TRUE,
                    FALSE
                );
                $animation = (!empty($ds['captions_animation']) ? $ds['captions_animation'] : 'slideup');
                $this->object->_add_script_data(
                    'ngg_common',
                    'galleries.gallery_' . $displayed_gallery->id() . '.captions_animation',
                    $animation,
                    FALSE
                );
                $show_title = (!empty($ds['captions_display_title']) ? $ds['captions_display_title'] : TRUE);
                $this->object->_add_script_data(
                    'ngg_common',
                    'galleries.gallery_' . $displayed_gallery->id() . '.captions_display_title',
                    $show_title,
                    FALSE
                );
                $show_description = (!empty($ds['captions_display_description']) ? $ds['captions_display_description'] : TRUE);
                $this->object->_add_script_data(
                    'ngg_common',
                    'galleries.gallery_' . $displayed_gallery->id() . '.captions_display_description',
                    $show_description,
                    FALSE
                );
            }
            else {
                $this->object->_add_script_data(
                    'ngg_common',
                    'galleries.gallery_' . $displayed_gallery->id() . '.captions_enabled',
                    FALSE,
                    FALSE
                );
            }
        }

        if (isset($displayed_gallery->display_settings['captions_enabled'])
        &&  $displayed_gallery->display_settings['captions_enabled']
        &&  !self::$_pro_captions_run_once)
        {
            $router = C_Router::get_instance();
            wp_enqueue_script(
                'jquery.dotdotdot',
                $router->get_static_url('photocrati-nextgen_basic_album#jquery.dotdotdot-1.5.7-packed.js'),
                array('jquery')
            );
            wp_enqueue_script(
                'nextgen_pro_captions-js',
                $router->get_static_url('photocrati-nextgen_pro_captions#captions.js'),
                array('jquery.dotdotdot'),
                FALSE,
                TRUE
            );
            wp_enqueue_style(
                'nextgen_pro_captions-css',
                $router->get_static_url('photocrati-nextgen_pro_captions#captions.css')
            );

            self::$_pro_captions_run_once = TRUE;
        }
    }
}