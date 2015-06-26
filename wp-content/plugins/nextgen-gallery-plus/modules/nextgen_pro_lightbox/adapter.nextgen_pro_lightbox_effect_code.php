<?php

class A_NextGen_Pro_Lightbox_Effect_Code extends Mixin
{
    static $galleries_displayed = array();

    function get_effect_code($displayed_gallery)
    {
        // Swap the gallery placeholder
        $retval = $this->call_parent('get_effect_code', $displayed_gallery);
        $retval = str_replace('%PRO_LIGHTBOX_GALLERY_ID%', $displayed_gallery->id(), $retval);

        $lightbox = C_Lightbox_Library_Manager::get_instance()->get(NGG_PRO_LIGHTBOX);
        if ($lightbox && $lightbox->values['nplModalSettings']['enable_comments']
        &&  $lightbox->values['nplModalSettings']['display_comments'])
            $retval .= ' data-nplmodal-show-comments="1"';

        return $retval;
    }

    function enqueue_frontend_resources($displayed_gallery)
    {
        $this->call_parent('enqueue_frontend_resources', $displayed_gallery);

        if (!in_array($displayed_gallery->id(), self::$galleries_displayed))
        {
            // prevent calling get_included_entities() more than once
            self::$galleries_displayed[] = $displayed_gallery->id();

            foreach (M_NextGen_Pro_Lightbox::get_components() as $name => $handler) {
                $handler = new $handler();
                $handler->name = $name;
                $handler->displayed_gallery = $displayed_gallery;
                $handler->enqueue_static_resources();
            }

            $settings = C_NextGen_Settings::get_instance()->get('ngg_pro_lightbox');

            // localize the gallery images for startup performance
            $this->object->_add_script_data(
                'ngg_common',
                'galleries.gallery_' . $displayed_gallery->id() . '.images_list',
                M_NextGen_Pro_Lightbox::parse_entities_for_galleria($displayed_gallery->get_entities($settings['localize_limit'])),
                FALSE
            );

            // inform the lightbox js it needs to do an ajax request to load the rest of the gallery
            $this->object->_add_script_data(
                'ngg_common',
                'galleries.gallery_' . $displayed_gallery->id() . '.images_list_limit_reached',
                ($displayed_gallery->get_entity_count() > $settings['localize_limit']) ? TRUE : FALSE,
                FALSE
            );
        }
    }
}