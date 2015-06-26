<?php

class C_NextGen_Pro_Lightbox_Trigger extends C_Displayed_Gallery_Trigger
{
    static $_pro_lightbox_enabled = NULL;

    function get_css_class()
    {
        $classes = 'fa ngg-trigger nextgen_pro_lightbox';

        if ($this->name == NGG_PRO_LIGHTBOX_TRIGGER) {
            return $classes . ' fa-share-square';
        }
        else {
            return $classes . ' fa-comment';
        }
    }

    static function is_renderable($name, $displayed_gallery)
    {
        $retval = FALSE;

        // Both of these triggers require the Pro Lightbox to be configured as the lightbox effect
        if (self::are_triggers_enabled($displayed_gallery) && self::is_pro_lightbox_enabled() && self::does_source_return_images($displayed_gallery)) {

            // If comments are enabled, display the trigger button to open the comments sidebar
            if ($name == NGG_PRO_LIGHTBOX_COMMENT_TRIGGER) {
                $library = self::get_pro_lightbox();
                if (isset($library->values['nplModalSettings']['enable_comments']) && $library->values['nplModalSettings']['enable_comments']) {
                    $retval = TRUE;
                }
            }

            else {
                $retval = TRUE;
            }
        }

        return $retval;
    }

    static function does_source_return_images($displayed_gallery)
    {
        $retval = FALSE;

        if (($source = $displayed_gallery->get_source()) && in_array('image', $source->returns)) {
            $retval = TRUE;
        }

        return $retval;
    }

    static function is_pro_lightbox_enabled()
    {
        if (is_null(self::$_pro_lightbox_enabled)) {
            $settings	= C_NextGen_Settings::get_instance();
            if ($settings->thumbEffect == NGG_PRO_LIGHTBOX)
                self::$_pro_lightbox_enabled = TRUE;
            else
                self::$_pro_lightbox_enabled = FALSE;
        }

        return self::$_pro_lightbox_enabled;
    }

    static function are_triggers_enabled($displayed_gallery)
    {
        return isset($displayed_gallery->display_settings['ngg_triggers_display']) && $displayed_gallery->display_settings['ngg_triggers_display'] != 'never';
    }


    static function get_pro_lightbox()
    {
        return C_Lightbox_Library_Manager::get_instance()->get(NGG_PRO_LIGHTBOX);
    }

    function get_attributes()
    {
        $retval = array(
            'class'                     =>  $this->get_css_class(),
            'data-nplmodal-gallery-id'  =>  $this->displayed_gallery->id()
        );

        // If we're adding the trigger to an image, then we need
        // to add an attribute for the Pro Lightbox to know which image to display
        if ($this->view->get_id() == 'nextgen_gallery.image') {
            $image                      =   $this->view->get_object();
            $retval['data-image-id']    =   $image->{$image->id_field};
        }

        // If we're adding the commenting trigger, then we need to tell the
        // Pro Lightbox to open the sidebar when clicked
        if ($this->name == NGG_PRO_LIGHTBOX_COMMENT_TRIGGER) {
            $retval['data-nplmodal-show-comments'] = 1;
        }

        return $retval;
    }

	function enqueue_resources($displayed_gallery)
	{
		$router   = C_Router::get_instance();
		$enqueue  = FALSE;

		if (!wp_style_is('fontawesome', 'registered'))
			$enqueue = C_Display_Type_Controller::get_instance()->enqueue_displayed_gallery_trigger_buttons_resources($displayed_gallery);

		if ($enqueue !== FALSE) {
			if (!wp_style_is('ngg-trigger-buttons', 'registered')) {
				wp_register_style(
					'ngg-trigger-buttons',
					$router->get_static_url('photocrati-nextgen_pro_lightbox#trigger_buttons.css'),
					false
				);
				wp_enqueue_style('ngg-trigger-buttons');
			}
		}
	}
}