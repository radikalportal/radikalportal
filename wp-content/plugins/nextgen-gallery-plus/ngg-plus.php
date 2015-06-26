<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/*
 * Plugin Name: NextGEN Plus by Photocrati
 * Description: A premium add-on for NextGEN Gallery with beautiful new gallery displays and a fullscreen, responsive Pro Lightbox with social sharing and commenting.
 * Version: 1.3.0
 * Plugin URI: http://www.nextgen-gallery.com
 * Author: Photocrati Media
 * Author URI: http://www.photocrati.com
 * License: GPLv2
 */

// in case bcmath isn't enabled we provide these wrappers
if (!function_exists('bcadd')) { function bcadd($one, $two, $scale) { return $one + $two; }}
if (!function_exists('bcmul')) { function bcmul($one, $two, $scale) { return $one * $two; }}
if (!function_exists('bcdiv')) { function bcdiv($one, $two, $scale) { return $one / $two; }}
if (!function_exists('bcsub')) { function bcsub($one, $two, $scale) { return $one - $two; }}
if (!function_exists('bcmod')) { function bcmod($one, $two)         { return $one % $two; }}

class NextGen_Plus
{
    static $minimum_ngg_version = '2.0.63';
    static $product_loaded = FALSE;

    // Initialize the plugin
    function __construct()
    {
        // We only load the plugin if we're outside of the activation request, loaded in an iframe
        // by WordPress. Reason being, if WP_DEBUG is enabled, and another Pope-based plugin (such as
        // the photocrati theme or NextGEN Pro/Plus), then PHP will output strict warnings
        if ($this->is_not_activating()) {
            define('NGG_PLUS_PLUGIN_BASENAME', plugin_basename(__FILE__));
            define('NGG_PLUS_MODULE_URL', plugins_url(path_join(basename(dirname(__FILE__)), 'modules')));
            define('NGG_PLUS_PLUGIN_VERSION', '1.3.0');

            if (class_exists('C_Component_Registry') && did_action('load_nextgen_gallery_modules')) {
                add_action('init', array(get_class(), 'load_product'));
            }
            else {
                add_action('load_nextgen_gallery_modules', array(get_class(), 'load_product'));
            }
        }

        $this->_register_hooks();
    }

    /**
     * Loads the product providing NextGEN Gallery Pro functionality
     * @param C_Component_Registry $registry
     */
    function load_product($registry = NULL)
    {
        $retval = FALSE;

        if (!self::$product_loaded) {
            // version mismatch: do not load
            if (!defined('NGG_PLUGIN_VERSION') || version_compare(NGG_PLUGIN_VERSION, self::$minimum_ngg_version) == -1)
                return;

            // Don't load Pro if Plus was recently activated
            if (defined('NGG_PRO_PLUGIN_VERSION') OR get_option('photocrati_pro_recently_activated', FALSE)) {
                return;
            }

            // Get the component registry if one wasn't provided
            if (!$registry) $registry = C_Component_Registry::get_instance();

            // Tell the registry where it can find our products/modules
            $dir = dirname(__FILE__);
            $registry->add_module_path($dir, TRUE, TRUE);
            $registry->initialize_all_modules();

            $retval = self::$product_loaded = TRUE;

            if (is_admin())
                $registry->del_adapter('I_Page_Manager', 'A_NextGen_Pro_Upgrade_Page');
        }
        else {
            $retval = self::$product_loaded;
        }

        return $retval;
    }

