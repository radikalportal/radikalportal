<?php
/***
{
    Module: photocrati-nextgen_pro_masonry,
    Depends: { photocrati-nextgen_gallery_display }
}
***/

define('NGG_PRO_MASONRY', 'photocrati-nextgen_pro_masonry');

class M_NextGen_Pro_Masonry extends C_Base_Module
{
    function define()
    {
        parent::define(
            'photocrati-nextgen_pro_masonry',
            'NextGEN Pro Masonry',
            'Provides the NextGEN Pro Masonry Display Type',
            '0.12',
            'http://www.nextgen-gallery.com',
            'Photocrati Media',
            'http://www.photocrati.com'
        );

		C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Masonry_Installer');
    }

    /**
     * Register adapters
     */
    function _register_adapters()
    {
        // Add display type
        $this->get_registry()->add_adapter('I_Display_Type_Mapper', 'A_NextGen_Pro_Masonry_Mapper');

        if (!is_admin())
        {
            // Add controller
            $this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_NextGen_Pro_Masonry_Controller', $this->module_id);
        }

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            // Add settings form
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Masonry_Form', $this->module_id);
            $this->get_registry()->add_adapter('I_Form_Manager', 'A_NextGen_Pro_Masonry_Forms');
        }
    }

    function get_type_list()
    {
        return array(
            'A_Nextgen_Pro_Masonry_Controller' => 'adapter.nextgen_pro_masonry_controller.php',
            'A_Nextgen_Pro_Masonry_Form' => 'adapter.nextgen_pro_masonry_form.php',
            'A_Nextgen_Pro_Masonry_Forms' => 'adapter.nextgen_pro_masonry_forms.php',
            'A_Nextgen_Pro_Masonry_Mapper' => 'adapter.nextgen_pro_masonry_mapper.php'
        );
    }
}

class C_NextGen_Pro_Masonry_Installer extends C_Gallery_Display_Installer
{
    function install($reset=FALSE)
    {
        $this->install_display_types();
    }

    function install_display_types()
    {
        $this->install_display_type(
            NGG_PRO_MASONRY, array(
                'title'                 => __('NextGEN Pro Masonry', 'nextgen-gallery-pro'),
                'entity_types'          => array('image'),
                'preview_image_relpath' => 'photocrati-nextgen_pro_masonry#preview.jpg',
                'default_source'        => 'galleries',
                'hidden_from_ui'        => FALSE,
                'view_order'            => NGG_DISPLAY_PRIORITY_BASE + (NGG_DISPLAY_PRIORITY_STEP * 10) + 50
            )
        );
    }

    function uninstall($hard=FALSE)
    {
        $mapper = C_Display_Type_Mapper::get_instance();
        if (($entity = $mapper->find_by_name(NGG_PRO_MASONRY))) {
            if ($hard)
            {
                $mapper->destroy($entity);
            }
            else {
                $entity->hidden_from_ui = TRUE;
                $mapper->save($entity);
            }
        }
    }
}

new M_NextGen_Pro_Masonry();