<?php

class A_NextGen_Pro_Lightbox_Effect_Code extends Mixin
{
    static $galleries_displayed = array();

    function get_effect_code($displayed_gallery)
    {
        $retval = $this->call_parent('get_effect_code', $displayed_gallery);

        if (C_NextGen_Settings::get_instance()->thumbEffect == NGG_PRO_LIGHTBOX)
        {
            $retval = str_replace('%PRO_LIGHTBOX_GALLERY_ID%', $displayed_gallery->id(), $retval);

            $mapper = C_Lightbox_Library_Mapper::get_instance();
            $lightbox = $mapper->find_by_name(NGG_PRO_LIGHTBOX);
            if ($lightbox->display_settings['display_comments'])
                $retval .= ' data-nplmodal-show-comments="1"';
        }

        return $retval;
    }

    function enqueue_frontend_resources($displayed_gallery)
    {
        $this->call_parent('enqueue_frontend_resources', $displayed_gallery);

        if (C_NextGen_Settings::get_instance()->thumbEffect == NGG_PRO_LIGHTBOX
        &&  !in_array($displayed_gallery->id(), self::$galleries_displayed))
        {
            // prevent calling get_included_entities() more than once
            self::$galleries_displayed[] = $displayed_gallery->id();

            foreach (M_NextGen_Pro_Lightbox_Legacy::get_components() as $name => $handler) {
                $handler = new $handler();
                $handler->name = $name;
                $handler->displayed_gallery = $displayed_gallery;
                $handler->enqueue_static_resources();
            }

            $mapper = C_Lightbox_Library_Mapper::get_instance();
            $lightbox = $mapper->find_by_name(NGG_PRO_LIGHTBOX);

            // localize the gallery images for startup performance
            $this->object->_add_script_data(
                'ngg_common',
                'galleries.gallery_' . $displayed_gallery->id() . '.images_list',
                M_NextGen_Pro_Lightbox_Legacy::parse_entities_for_galleria($displayed_gallery->get_entities($lightbox->display_settings['localize_limit'])),
                FALSE
            );

            // inform the lightbox js it needs to do an ajax request to load the rest of the gallery
            $this->object->_add_script_data(
                'ngg_common',
                'galleries.gallery_' . $displayed_gallery->id() . '.images_list_limit_reached',
                ($displayed_gallery->get_entity_count() > $lightbox->display_settings['localize_limit']) ? TRUE : FALSE,
                FALSE
            );
        }
    }
}