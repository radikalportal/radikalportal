<?php
/*
{
	Module: photocrati-nextgen_pro_film
}
 */
define('NGG_PRO_FILM', 'photocrati-nextgen_pro_film');
class M_NextGen_Pro_Film extends C_Base_Module
{
	function define($context=FALSE)
	{
		parent::define(
			'photocrati-nextgen_pro_film',
			'NextGEN Pro Film',
			'Provides a film-like gallery for NextGEN Gallery',
            '0.13',
			'http://www.nextgen-gallery.com',
			'Photocrati Media',
			'http://www.photocrati.com',
			$context
		);

		C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Film_Installer');
	}

	function get_type_list()
	{
		return array(
			'A_Nextgen_Pro_Film_Controller' => 'adapter.nextgen_pro_film_controller.php',
			'A_Nextgen_Pro_Film_Dynamic_Styles' => 'adapter.nextgen_pro_film_dynamic_styles.php',
			'A_Nextgen_Pro_Film_Form' => 'adapter.nextgen_pro_film_form.php',
			'A_Nextgen_Pro_Film_Forms' => 'adapter.nextgen_pro_film_forms.php',
			'A_Nextgen_Pro_Film_Mapper' => 'adapter.nextgen_pro_film_mapper.php',
		);
	}

	function _register_adapters()
	{
		$this->get_registry()->add_adapter('I_Display_Type_Mapper', 'A_NextGen_Pro_Film_Mapper');

        if (!is_admin()) {
            C_Dynamic_Stylesheet_Controller::get_instance('all')
                ->register('nextgen_pro_film', 'photocrati-nextgen_pro_film#nextgen_pro_film_dyncss');

            $this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_NextGen_Pro_Film_Controller', $this->module_id);
        }

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Film_Form', $this->module_id);
            $this->get_registry()->add_adapter('I_Form_Manager', 'A_NextGen_Pro_Film_Forms');
        }
	}
}

class C_NextGen_Pro_Film_Installer extends C_Gallery_Display_Installer
{
    function install()
    {
        $this->install_display_types();
    }

    function install_display_types()
    {
        $this->install_display_type(
            NGG_PRO_FILM, array(
                'title'						=>	__('NextGEN Pro Film', 'nextgen-gallery-pro'),
                'entity_types'				=>	array('image'),
                'default_source'			=>	'galleries',
                'preview_image_relpath'		=>	'photocrati-nextgen_pro_film#preview.jpg',
                'hidden_from_ui'            =>  FALSE,
                'view_order' => NGG_DISPLAY_PRIORITY_BASE + (NGG_DISPLAY_PRIORITY_STEP * 10) + 30
            )
        );
    }

    function uninstall($hard)
    {
        $mapper = C_Display_Type_Mapper::get_instance();
        if (($entity = $mapper->find_by_name(NGG_PRO_FILM))) {
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

new M_NextGen_Pro_Film;
