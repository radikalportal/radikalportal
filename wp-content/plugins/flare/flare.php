<?php
/*
Plugin Name: Flare
Plugin URI: http://www.dtelepathy.com/
Description: Flare is a simple yet eye-catching social sharing bar that gets you followed and lets your content get shared via posts, pages, and media types.
Version: 1.2.7
Author: dtelepathy
Author URI: http://www.dtelepathy.com/
Contributors: kynatro, dtelepathy, moonspired, nielsfogt, dtlabs
License: GPL3

Copyright 2012 digital-telepathy  (email : support@digital-telepathy.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

Flare::constants();

class Flare {
    static $namespace = "flare";
    static $label = "Flare";
    
    // Default plugin options
    var $defaults = array(
        'iconstyle' => "round",
        'backgroundcolor' => "light",
        'post_types' => array( 'post' ),
        'positions' => array( 'top', 'left' ),
        'follow_iconstyle' => "round",
        'enablecounters' => true,
        'enabletotal' => true,
        'enablehumbleflare' => false,
        'humbleflarecount' => 5,
        'closablevertical' => true,
        'filamenticon' => true
    );
    
    var $iconstyles = array(
        'round-bevel' => "Round (Beveled)",
        'round-flat' => "Round (Flat)",
        'round-flat-nostroke' => "Round (Flat, No Stroke)",
        'rounded-square-bevel' => "Rounded Square (Beveled)",
        'rounded-square-flat' => "Rounded Square (Flat)",
        'rounded-square-flat-nostroke' => "Rounded Square (Flat, No Stroke)",
        'square-bevel' => "Square (Beveled)",
        'square-flat' => "Square (Flat)",
        'square-flat-nostroke' => "Square (Flat, No Stroke)"
    );
    
    var $available_positions = array(
        'top' => "At top of the post",
        'top-left' => "At top of the post (left aligned)",
        'bottom' => "At bottom of the post",
        'bottom-left' => "At bottom of the post (left aligned)",
        'left' => "Floating left of the post",
        'right' => "Floating right of the post"
    );
    
    // Menu item hook
    var $menu_hooks = array();
    
    // Has the top been output yet?
    var $top_output = false;
    // Has the bottom been output yet?
    var $bottom_output = false;
    
    /**
     * Instantiation construction
     * 
     * @uses add_action()
     * @uses Flare::route()
     * @uses Flare::wp_register_scripts()
     * @uses Flare::wp_register_styles()
     */
    function __construct() {
        // Make namespace available as an instance variable
        $this->namespace = self::$namespace;
        // Make friendly name available as an instance variable
        $this->label = self::$label;
        // Name of the option_value to store plugin options in
        $this->option_name = '_' . $this->namespace . '--options';

        /**
         * Make this plugin available for translation.
         * Translations can be added to the /languages/ directory.
         */
        load_theme_textdomain( $this->namespace, FLARE_DIRNAME . '/languages' );
        
        $lib_files = glob( FLARE_DIRNAME . '/lib/*.php' );
        foreach( $lib_files as $lib_file ) {
            include_once( $lib_file );
        }
        
        $widget_files = glob( FLARE_DIRNAME . '/widgets/*.php' );
        foreach( $widget_files as $widget_file ) {
            include_once( $widget_file );
        }
        
        // Admin head
        add_action( 'admin_head', array( &$this, 'admin_head' ) );
        // Admin menu addition
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
        // Register JavaScript files
        add_action( 'init', array( &$this, 'wp_register_scripts' ) );
        // Register Stylesheets
        add_action( 'init', array( &$this, 'wp_register_styles' ) );
        // Custom routing
        add_action( 'init', array( &$this, 'route' ) );
        // Output horizontal flare bars
        add_filter( 'the_content', array( &$this, 'the_content' ) );
        // Add a settings link next to the "Deactivate" link on the plugin listing page
        add_filter( 'plugin_action_links', array( &$this, 'plugin_action_links' ), 10, 2 );
        // Output vertical flare bar
        add_action( 'wp_footer', array( &$this, 'wp_footer' ) );
        // Load Flare widget
        add_action( 'widgets_init', array( 'FlareFollowWidget', 'initialize' ) );
        // Load JavaScript assets
        add_action( 'wp_print_scripts', array( &$this, 'wp_print_scripts' ) );
        // Load Stylesheet assets
        add_action( 'wp_print_styles', array( &$this, 'wp_print_styles' ) );
        // Add button AJAX
        add_action( "wp_ajax_{$this->namespace}_add_button", array( &$this, 'ajax_add_button' ) );
        // Add button AJAX
        add_action( "wp_ajax_{$this->namespace}_add_follow_button", array( &$this, 'ajax_add_follow_button' ) );
        // Delete button AJAX
        add_action( "wp_ajax_{$this->namespace}_delete_button", array( &$this, 'ajax_delete_button' ) );
        // Get Flare score counts AJAX
        add_action( "wp_ajax_{$this->namespace}_get_counts", array( &$this, 'ajax_get_counts' ) );
        add_action( "wp_ajax_nopriv_{$this->namespace}_get_counts", array( &$this, 'ajax_get_counts' ) );
        // Get digital-telepathy blog
        add_action( "wp_ajax_{$this->namespace}_blog_feed", array( &$this, 'ajax_blog_feed' ) );
    }
    
    function __get( $name ) {
        if( $name == "Button" ) {
            include_once( FLARE_DIRNAME . '/models/button.php' );
            $this->Button = new Flare_Model();
            
            return $this->Button;
        }
        
        if( $name == "Follow" ) {
            include_once( FLARE_DIRNAME . '/models/follow.php' );
            $this->Follow = new Flare_Follow_Model();
            
            return $this->Follow;
        }
    }
    
    /**
     * Process follow admin page form submissions
     * 
     * @uses Flare::sanitize()
     * @uses wp_redirect()
     * @uses wp_verify_nonce()
     */
    private function _admin_follow_update() {
        // Verify submission for processing using wp_nonce
        if( wp_verify_nonce( $_REQUEST[$this->namespace . '_update_follow_wpnonce'], $this->namespace . '_follow_options' ) ) {
                
            $buttons = isset( $_POST['button'] ) ? $_POST['button'] : array();
            
            $this->Follow->save( $buttons );
            
            $follow_iconstyle = reset( array_keys( $this->iconstyles ) );
            if( in_array( $_POST['data']['follow_iconstyle'], array_keys( $this->iconstyles ) ) ) {
                $follow_iconstyle = $_POST['data']['follow_iconstyle'];
            }
            
            update_option( "_{$this->namespace}_follow_iconstyle", $follow_iconstyle );
            
            // Redirect back to the options page with the message flag to show the saved message
            $redirect_url = strpos( $_POST['_wp_http_referer'], "message=1" ) === false ? $_POST['_wp_http_referer'] . "&message=1" : $_POST['_wp_http_referer'];
            wp_redirect( $redirect_url );
            exit;
        }
    }
    
    /**
     * Process share admin page form submissions
     * 
     * @uses Flare::sanitize()
     * @uses wp_redirect()
     * @uses wp_verify_nonce()
     */
    private function _admin_share_update() {
        // Verify submission for processing using wp_nonce
        if( wp_verify_nonce( $_REQUEST[$this->namespace . '_update_share_wpnonce'], $this->namespace . '_share_options' ) ) {
            $buttons = $_POST['button'];
            $data = $this->_sanitize( $_POST['data'] );
            
            $data['enablecounters'] = (bool) isset( $data['enablecounters'] );
            $data['enabletotal'] = (bool) isset( $data['enabletotal'] );
            $data['enablehumbleflare'] = (bool) isset( $data['enablehumbleflare'] );
            $data['closablevertical'] = (bool) isset( $data['closablevertical'] );
            $data['filamenticon'] = (bool) isset( $data['filamenticon'] );
            
            $menu_order = 1;
            foreach( $buttons as $button ) {
                $this->Button->save( $button, $menu_order++ );
            }
            
            if( isset( $data['delete'] ) ) {
                $delete_ids = (array) $data['delete'];
                $delete_ids = array_unique( $delete_ids );
                
                foreach( $delete_ids as $delete_id ) {
                    wp_delete_post( $delete_id, true );
                }
            }
            
            for( $i = ( count( $data['positions'] ) - 1 ); $i >= 0; $i-- ) {
                if( empty( $data['positions'][$i] ) ) {
                    unset( $data['positions'][$i] );
                }
            }

            // Make accommodations for no post types selected
            if( !isset( $data['post_types'] ) ) {
                $data['post_types'] = array();
            }
            
            update_option( $this->option_name, $data );
            
            // Redirect back to the options page with the message flag to show the saved message
            $redirect_url = strpos( $_POST['_wp_http_referer'], "message=1" ) === false ? $_POST['_wp_http_referer'] . "&message=1" : $_POST['_wp_http_referer'];
            wp_redirect( $redirect_url );
            exit;
        }
    }
    
    /**
     * Retrieve the stored plugin option or the default if no user specified value is defined
     * 
     * @param string $option_name The name of the TrialAccount option you wish to retrieve
     * 
     * @uses get_option()
     * 
     * @return mixed Returns the option value or false(boolean) if the option is not found
     */
    private function _get_option( $option_name ) {
        // Load option values if they haven't been loaded already
        if( !isset( $this->options ) || empty( $this->options ) ) {
            $this->options = get_option( $this->option_name, $this->defaults );
        }
        
        if( isset( $this->options[$option_name] ) ) {
            return $this->options[$option_name];    // Return user's specified option value
        } elseif( isset( $this->defaults[$option_name] ) ) {
            return $this->defaults[$option_name];   // Return default option value
        }
        return false;
    }
    
    /**
     * Should Flare be displayed on this page?
     * 
     * Checks the $wp_query->post and other properties of the page being viewed to determine
     * if Flare should be loaded and included here.
     * 
     * @global $post
     * @global $wp_query
     * 
     * @return boolean
     */
    private function _include_flare() {
        global $post, $wp_query;
        
        $valid = array();
        
        // Is the $post value the one that was requested for this page?
        $valid['requested'] = ( $post == $wp_query->post );
        
        // Is this a post type Flare is configured to exist on?
        $valid['post_type'] = in_array( $post->post_type, $this->_get_option( 'post_types' ) );
        
        // Only include Flare if this is for a single page view
        $valid['single'] = ( count( $wp_query->posts ) == 1 );
        
        // Make sure it isn't an invalid WordPress type page (pages with lists or invalid types like author pages) 
        $valid['request_type'] = ( !is_archive() && !is_home() && !is_search() && !is_404() );
        
        // If all passed the unique array length should be one and it should be boolean(true)
        $is_valid = ( count( array_unique( $valid ) ) == 1 ) && ( reset( array_unique( $valid ) ) == true );
        
        return $is_valid;
    }
    
    /**
     * Sanitize data
     * 
     * @param mixed $str The data to be sanitized
     * 
     * @uses wp_kses()
     * 
     * @return mixed The sanitized version of the data
     */
    private function _sanitize( $str ) {
        if ( !function_exists( 'wp_kses' ) ) {
            require_once( ABSPATH . 'wp-includes/kses.php' );
        }
        global $allowedposttags;
        global $allowedprotocols;
        
        if ( is_string( $str ) ) {
            $str = wp_kses( $str, $allowedposttags, $allowedprotocols );
        } elseif( is_array( $str ) ) {
            $arr = array();
            foreach( (array) $str as $key => $val ) {
                $arr[$key] = $this->_sanitize( $val );
            }
            $str = $arr;
        }
        
        return $str;
    }
    
    /**
     * Hook into register_activation_hook action
     *
     * Put code here that needs to happen when your plugin is first activated
     * (database creation, permalink additions, etc.)
     */
    static function activate() {
        global $wpdb;
        
        $installed_version = get_option( "ssb_version", "1.0.0" );
        
        // Create a default Twitter/Facebook entry if the user has no buttons yet (first installation)
        $existing_buttons = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = %s AND post_status = 'publish';", self::$namespace ) );
        if( empty( $existing_buttons ) ) {
            if( !class_exists( 'Flare_Model' ) ) {
                include_once( FLARE_DIRNAME . '/models/button.php' );
                $button_model = new Flare_Model();
            }
            
            $menu_order = 1;
            foreach( array( 'twitter', 'facebook' ) as $button_type ) {
                $button = $button_model->get_scaffold( $button_type );
                $button_model->save( $button, $menu_order++ );
            }
        }
        
        if( version_compare( $installed_version, FLARE_VERSION, "<" ) ) {
            
        }
        
        update_option( "ssb_version", FLARE_VERSION );
    }

    /**
     * Hook into admin_head action
     * 
     * Output variables for use by JavaScript for this Class
     */
    function admin_head() {
        echo '<script type="text/javascript">var FlareInterfaces = {};</script>';
    }
    
    /**
     * Define the admin menu options for this plugin
     * 
     * @uses add_action()
     * @uses add_options_page()
     */
    function admin_menu() {
        add_menu_page( $this->label, $this->label, 'administrator', FLARE_BASENAME, array( &$this, 'admin_options_share' ), FLARE_URLPATH . '/images/icon.png' );
        $this->menu_hooks['share'] = array(
            'label' => __( "Share", $this->namespace ),
            'path' => FLARE_BASENAME,
            'hook' => add_submenu_page( FLARE_BASENAME, __( $this->label . " > Share", $this->namespace ), __( "Share", $this->namespace ), 'administrator', FLARE_BASENAME, array( &$this, 'admin_options_share' ) )
        );
        $this->menu_hooks['follow'] = array(
            'label' => __( "Follow", $this->namespace ),
            'path' => FLARE_BASENAME . '/follow',
            'hook' => add_submenu_page( FLARE_BASENAME, __( $this->label . " > Follow", $this->namespace ), __( "Follow", $this->namespace ), 'administrator', FLARE_BASENAME . "/follow", array( &$this, 'admin_options_follow' ) )
        );
        $this->menu_hooks['metrics'] = array(
            'label' => __( "Metrics", $this->namespace ),
            'path' => FLARE_BASENAME . '/metrics',
            'hook' => add_submenu_page( FLARE_BASENAME, __( $this->label . " > Metrics", $this->namespace ), __( "Metrics", $this->namespace ), 'administrator', FLARE_BASENAME . "/metrics", array( &$this, 'admin_options_metrics' ) )
        );
        
        // Link to dt.Labs Products
        add_submenu_page( FLARE_BASENAME, 'Filament', 'Get Flare Pro', 'update_plugins', FLARE_BASENAME . '/filament', array( &$this, 'admin_options_page' ) );
        
        // Add print scripts and styles action based off the option page hook
        foreach( $this->menu_hooks as &$menu_hook ) {
            add_action( 'admin_print_scripts-' . $menu_hook['hook'], array( &$this, 'admin_print_scripts' ) );
            add_action( 'admin_print_styles-' . $menu_hook['hook'], array( &$this, 'admin_print_styles' ) );
        }
    }
    
    /**
     * The admin Follow page rendering method
     * 
     * @uses current_user_can()
     * @uses wp_die()
     */
    function admin_options_follow() {
        if( !current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to access this page' );
        }
        
        $available_buttons = $this->Follow->available_buttons;
        $label = $this->label;
        $namespace = $this->namespace;
        $friendly_name = $this->label;
        $saved_buttons = $this->Follow->get();
        $url_path = FLARE_URLPATH;
        $iconstyles = $this->iconstyles;
        
        $follow_iconstyle = get_option( "_{$this->namespace}_follow_iconstyle", 'round-bevel' );
        
        $buttons = array();
        foreach( $saved_buttons as $button_key => $saved_button ) {
            $buttons[$button_key] = $saved_button;
        }
        
        include( FLARE_DIRNAME . "/views/follow.php" );
    }
    
    function admin_options_metrics() {
        $namespace = $this->namespace;
        $friendly_name = $this->label;
        $url_path = FLARE_URLPATH;
        
        include( FLARE_DIRNAME . "/views/metrics.php" );
    }

    /**
     * The admin Share page rendering method
     * 
     * @uses current_user_can()
     * @uses Flare_Model::get()
     * @uses get_option()
     * @uses get_post_types()
     * @uses wp_die()
     */
    function admin_options_share() {
        if( !current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to access this page' );
        }
        
        $available_buttons = $this->Button->available_buttons;
        $label = $this->label;
        $namespace = $this->namespace;
        $friendly_name = $this->label;
        $saved_buttons = $this->Button->get();
        $url_path = FLARE_URLPATH;
        $iconstyles = $this->iconstyles;
        $available_positions = $this->available_positions;
        $available_positions_grouped = array(
            'top' => array(
                'top' => $available_positions['top'],
                'top-left' => $available_positions['top-left']
            ),
            'bottom' => array(
                'bottom' => $available_positions['bottom'],
                'bottom-left' => $available_positions['bottom-left']
            ),
            'left-right' => array(
                'left' => $available_positions['left'],
                'right' => $available_positions['right']
            )
        );
        $backgroundcolors = array(
            'none' => "None",
            'light' => "Light",
            'dark' => "Dark"
        );
        
        $data = get_option( $this->option_name, $this->defaults );
        foreach( $this->defaults as $key => $val ) {
            if( !isset( $data[$key] ) )
                $data[$key] = $val;
        }
        
        $post_types = get_post_types( array(
            'public' => true
        ), 'objects' );
        
        $buttons = array();
        foreach( $saved_buttons as $button_key => $saved_button ) {
            $buttons[$button_key] = $saved_button;
        }
        
        include( FLARE_DIRNAME . "/views/share.php" );
    }

    /**
     * Load JavaScript for the admin options page
     * 
     * @uses wp_enqueue_script()
     */
    function admin_print_scripts() {
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script( "{$this->namespace}-admin" );
        wp_enqueue_script( "{$this->namespace}-jquery-minicolors" );
        wp_enqueue_script( "{$this->namespace}-fancyform" );
    }
    
    /**
     * Load Stylesheet for the admin options page
     * 
     * @uses wp_enqueue_style()
     */
    function admin_print_styles() {
        wp_enqueue_style( "{$this->namespace}" );
        wp_enqueue_style( "{$this->namespace}-admin" );
        wp_enqueue_style( "{$this->namespace}-jquery-minicolors" );
        wp_enqueue_style( "{$this->namespace}-fancyform" );
    }
    
    /**
     * Add a button to the view AJAX response
     * 
     * @uses wp_nonce_verify()
     */
    function ajax_add_button() {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-add-button" ) ) {
            die( "false" );
        }
        
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        
        $namespace = $this->namespace;
        
        // The button type slug
        $button_type = $_REQUEST['type'];
        
        // All available button models
        $available_buttons = $this->Button->available_buttons;
        
        $button = $this->Button->get_scaffold( $button_type );
        
        include( FLARE_DIRNAME . '/views/admin/_button.php' );
        exit;
    }
    
    /**
     * Add a button to the view AJAX response
     * 
     * @uses wp_nonce_verify()
     */
    function ajax_add_follow_button() {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-add-follow-button" ) ) {
            die( "false" );
        }
        
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        
        $namespace = $this->namespace;
        
        // The button type slug
        $button_type = $_REQUEST['type'];
        
        // All available button models
        $available_buttons = $this->Follow->available_buttons;
        $button_model = $this->Follow->available_buttons[$button_type];
        
        $button_params = array( 'color' => $button_model['color'] );
        foreach( $button_model['options'] as $option_name => $option_params ) {
            $button_params[$option_name] = $option_params['value'];
        }
        
        include( FLARE_DIRNAME . '/views/admin/_follow_button.php' );
        exit;
    }

    /**
     * Outputs an <ul> for the SlideDeck Blog on the "Overview" page
     *
     * @uses fetch_feed()
     * @uses wp_redirect()
     * @uses SlideDeckPlugin::action()
     * @uses is_wp_error()
     * @uses SimplePie::get_item_quantity()
     * @uses SimplePie::get_items()
     */
    function ajax_blog_feed( ) {
        if( !FLARE_IS_AJAX_REQUEST ) {
            wp_redirect( $this->action( ) );
            exit ;
        }

        $rss = fetch_feed( array( 'http://feeds.feedburner.com/Slidedeck', 'http://feeds.feedburner.com/digital-telepathy' ) );
        
        // Checks that the object is created correctly
        if( !is_wp_error( $rss ) ) {
            // Figure out how many total items there are, but limit it to 5.
            $maxitems = $rss->get_item_quantity( 5 );

            // Build an array of all the items, starting with element 0 (first element).
            $rss_items = $rss->get_items( 0, $maxitems );

            include( FLARE_DIRNAME . '/views/elements/_blog-feed.php' );
            exit ;
        }

        die( "Could not connect to digital-telepathy blog feed..." );
    }
    
    /**
     * AJAX response for total counts
     * 
     * Gets the total counts for each social service and returns a JSON formatted
     * array of values.
     * 
     * @uses wp_verify_nonce()
     * @uses Flare_Model::get_count()
     */
    function ajax_get_counts() {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-get-counts" ) ) {
            die( json_encode( array() ) );
        }
        
        $url = $_REQUEST['url'];
        $post_id = $_REQUEST['post_id'];
        $counts = array();
        $buttons = $this->Button->get();
        
        foreach( $buttons as $button ) {
            $counts[$button['type']] = $this->Button->get_count( $button['type'], $url );
        }
        
        // Update stored count value
        update_post_meta( $post_id, "_{$this->namespace}_counts", $counts );
        
        do_action( "{$this->namespace}_get_counts", $url, $post_id, $counts );
        
        die( json_encode( $counts ) );
    }
    
    /**
     * Define constants
     * 
     * @uses plugins_url()
     */
    static function constants() {
        // Plugin Basename
        if( !defined( 'FLARE_BASENAME' ) ) define( 'FLARE_BASENAME', basename( __FILE__ ) );
        // Plugin Dirname
        if( !defined( 'FLARE_DIRNAME' ) ) define( 'FLARE_DIRNAME', dirname( __FILE__ ) );
        // Plugin URL
        if( !defined( 'FLARE_URLPATH' ) ) define( 'FLARE_URLPATH', plugins_url( "", __FILE__ ) );
        
        // Other constants
        include_once( FLARE_DIRNAME . '/lib/constants.php' );
    }
    
    /**
     * Hook into register_deactivation_hook action
     *
     * Put code here that needs to happen when your plugin is deactivated
     */
    static function deactivate() {
    }
    
    /**
     * Initialization function to hook into the WordPress init action
     * 
     * Instantiates the class on a global variable and sets the class, actions
     * etc. up for use.
     */
    static function instance() {
        global $Flare;
        
        $Flare = new Flare();
    }

    /**
     * Hook into plugin_action_links filter
     *
     * Adds a "Settings" link next to the "Deactivate" link in the plugin listing
     * page when the plugin is active.
     *
     * @param object $links An array of the links to show, this will be the
     * modified variable
     * @param string $file The name of the file being processed in the filter
     */
    function plugin_action_links( $links, $file ) {
        $new_links = array( );
        
        if( $file == plugin_basename( FLARE_DIRNAME . '/' . FLARE_BASENAME ) ) {
            $new_links[] = '<a href="' . admin_url( 'admin.php' ) . '?page=' . FLARE_BASENAME . '">' . __( 'Configure' ) . '</a>';
        }

        return array_merge( $new_links, $links );
    }
    
    /**
     * Build primary navigation HTML
     * 
     * Builds the primary navigation options from the menu_hooks array and marks the
     * current page as active. Returns a string of HTML for output.
     * 
     * @return string
     */
    function primary_navigation() {
        $menu_hooks = $this->menu_hooks;
        $namespace = $this->namespace;
        
        $screen = get_current_screen();
        
        $html = "";
        
        ob_start();
        
            include( FLARE_DIRNAME . '/views/elements/_primary-navigation.php' );
            $html = ob_get_contents();
        
        ob_end_clean();
        
        return $html;
    }
    
    /**
     * Route the user based off of environment conditions
     * 
     * This function will handling routing of form submissions to the appropriate
     * form processor.
     */
    function route() {
        $uri = $_SERVER['REQUEST_URI'];
        $protocol = isset( $_SERVER['HTTPS'] ) ? 'https' : 'http';
        $hostname = $_SERVER['HTTP_HOST'];
        $url = "{$protocol}://{$hostname}{$uri}";
        $is_post = isset( $_POST ) && !empty( $_POST );
        
        if( isset( $_REQUEST[$this->namespace . '_update_share_wpnonce'] ) ) {
            if( $is_post && wp_verify_nonce( $_REQUEST[$this->namespace . '_update_share_wpnonce'], $this->namespace . '_share_options' ) ) {
                $this->_admin_share_update();
            }
        }

        if( isset( $_REQUEST[$this->namespace . '_update_follow_wpnonce'] ) ) {
            if( $is_post && wp_verify_nonce( $_REQUEST[$this->namespace . '_update_follow_wpnonce'], $this->namespace . '_follow_options' ) ) {
                $this->_admin_follow_update();
            }
        }

        if( preg_match( "/admin\.php\?.*page\=" . FLARE_BASENAME . "\/filament/", $uri ) ) {
            wp_redirect( "https://filament.io/applications/flare?utm_source=flare_wp&utm_medium=deployment&utm_content=admin&utm_campaign=filament" );
            exit ;
        }
    }
    
    /**
     * Hook into the the_content filter
     * 
     * Output the ShareBar horizontal code above the post for anchoring the
     * vertical ShareBar and displaying the horizontal ShareBar code.
     * 
     * @global $post;
     * 
     * @uses Flare::get_option()
     * @uses Flare::get()
     */
    function the_content( $content ) {
        global $post;
        
        // If get_the_excerpt is being run, then just give them the $content.
        if( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) ) return $content;
        
        // If Flare shouldn't be here, just return the $content
        if( !$this->_include_flare() ) {
            return $content;
        }
        
        $namespace = $this->namespace;
        $direction = "horizontal";
        $available_buttons = $this->Button->available_buttons;
        $buttons = array();
        $positions = $this->_get_option( 'positions' );
        $enablecounters = $this->_get_option( 'enablecounters' );
        $enabletotal = $this->_get_option( 'enabletotal' );
        $enablehumbleflare = $this->_get_option( 'enablehumbleflare' );
        $humbleflarecount = $this->_get_option( 'humbleflarecount' );
        $positions = $this->_get_option( 'positions' );
        $iconstyle = $this->_get_option( 'iconstyle' );
        $backgroundcolor = $this->_get_option( 'backgroundcolor' );
        $filamenticon = $this->_get_option( 'filamenticon' );
        $buttons = $this->Button->get();
        
        $counts = (array) get_post_meta( $post->ID, "_{$this->namespace}_counts", true );
        
        $total_count = array_sum( array_values( $counts ) );
        
        $classes = array(
            "{$namespace}-{$direction}",
            "{$namespace}-backgroundcolor-{$backgroundcolor}"
        );
        if( $direction == 'vertical' ) $classes[] = "{$namespace}-{$side}";
        if( $enablecounters === true ) $classes[] = 'enablecounters';
        if( $enabletotal === true ) $classes[] = 'enabletotal';
        if( $enablehumbleflare === true ) $classes[] = 'enablehumbleflare';
        
        ob_start();
            include( FLARE_DIRNAME . "/views/sharebar.php" );
            $buttons_html .= ob_get_contents();
        ob_end_clean();
        
        $buttons = array();
        $classes[] = "{$this->namespace}-hidden";
        ob_start();
            include( FLARE_DIRNAME . "/views/sharebar.php" );
            $no_buttons_html .= ob_get_contents();
        ob_end_clean();
        
        if( in_array( 'top', $positions ) || in_array( 'top-left', $positions ) ) {
            $position = in_array( 'top', $positions ) ? 'top' : 'top-left';
            $top_buttons_html = preg_replace( "/class\=\"(" . $namespace . "-" . $direction . ")/", "class=\"$1 " . $namespace . "-position-" . $position, $buttons_html );
            $content = $top_buttons_html . $content;
        } else {
            $content = $no_buttons_html . $content;
        }
    
        if( in_array( 'bottom', $positions ) || in_array( 'bottom-left', $positions ) ) {
            $position = in_array( 'bottom', $positions ) ? 'bottom' : 'bottom-left';
            $bottom_buttons_html = preg_replace( "/class\=\"(" . $namespace . "-" . $direction . ")/", "class=\"$1 " . $namespace . "-position-" . $position, $buttons_html );
            $content = $content . $bottom_buttons_html;
        }
        
        return $content;
    }
    
    /**
     * Hook into WordPress wp_print_styles action
     * 
     * Prints the appropriate stylesheets on public pages for valid post types
     * 
     * @global $post
     * 
     * @uses is_admin()
     * @uses Flare::get_option()
     * @uses wp_enqueue_style()
     */
    function wp_print_styles() {
        global $wp_query;
        
        // Only enqueue these scripts on the front end
        if( is_admin() ) {
            return false;
        }
        
        wp_enqueue_style( $this->namespace );
        wp_enqueue_style( "{$this->namespace}-oswald-font" );
    }
    
    /**
     * Hook into WordPress wp_print_scripts action
     * 
     * Prints the appropriate scripts on public pages for valid post types
     * 
     * @global $post
     * 
     * @uses is_admin()
     * @uses Flare::get_option()
     * @uses wp_enqueue_script()
     */
    function wp_print_scripts() {
        global $wp_query;
        
        // Only enqueue these scripts on the front end
        if( is_admin() || !is_object( $wp_query->post ) ) {
            return false;
        }
        
        // Only enqueue these scripts if this is a post type we have specified
        if( in_array( $wp_query->post->post_type, $this->_get_option( 'post_types' ) ) && count( $wp_query->posts ) == 1 ) {
            wp_enqueue_script( $this->namespace );
            
            $buttons = $this->Button->get();
            if( in_array( 'pinterest', array_keys( $buttons ) ) ) {
                wp_enqueue_script( 'pinterest-button' );
            }
        }
    }
    
    /**
     * Hook into WordPress wp_footer action
     * 
     * Outputs the flare bar element for display on the page next to the post.
     * 
     * @uses is_admin()
     * @uses get_permalink()
     * @uses Flare::get()
     * @uses Flare::get_option()
     */
    function wp_footer() {
        global $wp_query;
        
        // Only enqueue these scripts on the front end
        if( is_admin() ) {
            return false;
        }
        
        // Do the work if this is an included Post Type and it is a single post entry page
        if( in_array( $wp_query->post->post_type, $this->_get_option( 'post_types' ) ) && count( $wp_query->posts ) == 1 ) {
            $buttons = $this->Button->get();
            
            $namespace = $this->namespace;
            $direction = "vertical";
            $available_buttons = $this->Button->available_buttons;
            $enablecounters = $this->_get_option( 'enablecounters' );
            $enabletotal = $this->_get_option( 'enabletotal' );
            $enablehumbleflare = $this->_get_option( 'enablehumbleflare' );
            $humbleflarecount = $this->_get_option( 'humbleflarecount' );
            $positions = $this->_get_option( 'positions' );
            $iconstyle = $this->_get_option( 'iconstyle' );
            $backgroundcolor = $this->_get_option( 'backgroundcolor' );
            $closablevertical = $this->_get_option( 'closablevertical' );
            $filamenticon = $this->_get_option( 'filamenticon' );
            $side = "";
            
            $counts = (array) get_post_meta( $wp_query->post->ID, "_{$this->namespace}_counts", true );
            $total_count = array_sum( array_values( $counts ) );
            
            if( in_array( 'left', $positions ) )
                $side = "left";
            if( in_array( 'right', $positions ) )
                $side = "right";
            
            $classes = array(
                "{$namespace}-{$direction}",
                "{$namespace}-backgroundcolor-{$backgroundcolor}"
            );
            if( $direction == 'vertical' ) $classes[] = "{$namespace}-{$side}";
            if( $enablecounters === true ) $classes[] = 'enablecounters';
            if( $enabletotal === true ) $classes[] = 'enabletotal';
            if( $enablehumbleflare === true ) $classes[] = 'enablehumbleflare';
            if( $closablevertical === true ) $classes[] = 'closablevertical';
            
            echo '<!--[if IE]><style type="text/css">.' . $namespace . '-flyout{visibility:visible;}</style><![endif]-->';
            echo '<!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="' . FLARE_URLPATH . '/css/flare.ie.css" /><![endif]-->';
            
            // Only output if the user has selected to show the sidebar
            if( in_array( 'left', $positions ) || in_array( 'right', $positions ) ) {
                include( FLARE_DIRNAME . "/views/sharebar.php" );
            }
            
            $count_url = admin_url( 'admin-ajax.php' ) . "?action={$namespace}_get_counts&url=" . urlencode( get_permalink( $wp_query->post->ID ) ) . "&post_id={$wp_query->post->ID}&_wpnonce=" . wp_create_nonce( "{$this->namespace}-get-counts" );
            
            include( FLARE_DIRNAME . '/views/_footer.php' );
        }
    }
        
    /**
     * Register scripts used by this plugin for enqueuing elsewhere
     * 
     * @uses wp_register_script()
     */
    function wp_register_scripts() {
        // Fancy Form Elements
        wp_register_script( "{$this->namespace}-fancyform", FLARE_URLPATH . "/js/fancy-form" . ( FLARE_ENVIRONMENT == 'development' ? '.dev' : '' ) . ".js", array( 'jquery' ), '1.0.1', true );
        
        // jQuery Minicolors
        wp_register_script( "{$this->namespace}-jquery-minicolors", FLARE_URLPATH . "/js/jquery-minicolors/jquery.minicolors.min.js", array( 'jquery' ), '1.0.0', true );
        
        // Admin JavaScript
        wp_register_script( "{$this->namespace}-admin", FLARE_URLPATH . "/js/{$this->namespace}-admin" . ( FLARE_ENVIRONMENT == 'development' ? '.dev' : '' ) . ".js", array( 'jquery', "{$this->namespace}-jquery-minicolors", "{$this->namespace}-fancyform", 'jquery-ui-sortable', 'jquery-ui-slider' ), FLARE_VERSION, true );
        
        // Public JavaScript
        wp_register_script( "{$this->namespace}", FLARE_URLPATH . "/js/{$this->namespace}" . ( FLARE_ENVIRONMENT == 'development' ? '.dev' : '' ) . ".js", array( 'jquery' ), FLARE_VERSION, true );
        
        // Pinterest JavaScript library
        wp_register_script( "pinterest-button", ( is_ssl() ? 'https:' : 'http:' ) . "//assets.pinterest.com/js/pinit.js", array(), '', true );
    }
    
    /**
     * Register styles used by this plugin for enqueuing elsewhere
     * 
     * @uses wp_register_style()
     */
    function wp_register_styles() {
        // jQuery Minicolors
        wp_register_style( "{$this->namespace}-fancyform", FLARE_URLPATH . "/css/fancy-form.css", array(), '1.1.0', 'screen' );
        
        // jQuery Minicolors
        wp_register_style( "{$this->namespace}-jquery-minicolors", FLARE_URLPATH . "/css/jquery.minicolors.css", array(), '1.0.0', 'screen' );
        
        // Admin Stylesheet
        wp_register_style( "{$this->namespace}-admin", FLARE_URLPATH . "/css/{$this->namespace}-admin.css", array( "{$this->namespace}-jquery-minicolors", "{$this->namespace}-fancyform" ), FLARE_VERSION, 'screen' );
        
        // Public Stylesheet
        wp_register_style( "{$this->namespace}", FLARE_URLPATH . "/css/{$this->namespace}.css", array(), FLARE_VERSION, 'all' );
        
        // Google Oswald Font
        wp_register_style( "{$this->namespace}-oswald-font", "http://fonts.googleapis.com/css?family=Oswald:700:latin&text=1234567890MK.", array(), FLARE_VERSION, 'all' );
    }
}
    
register_activation_hook( __FILE__, array( 'Flare', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Flare', 'deactivate' ) );

// Initiatie the Flare class at the WordPress plugins_loaded action
add_action( 'plugins_loaded', array( 'Flare', 'instance' ) );
