<?php
/*
{
    Module: photocrati-nextgen_pro_lightbox_legacy,
    Depends: { photocrati-lightbox }
}
 */

define('NGG_PRO_LIGHTBOX', 'photocrati-nextgen_pro_lightbox');
define('NGG_PRO_LIGHTBOX_TRIGGER', NGG_PRO_LIGHTBOX);
define('NGG_PRO_LIGHTBOX_COMMENT_TRIGGER', 'photocrati-nextgen_pro_lightbox_comments');

/**
 * This version of the Pro Lightbox is compatible with 2.0.66 and earlier. For 2.0.67, see the
 * photocrati-nextgen_pro_lightbox module
 */
class M_NextGen_Pro_Lightbox_Legacy extends C_Base_Module
{
    var $resources = NULL;

    // See self::add_component() below to extend the pro-lightbox
    static $_components = array();

    function define($context=FALSE)
    {
        parent::define(
            'photocrati-nextgen_pro_lightbox_legacy',
            'NextGEN Pro Lightbox',
            'Provides a lightbox with integrated commenting, social sharing, and e-commerce functionality',
            '0.29',
            'http://www.nextgen-gallery.com',
            'Photocrati Media',
            'http://www.photocrati.com',
            $context
        );

        C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Lightbox_Installer');
    }

    function initialize()
    {
        parent::initialize();

        if (!is_admin())
        {
            // Add triggers
            $triggers = C_Displayed_Gallery_Trigger_Manager::get_instance();
            $triggers->add(NGG_PRO_LIGHTBOX_TRIGGER, 'C_NextGen_Pro_Lightbox_Trigger');
            $triggers->add(NGG_PRO_LIGHTBOX_COMMENT_TRIGGER, 'C_NextGen_Pro_Lightbox_Trigger');
        }

        $this->resources = new A_Nextgen_Pro_Lightbox_Resources();
    }

    function _register_adapters()
    {
        $this->get_registry()->add_adapter('I_Ajax_Controller', 'A_NextGen_Pro_Lightboxy_Legacy_Ajax');

        if (!is_admin())
        {
            // controllers & their helpers
            $this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_Nextgen_Pro_Lightbox_Resources');
            $this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_NextGen_Pro_Lightbox_Effect_Code');
        }

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            // add additional settings to each supported display type
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_BASIC_THUMBNAILS);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_BASIC_SLIDESHOW);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_BASIC_IMAGEBROWSER);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_BASIC_SINGLEPIC);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_SLIDESHOW);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_HORIZONTAL_FILMSTRIP);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_THUMBNAIL_GRID);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_BLOG_GALLERY);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_FILM);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_MASONRY);

            // lightbox settings form
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Form', NGG_PRO_LIGHTBOX . '_basic');
        }
    }

    function _register_utilities()
    {
        if (!is_admin())
        {
            $this->get_registry()->add_utility('I_NextGen_Pro_Lightbox_Controller', 'C_NextGen_Pro_Lightbox_Controller');
            $this->get_registry()->add_utility('I_OpenGraph_Controller', 'C_OpenGraph_Controller');
        }
    }

    function _register_hooks()
    {
        add_action('admin_init', array(&$this, 'register_forms'));
        add_action('wp_enqueue_scripts', array(&$this->resources, 'enqueue_pro_lightbox_resources'));
        add_action('wp_enqueue_scripts', array(&$this, 'use_legacy_resources'), PHP_INT_MAX-5);
        if (!is_admin())
            add_action('init', array(&$this, 'define_routes'));
        if (!is_admin())
            add_action('wp_enqueue_scripts', array($this, 'maybe_enqueue_fontawesome'));
    }

    function define_routes()
    {
        $app = C_Router::get_instance()->create_app('/nextgen-share');
        $app->rewrite("/{displayed_gallery_id}/{image_id}", '/displayed_gallery_id--{displayed_gallery_id}/image_id--{image_id}/named_size--thumb', FALSE, TRUE);
        $app->rewrite('/{displayed_gallery_id}/{image_id}/{named_size}', '/displayed_gallery_id--{displayed_gallery_id}/image_id--{image_id}/named_size--{named_size}');
        $app->route('/', 'I_OpenGraph_Controller#index');
    }

    /**
     * In 1.0.17, there was no legacy module. Therefore, all scripts are registered from the
     * nextgen_pro_lightbox module directory. We apply a hotfix for any registered libraries to point
     * to the correct path
     */
    function use_legacy_resources()
    {
        if (isset(M_Lightbox::$_registered_lightboxes))
        {
            global $wp_scripts;
            foreach (M_Lightbox::$_registered_lightboxes as $handle) {
                $script = $wp_scripts->registered[$handle];
                $script->src = str_replace('/nextgen_pro_lightbox/', '/nextgen_pro_lightbox_legacy/', $script->src);
            }
        }
    }

    function register_forms()
    {
        // Add forms
        $forms = C_Form_Manager::get_instance();
        $forms->add_form(NGG_LIGHTBOX_OPTIONS_SLUG, NGG_PRO_LIGHTBOX.'_basic');
    }

    static function get_components()
    {
        return self::$_components;
    }

    static function add_component($name, $handler)
    {
        self::$_components[$name] = $handler;
    }

    static function remove_component($name, $handler)
    {
        unset(self::$_components[$name]);
    }

    static function parse_entities_for_galleria($entities = array())
    {
        $retval = array();
        if (!empty($entities))
        {
            $storage = C_Gallery_Storage::get_instance();
            foreach ($entities as $entity) {
                if (isset($entity->is_gallery) && !$entity->is_gallery)
                    continue;
                if (isset($entity->is_album) && !$entity->is_album)
                    continue;
                $retval[] = array(
                    'image'       => $storage->get_image_url($entity),
                    'title'       => $entity->alttext,
                    'description' => $entity->description,
                    'image_id'    => $entity->{$entity->id_field},
                    'thumb'       => $storage->get_image_url($entity, 'thumb')
                );
            }
        }

        return apply_filters('ngg_pro_lightbox_images_queue', $retval);
    }

    function maybe_enqueue_fontawesome()
    {
        $settings = C_NextGen_Settings::get_instance();
        $context = isset($settings->thumbEffectContext) ? $settings->thumbEffectContext : '';
        if ($context != 'nextgen_images')
        {
            if (!wp_style_is('fontawesome', 'registered'))
            {
                if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'microsoft-iis') !== FALSE)
                {
                    wp_register_style('fontawesome', site_url('/?ngg_serve_fontawesome_css=1'));
                }
                else {
                    wp_register_style(
                        'fontawesome',
                        C_Router::get_instance()->get_static_url('photocrati-nextgen_gallery_display#fontawesome/font-awesome.css')
                    );
                }
            }
            else {
                wp_enqueue_style('fontawesome');
            }
        }
    }

    function get_type_list()
    {
        return array(
            'A_Pro_Lightbox_Mapper'                 => 'adapter.pro_lightbox_mapper.php',
            'A_Nextgen_Pro_Lightbox_Resources'      =>  'adapter.nextgen_pro_lightbox_resources.php',
            'A_NextGen_Pro_Lightbox_Pages'          =>  'adapter.nextgen_pro_lightbox_pages.php',
            'A_Nextgen_Pro_Lightbox_Effect_Code'    => 'adapter.nextgen_pro_lightbox_effect_code.php',
            'A_Nextgen_Pro_Lightbox_Form'           => 'adapter.nextgen_pro_lightbox_form.php',
            'A_Nextgen_Pro_Lightbox_Triggers_Form'  => 'adapter.nextgen_pro_lightbox_triggers_form.php',
            'C_NextGen_Pro_Lightbox_Trigger'        =>  'class.nextgen_pro_lightbox_trigger.php',
            'C_Nextgen_Pro_Lightbox_Controller'     => 'class.nextgen_pro_lightbox_controller.php',
            'C_Opengraph_Controller'                => 'class.opengraph_controller.php',
            'I_Nextgen_Pro_Lightbox_Controller'     => 'interface.nextgen_pro_lightbox_controller.php',
            'I_Opengraph_Controller'                => 'interface.opengraph_controller.php',
            'A_NextGen_Pro_Lightboxy_Legacy_Ajax'   => 'adapter.nextgen_pro_lightbox_ajax.php'
        );
    }
}

