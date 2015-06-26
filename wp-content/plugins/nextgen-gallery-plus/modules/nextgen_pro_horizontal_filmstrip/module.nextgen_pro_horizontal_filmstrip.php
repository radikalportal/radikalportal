<?php
/*
{
	Module: photocrati-nextgen_pro_horizontal_filmstrip,
    Depends: { photocrati-galleria, photocrati-nextgen_pro_slideshow }
}
 */
define('NGG_PRO_HORIZONTAL_FILMSTRIP', 'photocrati-nextgen_pro_horizontal_filmstrip');
class M_NextGen_Pro_Horizontal_Filmstrip extends C_Base_Module
{
	function define($context=FALSE)
	{
		parent::define(
			NGG_PRO_HORIZONTAL_FILMSTRIP,
			'NextGEN Pro Horizontal Filmstrip',
			"Provides Photocrati's Horizontal Filmstrip for NextGEN Gallery",
            '0.12',
			'http://www.nextgen-gallery.com',
			'Photocrati Media',
			'http://www.photocrati.com',
			$context
		);

		C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Horizontal_Filmstrip_Installer');
	}

	function get_type_list()
	{
		return array(
			'A_Nextgen_Pro_Horizontal_Filmstrip_Controller' => 'adapter.nextgen_pro_horizontal_filmstrip_controller.php',
			'A_Nextgen_Pro_Horizontal_Filmstrip_Form' => 'adapter.nextgen_pro_horizontal_filmstrip_form.php',
			'A_Nextgen_Pro_Horizontal_Filmstrip_Forms' => 'adapter.nextgen_pro_horizontal_filmstrip_forms.php',
			'A_Nextgen_Pro_Horizontal_Filmstrip_Mapper' => 'adapter.nextgen_pro_horizontal_filmstrip_mapper.php'
		);
	}

	function _register_adapters()
	{
		$this->get_registry()->add_adapter('I_Display_Type_Mapper', 'A_NextGen_Pro_Horizontal_Filmstrip_Mapper');

        if (!is_admin()) {
            $this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_NextGen_Pro_Horizontal_Filmstrip_Controller', $this->module_id);
        }

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Horizontal_Filmstrip_Form', $this->module_id);
            $this->get_registry()->add_adapter('I_Form_Manager', 'A_NextGen_Pro_Horizontal_Filmstrip_Forms');
        }
	}
}

class C_NextGen_Pro_Horizontal_Filmstrip_Installer extends C_Gallery_Display_Installer
{
    function install()
    {
        $this->install_display_types();
    }

    function install_display_types()
    {
        $this->install_display_type(
            NGG_PRO_HORIZONTAL_FILMSTRIP, array(
                'title'						=>	__('NextGEN Pro Horizontal Filmstrip', 'nextgen-gallery-pro'),
                'entity_types'				=>	array('image'),
                'default_source'			=>	'galleries',
                'preview_image_relpath'		=>	'photocrati-nextgen_pro_horizontal_filmstrip#preview.jpg',
                'hidden_from_ui'            =>  FALSE,
                'view_order' => NGG_DISPLAY_PRIORITY_BASE + (NGG_DISPLAY_PRIORITY_STEP * 10) + 20
            )
        );
    }

    function uninstall($hard)
    {
        $mapper = C_Display_Type_Mapper::get_instance();
        if (($entity = $mapper->find_by_name(NGG_PRO_HORIZONTAL_FILMSTRIP))) {
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

new M_NextGen_Pro_Horizontal_Filmstrip;