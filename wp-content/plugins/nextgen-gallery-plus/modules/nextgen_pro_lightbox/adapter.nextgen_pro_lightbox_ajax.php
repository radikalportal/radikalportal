<?php

class A_NextGen_Pro_Lightbox_Ajax extends Mixin
{
    /**
     * Provides a Galleria-formatted JSON array of get_included_entities() results
     */
    function pro_lightbox_load_images_action()
    {
        $retval = array();

        if ($id = $this->param('id'))
        {
            $displayed_gallery_mapper = C_Displayed_Gallery_Mapper::get_instance();

            if ($this->param('lang', NULL, FALSE))
            {
                if (class_exists('SitePress'))
                {
                    global $sitepress;
                    $sitepress->switch_lang($this->param('lang'));
                }
            }

	        // Fetch ATP galleries or build our displayed gallery by parameters
	        if (is_numeric($id))
            {
		        $displayed_gallery = $displayed_gallery_mapper->find($id, TRUE);
	        }
	        else {
		        $factory = C_Component_Factory::get_instance();
		        $displayed_gallery = $factory->create(
                    'displayed_gallery',
                    $this->param('gallery'),
                    $displayed_gallery_mapper
                );
	        }

            if ($displayed_gallery)
            {
                $settings = C_NextGen_Settings::get_instance()->get('ngg_pro_lightbox');
                $retval   = M_NextGen_Pro_Lightbox::parse_entities_for_galleria(
                    $displayed_gallery->get_entities(FALSE, $settings['localize_limit'])
                );
            }
        }

        return $retval;
    }
}
