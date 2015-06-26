<?php
/*
 {
    Module: photocrati-nextgen_pro_blog_gallery
 }
 */
define('NGG_PRO_BLOG_GALLERY', 'photocrati-nextgen_pro_blog_gallery');
class M_NextGen_Pro_Blog_Gallery extends C_Base_Module
{
    function define($context=FALSE)
    {
        parent::define(
            NGG_PRO_BLOG_GALLERY,
            'NextGEN Pro Blog Gallery',
            "Provides Photocrati's Blog Style gallery type for NextGEN Gallery",
            '0.15',
            'http://www.nextgen-gallery.com',
            'Photocrati Media',
            'http://www.photocrati.com',
            $context
        );

        C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Blog_Installer');
    }

    function get_type_list()
    {
        return array(
            'A_Nextgen_Pro_Blog_Controller'     => 'adapter.nextgen_pro_blog_controller.php',
            'A_Nextgen_Pro_Blog_Dynamic_Styles' => 'adapter.nextgen_pro_blog_dynamic_styles.php',
            'A_Nextgen_Pro_Blog_Form'           => 'adapter.nextgen_pro_blog_form.php',
            'A_Nextgen_Pro_Blog_Forms'          => 'adapter.nextgen_pro_blog_forms.php',
            'A_Nextgen_Pro_Blog_Mapper'         => 'adapter.nextgen_pro_blog_mapper.php',
        );
    }

    function _register_adapters()
    {
        $this->get_registry()->add_adapter('I_Display_Type_Mapper', 'A_NextGen_Pro_Blog_Mapper');

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Blog_Form', $this->module_id);
            $this->get_registry()->add_adapter('I_Form_Manager', 'A_NextGen_Pro_Blog_Forms');
        }

        if (!is_admin())
        {
            C_Dynamic_Stylesheet_Controller::get_instance('all')
                ->register('nextgen_pro_blog', 'photocrati-nextgen_pro_blog_gallery#nextgen_pro_blog_dyncss');

            $this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_NextGen_Pro_Blog_Controller', $this->module_id);
        }
    }
}

class C_NextGen_Pro_Blog_Installer extends C_Gallery_Display_Installer
{
    function install()
    {
        $this->install_display_types();
    }

    function install_display_types()
    {
        $this->install_display_type(
            NGG_PRO_BLOG_GALLERY, array(
                'title'                         =>  __('NextGEN Pro Blog Style', 'nextgen-gallery-pro'),
                'entity_types'                  =>  array('image'),
                'default_source'                =>  'galleries',
                'preview_image_relpath'         =>  'photocrati-nextgen_pro_blog_gallery#preview.jpg',
                'hidden_from_ui'                =>  FALSE,
                'view_order' => NGG_DISPLAY_PRIORITY_BASE + (NGG_DISPLAY_PRIORITY_STEP * 10) + 40
            )
        );
    }

    function uninstall($hard)
    {
        $mapper = C_Display_Type_Mapper::get_instance();
        if (($entity = $mapper->find_by_name(NGG_PRO_BLOG_GALLERY))) {
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

new M_NextGen_Pro_Blog_Gallery;
