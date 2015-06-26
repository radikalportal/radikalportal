<?php

/*
 {
	Module:		photocrati-nextgen_pro_slideshow,
	Depends:	{ photocrati-galleria }
 }
 */

define('NGG_PRO_SLIDESHOW', 'photocrati-nextgen_pro_slideshow');

class M_NextGen_Pro_Slideshow extends C_Base_Module
{
	function define($context=FALSE)
	{
		parent::define(
			'photocrati-nextgen_pro_slideshow',
			'NextGen Pro Slideshow',
			"Provides Photocrati's Slideshow Gallery Type",
            '0.12',
			'http://www.photocrati.com',
			'Photocrati Media',
			'http://www.photocrati.com',
			$context
		);

		C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Slideshow_Installer');
	}

	function get_type_list()
	{
		return array(
			'A_Nextgen_Pro_Slideshow' => 'adapter.nextgen_pro_slideshow.php',
			'A_Nextgen_Pro_Slideshow_Controller' => 'adapter.nextgen_pro_slideshow_controller.php',
			'A_Nextgen_Pro_Slideshow_Form' => 'adapter.nextgen_pro_slideshow_form.php',
			'A_Nextgen_Pro_Slideshow_Forms' => 'adapter.nextgen_pro_slideshow_forms.php',
			'A_Nextgen_Pro_Slideshow_Mapper' => 'adapter.nextgen_pro_slideshow_mapper.php'
		);
	}


	function _register_adapters()
	{
		// Provides default values for the display type
		$this->get_registry()->add_adapter('I_Display_Type_Mapper', 'A_NextGen_Pro_Slideshow_Mapper');

        if (!is_admin())
        {
            // Provides settings fields and frontend rendering
            $this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_NextGen_Pro_Slideshow_Controller', $this->module_id);
        }

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            // Provides a display settings form
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Slideshow_Form', $this->module_id);
            $this->get_registry()->add_adapter('I_Form_Manager', 'A_NextGen_Pro_Slideshow_Forms');
        }
	}
}

class C_NextGen_Pro_Slideshow_Installer extends C_Gallery_Display_Installer
{
    function install($reset=FALSE)
    {
        $this->install_display_types();
    }

    function install_display_types()
    {
        $this->install_display_type(
            NGG_PRO_SLIDESHOW, array(
                'title'							=>	__('NextGEN Pro Slideshow', 'nextgen-gallery-pro'),
                'entity_types'					=>	array('image'),
                'default_source'				=>	'galleries',
                'preview_image_relpath'			=>	'photocrati-nextgen_pro_slideshow#preview.jpg',
                'hidden_from_ui'                =>  FALSE,
                'view_order' => NGG_DISPLAY_PRIORITY_BASE + (NGG_DISPLAY_PRIORITY_STEP * 10) + 10
            )
        );
    }

    function uninstall($hard=FALSE)
    {
        $mapper = C_Display_Type_Mapper::get_instance();
        if (($entity = $mapper->find_by_name(NGG_PRO_SLIDESHOW))) {
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

new M_NextGen_Pro_Slideshow;