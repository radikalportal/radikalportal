<?php
/*
{
	Module:	photocrati-nextgen_pro_albums
}
 */

define('NGG_PRO_ALBUMS', 'photocrati-nextgen_pro_albums');
define('NGG_PRO_LIST_ALBUM',		 'photocrati-nextgen_pro_list_album');
define('NGG_PRO_GRID_ALBUM',		 'photocrati-nextgen_pro_grid_album');

class M_NextGen_Pro_Albums extends C_Base_Module
{
	function define($context=FALSE)
	{
		parent::define(
			'photocrati-nextgen_pro_albums',
			'NextGEN Pro Albums',
			'Provides Photocrati styled albums for NextGEN Gallery',
            '0.13',
			'http://www.nextgen-gallery.com',
			'Photocrati Media',
			'http://www.photocrati.com',
			$context
		);

		C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Album_Installer');
	}

	function get_type_list()
	{
		return array(
			'A_Nextgen_Pro_Album_Mapper' => 'adapter.nextgen_pro_album_mapper.php',
			'A_Nextgen_Pro_Album_Routes' => 'adapter.nextgen_pro_album_routes.php',
			'A_Nextgen_Pro_Album_Form' => 'adapter.nextgen_pro_album_form.php',
			'A_Nextgen_Pro_Album_Forms' => 'adapter.nextgen_pro_album_forms.php',
			'A_Nextgen_Pro_Grid_Album_Controller' => 'adapter.nextgen_pro_grid_album_controller.php',
			'A_Nextgen_Pro_Grid_Album_Dynamic_Styles' => 'adapter.nextgen_pro_grid_album_dynamic_styles.php',
			'A_Nextgen_Pro_Grid_Album_Form' => 'adapter.nextgen_pro_grid_album_form.php',
			'A_Nextgen_Pro_List_Album_Controller' => 'adapter.nextgen_pro_list_album_controller.php',
			'A_Nextgen_Pro_List_Album_Dynamic_Styles' => 'adapter.nextgen_pro_list_album_dynamic_styles.php',
			'A_Nextgen_Pro_List_Album_Form' => 'adapter.nextgen_pro_list_album_form.php',
			'A_Nextgen_Pro_List_Album_Forms' => 'adapter.nextgen_pro_list_album_forms.php',
			'Mixin_Nextgen_Pro_Album_Controller' => 'mixin.nextgen_pro_album_controller.php'
		);
	}

    function _register_hooks()
    {
        add_filter('ngg_basic_tagcloud_excluded_display_types', array($this, 'exclude_pro_albums_from_basic_tagcloud'));
    }

    function exclude_pro_albums_from_basic_tagcloud($types)
    {
        $types[] = NGG_PRO_GRID_ALBUM;
        $types[] = NGG_PRO_LIST_ALBUM;
        return $types;
    }

	function _register_adapters()
	{
		$this->get_registry()->add_adapter(
			'I_Display_Type_Mapper', 'A_NextGen_Pro_Album_Mapper'
		);

        if (!is_admin()) {
            $this->get_registry()->add_adapter(
                'I_Display_Type_Controller', 'A_NextGen_Pro_List_Album_Controller',
                NGG_PRO_LIST_ALBUM
            );

            $this->get_registry()->add_adapter(
                'I_Display_Type_Controller', 'A_NextGen_Pro_Grid_Album_Controller',
                NGG_PRO_GRID_ALBUM
            );

            $stylesheet_controller = C_Dynamic_Stylesheet_Controller::get_instance('all');
            $stylesheet_controller->register(
                'nextgen_pro_list_album',
                'photocrati-nextgen_pro_albums#nextgen_pro_list_album_dyncss'
            );
            $stylesheet_controller->register(
                'nextgen_pro_grid_album',
                'photocrati-nextgen_pro_albums#nextgen_pro_grid_album_dyncss'
            );

            $this->get_registry()->add_adapter(
                'I_Displayed_Gallery_Renderer',
                'A_NextGen_Pro_Album_Routes'
            );
        }

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            $this->get_registry()->add_adapter(
                'I_Form',
                'A_NextGen_Pro_List_Album_Form',
                NGG_PRO_LIST_ALBUM
            );
            $this->get_registry()->add_adapter(
                'I_Form',
                'A_NextGen_Pro_Grid_Album_Form',
                NGG_PRO_GRID_ALBUM
            );
            $this->get_registry()->add_adapter(
                'I_Form_Manager',
                'A_NextGen_Pro_Album_Forms'
            );
        }
    }
}

class C_NextGen_Pro_Album_Installer extends C_Gallery_Display_Installer
{
    function install()
    {
        $this->install_display_types();
    }

    function install_display_types()
    {
        $this->install_display_type(
            NGG_PRO_LIST_ALBUM, array(
                'title'					=>	__('NextGEN Pro List Album', 'nextgen-gallery-pro'),
                'entity_types'			=>	array('gallery', 'album'),
                'default_source'		=>	'albums',
                'preview_image_relpath'	=>	'photocrati-nextgen_pro_albums#list_preview.jpg',
                'hidden_from_ui'        =>  FALSE,
                'view_order' => NGG_DISPLAY_PRIORITY_BASE + (NGG_DISPLAY_PRIORITY_STEP * 10) + 200
            )
        );

        $this->install_display_type(
            NGG_PRO_GRID_ALBUM, array(
                'title'					=>	__('NextGEN Pro Grid Album', 'nextgen-gallery-pro'),
                'entity_types'			=>	array('gallery', 'album'),
                'default_source'		=>	'albums',
                'preview_image_relpath'	=>	'photocrati-nextgen_pro_albums#grid_preview.jpg',
                'hidden_from_ui'        =>  FALSE,
                'view_order' => NGG_DISPLAY_PRIORITY_BASE + (NGG_DISPLAY_PRIORITY_STEP * 10) + 210
            )
        );
    }

    function uninstall($hard=TRUE)
    {
        $mapper = C_Display_Type_Mapper::get_instance();
        foreach (array(NGG_PRO_GRID_ALBUM, NGG_PRO_LIST_ALBUM) as $display_type_name) {
            if (($display_type = $mapper->find_by_name($display_type_name))) {
                if ($hard)
                {
                    $mapper->destroy($display_type);
                }
                else {
                    $display_type->hidden_from_ui = TRUE;
                    $mapper->save($display_type);
                }

            }
        }
    }
}

new M_NextGen_Pro_Albums;
