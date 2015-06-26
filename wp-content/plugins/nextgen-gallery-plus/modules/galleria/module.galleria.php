<?php

/*
{
	Module: photocrati-galleria
}
 */

define('NGG_PRO_GALLERIA', 'photocrati-galleria');
if (!defined('NGG_ALLOW_CROSS_ORIGIN_FRAMING')) define('NGG_ALLOW_CROSS_ORIGIN_FRAMING', TRUE);

class M_Galleria extends C_Base_Module
{
	function define($context=FALSE)
	{
		parent::define(
			'photocrati-galleria',
			'Galleria',
			'Provides support for displaying galleries using Galleria Themes',
            '0.11',
			'http://www.nextgen-gallery.com',
			'Photocrati Media',
			'http://www.photocrati.com',
			$context
		);
	}

	function get_type_list()
	{
		return array(
			'A_Galleria_Controller' => 'adapter.galleria_controller.php',
			'C_Galleria_Iframe_Controller' => 'class.galleria_iframe_controller.php'
		);
	}

	function _register_adapters()
	{
        // Adapt the controller for the Galleria display type
        $this->get_registry()->add_adapter(
            'I_Display_Type_Controller',
            'A_Galleria_Controller',
            $this->module_id
        );
	}

	function _register_utilities()
	{
        $this->get_registry()->add_utility(
            'I_Galleria_iFrame_Controller',
            'C_Galleria_iFrame_Controller'
        );
	}

    function _register_hooks()
    {
        if (!is_admin()) add_action('init', array(&$this, 'define_routes'), 2);
    }

    function define_routes()
    {
        $router   = C_Router::get_instance();
        $app = $router->create_app('/nextgen-galleria-gallery');
        $app->rewrite("/{id}", "/id--{id}");
        $app->route('/', 'I_Galleria_iFrame_Controller#index');
    }
}

new M_Galleria();
