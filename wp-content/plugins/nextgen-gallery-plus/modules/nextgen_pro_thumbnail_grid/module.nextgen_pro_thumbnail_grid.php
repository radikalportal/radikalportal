<?php

/*
{
	Module: photocrati-nextgen_pro_thumbnail_grid
}
 */

define('NGG_PRO_THUMBNAIL_GRID', 'photocrati-nextgen_pro_thumbnail_grid');

class M_NextGen_Pro_Thumbnail_Grid extends C_Base_Module
{
	function define($context=FALSE)
	{
		parent::define(
			NGG_PRO_THUMBNAIL_GRID,
			'NextGen Pro Thumbnail Grid',
			'Provides a thumbnail grid for NextGEN Pro',
            '0.12',
			'http://www.photocrati.com',
			'Photocrati Media',
			'http://www.photocrati.com',
			$context
		);

		C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Thumbnail_Grid_Installer');
	}

	function get_type_list()
	{
		return array(
			'A_Nextgen_Pro_Thumbnail_Grid_Controller' => 'adapter.nextgen_pro_thumbnail_grid_controller.php',
			'A_Nextgen_Pro_Thumbnail_Grid_Dynamic_Styles' => 'adapter.nextgen_pro_thumbnail_grid_dynamic_styles.php',
			'A_Nextgen_Pro_Thumbnail_Grid_Form' => 'adapter.nextgen_pro_thumbnail_grid_form.php',
			'A_Nextgen_Pro_Thumbnail_Grid_Forms' => 'adapter.nextgen_pro_thumbnail_grid_forms.php',
			'A_Nextgen_Pro_Thumbnail_Grid_Mapper' => 'adapter.nextgen_pro_thumbnail_grid_mapper.php'
		);
	}

	function _register_adapters()
	{
        $this->get_registry()->add_adapter('I_Display_Type_Mapper', 'A_NextGen_Pro_Thumbnail_Grid_Mapper');

        if (!is_admin())
        {
            $this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_NextGen_Pro_Thumbnail_Grid_Controller', $this->module_id);
            C_Dynamic_Stylesheet_Controller::get_instance('all')
                ->register('nextgen_pro_thumbnail_grid', 'photocrati-nextgen_pro_thumbnail_grid#nextgen_pro_thumbnail_grid_dyncss');
        }

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Thumbnail_Grid_Form', $this->module_id);
            $this->get_registry()->add_adapter('I_Form_Manager', 'A_NextGen_Pro_Thumbnail_Grid_Forms');
        }
	}
}

class C_NextGen_Pro_Thumbnail_Grid_Installer extends C_Gallery_Display_Installer
{
    function install($reset=FALSE)
    {
        $this->install_display_types();
    }

    function install_display_types()
    {
        $this->install_display_type(
            NGG_PRO_THUMBNAIL_GRID, array(
                'title'							=>	__('NextGEN Pro Thumbnail Grid', 'nextgen-gallery-pro'),
                'entity_types'					=>	array('image'),
                'preview_image_relpath'			=>	'photocrati-nextgen_pro_thumbnail_grid#preview.jpg',
                'default_source'				=>	'galleries',
                'hidden_from_ui'                =>  FALSE,
                'view_order' => NGG_DISPLAY_PRIORITY_BASE + (NGG_DISPLAY_PRIORITY_STEP * 10)
            )
        );
    }

    function uninstall($hard=FALSE)
    {
        $mapper = C_Display_Type_Mapper::get_instance();
        if (($entity = $mapper->find_by_name(NGG_PRO_THUMBNAIL_GRID))) {
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

new M_NextGen_Pro_Thumbnail_Grid;