if (!class_exists('C_NextGen_Pro_Lightbox_Installer')) {
class C_NextGen_Pro_Lightbox_Installer
{
    function get_registry()
    {
        return C_Component_Registry::get_instance();
    }

    function set_attr(&$obj, $key, $val)
    {
        if (!isset($obj->$key))
            $obj->$key = $val;
    }

    function install_pro_lightbox()
    {
        // Install or update the lightbox library
        $mapper = $this->get_registry()->get_utility('I_Lightbox_Library_Mapper');
        $lightbox = $mapper->find_by_name(NGG_PRO_LIGHTBOX);
        if (!$lightbox)
            $lightbox = new stdClass;

        // Set properties
        $lightbox->name	= NGG_PRO_LIGHTBOX;
        $this->set_attr($lightbox, 'title', __("NextGEN Pro Lightbox", 'nextgen-gallery-pro'));
        $this->set_attr($lightbox, 'code', "class='nextgen_pro_lightbox' data-nplmodal-gallery-id='%PRO_LIGHTBOX_GALLERY_ID%'");
        $this->set_attr(
            $lightbox,
            'css_stylesheets',
            implode("\n", array(
                'photocrati-nextgen_pro_lightbox_legacy#style.css'
            ))
        );
        $this->set_attr(
            $lightbox,
            'styles',
            implode("\n", array(
                'photocrati-nextgen_pro_lightbox_legacy#style.css'
            ))
        );
        $this->set_attr(
            $lightbox,
            'scripts',
            implode("\n", array(
                'photocrati-nextgen_pro_lightbox_legacy#nextgen_pro_lightbox.js'
            ))
        );
        $this->set_attr(
            $lightbox,
            'display_settings',
            array(
                'icon_color' => '',
                'icon_background' => '',
                'icon_background_enabled' => '0',
                'icon_background_rounded' => '1',
                'overlay_icon_color' => '',
                'sidebar_button_color' => '',
                'sidebar_button_background' => '',
                'carousel_text_color' => '',
                'background_color' => '',
                'carousel_background_color' => '',
                'sidebar_background_color' => '',
                'router_slug' => 'gallery',
                'transition_effect' => 'slide',
                'enable_routing' => '1',
                'enable_comments' => '1',
                'enable_sharing' => '1',
                'display_comments' => '0',
                'display_captions' => '0',
                'display_carousel' => '1',
                'localize_limit' => '100',
                'transition_speed' => '0.4',
                'slideshow_speed' => '5',
                'style' => '',
                'touch_transition_effect' => 'slide',
                'image_pan' => '0',
                'interaction_pause' => '1',
                'image_crop' => 'false'
            )
        );

        $mapper->save($lightbox);
    }

    function install($reset=FALSE)
    {
        $this->install_pro_lightbox();
    }

    function uninstall_nextgen_pro_lightbox($hard = FALSE)
    {
        $mapper = $this->get_registry()->get_utility('I_Lightbox_Library_Mapper');
        if (($lightbox = $mapper->find_by_name(NGG_PRO_LIGHTBOX)))
            $mapper->destroy($lightbox);
    }
}}

new M_NextGen_Pro_Lightbox_Legacy;