    function is_activating()
    {
        $retval =  strpos($_SERVER['REQUEST_URI'], 'plugins.php') !== FALSE && isset($_REQUEST['action']) && $_REQUEST['action'] == 'activate';
        if (!$retval && strpos($_SERVER['REQUEST_URI'], 'update.php') !== FALSE && isset($_REQUEST['action']) && $_REQUEST['action'] == 'install-plugin' && isset($_REQUEST['plugin']) && strpos($_REQUEST['plugin'], 'nextgen-gallery-plus') === 0) {
            $retval = TRUE;
        }
        if (!$retval && strpos($_SERVER['REQUEST_URI'], 'update.php') !== FALSE && isset($_REQUEST['action']) && $_REQUEST['action'] == 'activate-plugin' && isset($_REQUEST['plugin']) && strpos($_REQUEST['plugin'], 'nextgen-gallery-plus') === 0) {
            $retval = TRUE;
        }
        if (!$retval && strpos($_SERVER['REQUEST_URI'], 'plugins.php') !== FALSE && isset($_REQUEST['action']) && $_REQUEST['action'] == 'activate-selected' && isset($_REQUEST['checked']) && is_array($_REQUEST['checked']) && in_array('nextgen-gallery-pro/nggallery-plus.php', $_REQUEST['checked'])) {
            $retval = TRUE;
        }

        return $retval;
    }

    function is_not_activating()
    {
        return !$this->is_activating();
    }

    function _register_hooks()
    {
        add_action('activate_' . plugin_basename(__FILE__), array(get_class(), 'activate'));
        add_action('deactivate_' . plugin_basename(__FILE__), array(get_class(), 'deactivate'));
        add_action('plugins_loaded', array($this, 'remove_ngg_third_party_compat_hook'));

        // hooks for showing available updates
        add_action('after_plugin_row_' . plugin_basename(__FILE__), array(get_class(), 'after_plugin_row'));
        add_action('admin_notices', array(&$this, 'admin_notices'));
        add_action('admin_init', array(&$this, 'deactivate_pro'));
        add_action('in_admin_header', array(&$this, 'hide_ngg_pro_ecommerce'), PHP_INT_MAX - 1);
    }

    /**
     * This is ugly, but needed with 2.0.79
     * TODO: Remove this!
     */
    function remove_ngg_third_party_compat_hook()
    {
        if (class_exists('M_Third_Party_Compat')) {
            $filter_found = FALSE;
            global $wp_filter;
            if (isset($wp_filter['ngg_non_minified_modules'])) {
                foreach ($wp_filter['ngg_non_minified_modules'] as $priority => $hooks) {
                    foreach ($hooks as $key => $params) {
                        $params = $params['function'];
                        if (is_object($params[0]) && get_class($params[0]) == 'M_Third_Party_Compat') {
                            remove_filter('ngg_non_minified_modules', array($params[0], $params[1]), $priority);
                            $filter_found = TRUE;
                        }
                        if ($filter_found) break;
                    }
                    if ($filter_found) break;
                }
            }

            // Exclude modules
            $registry = C_Component_Registry::get_instance();
            if ($registry->is_module_loaded('photocrati-nextgen-plus')) {
                add_filter('ngg_non_minified_modules', array(&$this, 'do_not_minify'));
            }
        }
    }

    function do_not_minify($modules)
    {
        $modules += P_Photocrati_NextGen_Plus::$modules;

        return $modules;
    }

    function deactivate_pro()
    {
        if (get_option('photocrati_plus_recently_activated', FALSE) && defined('NGG_PRO_PLUGIN_BASENAME')) {
            deactivate_plugins(NGG_PRO_PLUGIN_BASENAME);
        }
    }

    function hide_ngg_pro_ecommerce()
    {
        if (get_option('photocrati_plus_recently_activated') && strpos($_SERVER['REQUEST_URI'], 'plugins.php') !== FALSE) {
            delete_option('photocrati_plus_recently_activated');
            echo '<script id="hide_ngg_pro_ecommerce" type="text/javascript">jQuery(\'#toplevel_page_ngg_ecommerce_options\').remove();</script>';
        }
    }

    static function activate()
    {
        // admin_notices will check for this later
        update_option('photocrati_plus_recently_activated', 'true');
    }

    static function deactivate()
    {
        if (class_exists('C_Photocrati_Installer')) {
            C_Photocrati_Installer::uninstall('photocrati-nextgen-plus');
        }
    }

