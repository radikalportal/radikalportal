<?php

/***
{
Product: photocrati-nextgen-plus,
Depends: { photocrati-nextgen }
}
 ***/

class P_Photocrati_NextGen_Plus extends C_Base_Product
{
    static $modules = array();

    function define_modules()
    {
        $modules = array(
	        'photocrati-nextgen_pro_i18n',
	        'photocrati-nextgen_picturefill',
            'photocrati-image_protection',
	        'photocrati-comments',
            'photocrati-galleria',
            'photocrati-nextgen_pro_slideshow',
            'photocrati-nextgen_pro_horizontal_filmstrip',
            'photocrati-nextgen_pro_thumbnail_grid',
            'photocrati-nextgen_pro_blog_gallery',
            'photocrati-nextgen_pro_film',
            'photocrati-nextgen_pro_masonry',
            'photocrati-nextgen_pro_albums',
        );

        // Add auto-update modules if this is an admin request
        if (is_admin()) {
            $modules = array_merge($modules, array(
                'photocrati-auto_update',
                'photocrati-auto_update-admin'
            ));
        }

        // If we're using a recent version of NGG, then use the Pro Lightbox module
        if (version_compare(NGG_PLUGIN_VERSION, '2.0.67') >= 0)
            $modules[] = 'photocrati-nextgen_pro_lightbox';
        else $modules[] = 'photocrati-nextgen_pro_lightbox_legacy';

        $modules[] = 'photocrati-nextgen_pro_captions';

        self::$modules = $modules;
    }

    function define()
    {
        parent::define(
            'photocrati-nextgen-plus',
            'Photocrati NextGEN Plus',
            'Photocrati NextGEN Plus',
            NGG_PLUS_PLUGIN_VERSION,
            'http://www.nextgen-gallery.com',
            'Photocrati Media',
            'http://www.photocrati.com'
        );

        $module_path = path_join(dirname(__FILE__), 'modules');
        $registry = $this->get_registry();
        $registry->set_product_module_path($this->module_id, $module_path);
        $registry->add_module_path($module_path, TRUE, FALSE);
        $this->define_modules();

        foreach (self::$modules as $module_name) $registry->load_module($module_name);

        include_once('class.nextgen_plus_installer.php');
        C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Plus_Installer');
    }
}

new P_Photocrati_NextGen_Plus();
