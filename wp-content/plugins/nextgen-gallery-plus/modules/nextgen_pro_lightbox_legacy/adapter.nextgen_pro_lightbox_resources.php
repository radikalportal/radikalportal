<?php

class A_Nextgen_Pro_Lightbox_Resources extends Mixin
{
    static protected $run_once = FALSE;

    function enqueue_lightbox_resources($displayed_gallery=FALSE)
    {
        $this->call_parent('enqueue_lightbox_resources', $displayed_gallery);
        $this->enqueue_pro_lightbox_resources($displayed_gallery);
    }

    function enqueue_pro_lightbox_resources($displayed_gallery=FALSE)
    {
        $settings = C_NextGen_Settings::get_instance();
        if ($settings->thumbEffect == NGG_PRO_LIGHTBOX)
        {
            $router = C_Router::get_instance();

            if (!self::$run_once)
            {
                // ensure the gallery exists
                if ($displayed_gallery && $displayed_gallery->id())
                {
                    $this->object->_add_script_data(
                        'ngg_common',
                        'galleries.gallery_' . $displayed_gallery->id() . '.wordpress_page_root',
                        get_permalink(),
                        FALSE
                    );
                }

                wp_enqueue_script('underscore');
                wp_enqueue_script('backbone');
                wp_enqueue_script(
                    'velocity',
                    $router->get_static_url('photocrati-nextgen_pro_lightbox_legacy#velocity.min.js')
                );

                wp_enqueue_script(
                    'galleria',
                    $router->get_static_url('photocrati-galleria#galleria-1.4.2.min.js')
                );
                wp_enqueue_style(
                    'ngg_pro_lightbox_theme_css',
                    $router->get_static_url('photocrati-nextgen_pro_lightbox_legacy#theme/galleria.nextgen_pro_lightbox.css')
                );
                wp_enqueue_script(
                    'ngg_pro_lightbox_theme_js',
                    $router->get_static_url('photocrati-nextgen_pro_lightbox_legacy#theme/galleria.nextgen_pro_lightbox.js'),
                    'galleria'
                );

                if (!wp_style_is('fontawesome', 'registered'))
                    C_Display_Type_Controller::get_instance()->enqueue_displayed_gallery_trigger_buttons_resources();
                wp_enqueue_style('fontawesome');

                // retrieve the lightbox so we can examine its settings
                $mapper = C_Lightbox_Library_Mapper::get_instance();
                $library = $mapper->find_by_name(NGG_PRO_LIGHTBOX, TRUE);

                $library->display_settings += array(
                    'is_front_page'  => is_front_page() ? 1 : 0,
                    'share_url'      => $router->get_url('/nextgen-share/{gallery_id}/{image_id}/{named_size}', TRUE, 'root'),
                    'protect_images' => (!empty($settings->protect_images) ? TRUE :  FALSE),
                    'style'          => str_replace('.css', '', $library->display_settings['style']), // this once (~2.1.4) referenced files
                    'i18n'           => array(
                        'toggle_social_sidebar' => __('Toggle social sidebar', 'nextgen-gallery-pro'),
                        'play_pause'            => __('Play / Pause', 'nextgen-gallery-pro'),
                        'toggle_fullscreen'     => __('Toggle fullscreen', 'nextgen-gallery-pro'),
                        'toggle_image_info'     => __('Toggle image info', 'nextgen-gallery-pro'),
                        'close_window'          => __('Close window', 'nextgen-gallery-pro'),
                        'share' => array(
                            'twitter'   => __('Share on Twitter', 'nextgen-gallery-pro'),
                            'googlep'   => __('Share on Google+', 'nextgen-gallery-pro'),
                            'facebook'  => __('Share on Facebook', 'nextgen-gallery-pro'),
                            'pinterest' => __('Share on Pinterest', 'nextgen-gallery-pro')
                        )
                    )
                );

                // provide the current language so ajax requests can request translations in the same locale
                if (defined('ICL_LANGUAGE_CODE'))
                    $library->display_settings['lang'] = $router->param('lang', NULL, FALSE) ? $router->param('lang') : ICL_LANGUAGE_CODE;

                wp_localize_script(
                    'photocrati_ajax',
                    'nplModalSettings',
                    $library->display_settings
                );
            }

            self::$run_once = TRUE;
        }
    }
}