    static function _get_update_admin()
    {
        if (class_exists('C_Component_Registry') && method_exists('C_Component_Registry', 'get_instance')) {
            $registry = C_Component_Registry::get_instance();
            $update_admin = $registry->get_module('photocrati-auto_update-admin');

            return $update_admin;
        }

        return null;
    }

    static function _get_update_message()
    {
        $update_admin = self::_get_update_admin();

        if ($update_admin != NULL && method_exists($update_admin, 'get_update_page_url')) {
            $url = $update_admin->get_update_page_url();

            return sprintf(__('There are updates available. You can <a href="%s">Update Now</a>.', 'nextgen-gallery-pro'), $url);
        }

        return null;
    }

    static function has_updates()
    {
        $update_admin = self::_get_update_admin();

        if ($update_admin != NULL && method_exists($update_admin, '_get_update_list')) {
            $list = $update_admin->_get_update_list();

            if ($list != NULL) {
                $ngg_pro_count = 0;

                foreach ($list as $update) {
                    if (isset($update['info']['product-id']) && $update['info']['product-id'] == 'photocrati-nextgen-plus') {
                        $ngg_pro_count++;
                    }
                }

                if ($ngg_pro_count > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    static function after_plugin_row()
    {
        if (self::has_updates()) {
            $update_message = self::_get_update_message();

            if ($update_message != NULL) {
                echo '<tr style=""><td colspan="5" style="padding: 6px 8px; ">' . $update_message . '</td></tr>';
            }
        }
    }

    function admin_notices()
    {
        $nextgen_found = FALSE;
        if (defined('NGG_PLUGIN_VERSION'))
            $nextgen_found = 'NGG_PLUGIN_VERSION';
        if (defined('NEXTGEN_GALLERY_PLUGIN_VERSION'))
            $nextgen_found = 'NEXTGEN_GALLERY_PLUGIN_VERSION';
        $nextgen_version = @constant($nextgen_found);

        if (FALSE == $nextgen_found)
        {
            $message = __('Please install &amp; activate <a href="http://wordpress.org/plugins/nextgen-gallery/" target="_blank">NextGEN Gallery</a> to allow NextGEN Pro to work.', 'nextgen-gallery-pro');
            echo '<div class="updated"><p>' . $message . '</p></div>';
        }
        else if (version_compare($nextgen_version, self::$minimum_ngg_version) == -1) {
            $ngg_pro_version = NGG_PLUS_PLUGIN_VERSION;
            $upgrade_url     = admin_url('/plugin-install.php?tab=plugin-information&plugin=nextgen-gallery&section=changelog&TB_iframe=true&width=640&height=250');
            $message = sprintf(
                __("NextGEN Gallery %s is incompatible with NextGEN Pro %s. Please update <a class='thickbox' href='%s'>NextGEN Gallery</a> to version %s or higher. NextGEN Pro has been deactivated.", 'nextgen-gallery-pro'),
                $nextgen_version,
                $ngg_pro_version,
                $upgrade_url,
                self::$minimum_ngg_version
            );
            echo '<div class="updated"><p>' . $message . '</p></div>';
            deactivate_plugins(NGG_PLUS_PLUGIN_BASENAME);
        }
        elseif (delete_option('photocrati_plus_recently_activated')) {
            $message = __('To activate the NextGEN Pro Lightbox please go to Gallery > Other Options > Lightbox Effects.', 'nextgen-gallery-pro');
            echo '<div class="updated"><p>' . $message . '</p></div>';
        }

        if (class_exists('C_Page_Manager'))
        {
            $pages = C_Page_Manager::get_instance();

            if (isset($_REQUEST['page']))
            {
                if (in_array($_REQUEST['page'], array_keys($pages->get_all()))
                ||  preg_match("/^nggallery-/", $_REQUEST['page'])
                ||  $_REQUEST['page'] == 'nextgen-gallery')
                {
                    if (self::has_updates())
                    {
                        $update_message = self::_get_update_message();
                        echo '<div class="updated"><p>' . $update_message . '</p></div>';
                    }
                }
            }
        }
    }
}

new NextGen_Plus();
