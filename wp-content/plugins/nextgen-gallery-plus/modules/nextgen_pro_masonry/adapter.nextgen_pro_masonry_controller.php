<?php

/**
 * Provides rendering logic
 */
class A_NextGen_Pro_Masonry_Controller extends Mixin
{
    /**
     * Renders the front-end display for the masonry display type
     *
     * @param C_Displayed_Gallery $displayed_gallery
     * @param bool $return
     * @return string
     */
    function index_action($displayed_gallery, $return = FALSE)
    {
        $images = $displayed_gallery->get_included_entities();
        if (!$images)
        {
            return $this->object->render_partial("photocrati-nextgen_gallery_display#no_images_found", array(), $return);
        }
        else {
            $params = $displayed_gallery->display_settings;

            $params['images'] = $images;
            $params['storage'] = C_Gallery_Storage::get_instance();
            $params['effect_code'] = $this->object->get_effect_code($displayed_gallery);
            $params['displayed_gallery_id'] = $displayed_gallery->id();
            $params['thumbnail_size_name'] = C_Dynamic_Thumbnails_Manager::get_instance()->get_size_name(array(
                'width' => $params['size'],
                'crop' => FALSE
            ));
            $params = $this->object->prepare_display_parameters($displayed_gallery, $params);
            return $this->object->render_view('photocrati-nextgen_pro_masonry#index', $params, $return);
        }
    }

    /**
     * Enqueues all static resources required by this display type
     *
     * @param C_Displayed_Gallery $displayed_gallery
     */
    function enqueue_frontend_resources($displayed_gallery)
    {
        global $wp_version;

        wp_enqueue_style('nextgen_pro_masonry_style', $this->get_static_url('photocrati-nextgen_pro_masonry#style.css'));

        // Wordpress prior to 3.9 included an older version of Masonry than we wanted.
        if ($wp_version >= 3.9)
        {
            wp_enqueue_script(
                'nextgen_pro_masonry_script',
                $this->get_static_url('photocrati-nextgen_pro_masonry#nextgen_pro_masonry.js'),
                array('jquery', 'masonry')
            );
        }
        else {
            // When we began pro-masonry development Wordpress did not yet include Masonry; when they did include it
            // it was an older version than what NextGEN Pro had shipped with.
            //
            // For compatibility with <= 3.8 sites sites we use a different startup script and localize some settings
            if (wp_script_is('jquery-masonry', 'enqueued'))
            {
                wp_enqueue_script(
                    'nextgen_pro_masonry_script',
                    $this->get_static_url('photocrati-nextgen_pro_masonry#nextgen_pro_masonry_compat.js'),
                    array('jquery-masonry')
                );
                wp_localize_script(
                    'nextgen_pro_masonry_script',
                    'nextgen_pro_masonry_settings',
                    array(
                        'columnWidth' => $displayed_gallery->display_settings['size'],
                        'gutterWidth' => $displayed_gallery->display_settings['padding']
                    )
                );
            }
            else {
                // WP masonry isn't being used so we inject our own
                wp_enqueue_script(
                    'nextgen_pro_masonry_masonry_script',
                    $this->get_static_url('photocrati-nextgen_pro_masonry#masonry.min.js'),
                    array('jquery')
                );
                wp_enqueue_script(
                    'nextgen_pro_masonry_script',
                    $this->get_static_url('photocrati-nextgen_pro_masonry#nextgen_pro_masonry.js'),
                    array('nextgen_pro_masonry_masonry_script')
                );
            }
        }

        $this->call_parent('enqueue_frontend_resources', $displayed_gallery);
		$this->enqueue_ngg_styles();
    }
}
