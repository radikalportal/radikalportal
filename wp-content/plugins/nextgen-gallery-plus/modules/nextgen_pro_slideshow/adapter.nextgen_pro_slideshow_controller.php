<?php

// TODO: This should be replaced by a dynamic stylesheet adapter
class A_NextGen_Pro_Slideshow_Controller extends A_Galleria_Controller
{
	function get_custom_css_rules($displayed_gallery)
	{
		return $this->object->render_partial(
			'photocrati-nextgen_pro_slideshow#nextgen_pro_slideshow_css',
			$displayed_gallery->display_settings,
			TRUE
		);
	}

    function enqueue_frontend_resources($displayed_gallery)
    {
        $router = C_Router::get_instance();
        $displayed_gallery->display_settings['theme'] = $router->get_static_url('photocrati-nextgen_pro_slideshow#theme/galleria.nextgen_pro_slideshow.js');
        return C_Display_Type_Controller::get_instance(NGG_PRO_GALLERIA)
                                        ->enqueue_frontend_resources($displayed_gallery);
    }
}
