<?php
/*
  Plugin Name: AM Events
  Plugin URI: http://wordpress.org/extend/plugins/am-events/
  Description: Adds a post type for events and a customizable widget for displaying upcoming events.
  Version: 1.7.1
  Author: Atte Moisio
  Author URI: http://attemoisio.fi
  License: GPL2
 */

/******************************************************************************
 * =COPYRIGHT
 * ****************************************************************************/

/*  Copyright 2013 Atte Moisio  (email : atte.moisio@attemoisio.fi)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */



/************************************************************************************
 * VARIABLE NAMES, TAXONOMY NAMES, WIDGET SHORTCODES AND TEMPLATE TAGS FOR REFERENCE
 * 
 * Custom post type name: 
 * 
 *              'am_event'
 * 
 * Meta: 
 * 
 *              'am_startdate'
 *              'am_enddate'
 *              
 * 
 * Taxonomies: 
 *              
 *              'am_venues'
 *              'am_event_categories'
 * 
 * Widget template shortcodes:
 * 
 *           [event-title]    //The event title
 *           [start-date]     //The start date of the event (uses the date format from the feed options, if it is set. Otherwise uses the default WordPress date format)
 *           [end-date]       //The end date of the event (uses the date format from the feed options, if it is set. Otherwise uses the default WordPress date format)
 *           [event-venue]    //The event venue
 *           [event-category] //The event category
 *           [excerpt] 	      //The event excerpt
 *           [content]        //The event content (number of words can be limited by the 'limit' attribute)        
 *
 * Template tags:
 *
 *     Dates:
 *
 *              am_the_startdate($format = 'Y-m-d H:i:s', $before = '', $after = '', $echo = true)
 *              am_get_the_startdate( $format = 'Y-m-d H:i:s', $post = 0 )
 *              am_the_enddate($format = 'Y-m-d H:i:s', $before = '', $after = '', $echo = true)
 *              am_get_the_enddate( $format = 'Y-m-d H:i:s', $post = 0 )
 * 
 *     Venues:
 *
 *              am_get_the_venue( $id = false )
 *              am_in_venue( $venue, $post = null )
 *              am_get_the_venue_list( $separator = '', $parents='', $post_id = false )
 *              am_the_venue( $separator = '', $parents='', $post_id = false )
 * 
 *     Event categories:
 *
 *              am_get_the_event_category( $id = false )
 *              am_get_the_event_category_list( $separator = '', $parents='', $post_id = false )
 *              am_in_event_category( $eventCategory, $post = null )
 *              am_the_event_category( $separator = '', $parents='', $post_id = false )
 *
 *
 */



/******************************************************************************
 * =ACTION HOOKS
 * *************************************************************************** */

/**
 * INIT
 */
// Custom Post Type
add_action('init', 'am_cpt_init');
// Language files
add_action('plugins_loaded', 'am_load_language_files');
add_action('init', 'am_load_language_files');

/**
 * SETTINGS MENU
 */

if ( is_admin() ){ // admin actions
  add_action( 'admin_menu', 'am_plugin_menu' );
  add_action( 'admin_init', 'am_register_settings' );
} else {
  // non-admin enqueues, actions, and filters
}

function am_get_default_date_format() {
    return 'Y-m-d H:i:s';
}

function am_register_settings() { // whitelist options
  register_setting( 'am-events-settings-group', 'am_timepicker_minutestep' );
  register_setting( 'am-events-settings-group', 'am_rewrite_slug' );
}

function am_plugin_menu() {
	add_options_page( 'AM Events settings', 'AM Events', 'manage_options', 'am-events-settings', 'am_plugin_settings' );
}

function am_plugin_settings() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
        
        ?>
	<div class="wrap">
        <h2><?php _e( 'AM Events settings', 'am_events' ) ?></h2>
        <form method="post" action="options.php"> 
            
        <?php
        settings_fields( 'am-events-settings-group' );
        do_settings_sections( 'am-events-settings-group' );
        ?>
		<?php if( isset($_GET['settings-updated']) ) { ?>
			<script>
				jQuery( "#setting-error-settings_updated p" ).append(" Remember to reapply permalinks when changing slug.");
			</script>
		<?php } ?>
        <table><tbody>
            <tr>
                <td><label for="am_rewrite_slug"><?php _e( 'Slug for event posts', 'am_events' ) ?></label> </td>  
                <td>
					<input id="am_rewrite_slug" valuechanged="" type="text" name="am_rewrite_slug" value="<?php echo get_option('am_rewrite_slug', 'am_event'); ?>" />
					
				</td>

			</tr>
			<tr></tr>
            <tr>
                <td><label for="am_timepicker_minutestep"><?php _e( 'Timepicker minute step', 'am-events' ) ?></label> </td>   
                <td><input id="am_timepicker_minutestep" valuechanged="" type="number" min="1" max="59" name="am_timepicker_minutestep" value="<?php echo get_option('am_timepicker_minutestep', 15); ?>" /></td>
			</tr>

			</tr>
        </tbody></table>
		

		
        
        <?php submit_button(); ?>
        </form>
        <span id="slug-instructions" style="display:none"></span>
        
        
	</div>

        <?php
}

/**
 * SAVE_POST
 */
add_action('save_post', 'am_save_custom_meta');
add_action('add_meta_boxes', 'am_add_custom_meta_box');
add_action('save_post', 'am_save_event');

/**
 * SCRIPT AND STYLE
 */
add_action('admin_print_styles-post-new.php', 'am_custom_css');
add_action('admin_print_styles-post.php', 'am_custom_css');
add_action('admin_print_scripts-post-new.php', 'am_custom_script');
add_action('admin_print_scripts-post.php', 'am_custom_script');

/**
 * WIDGET
 */
add_action('widgets_init', 'am_register_widget');
function am_register_widget() {
	register_widget('AM_Upcoming_Events_Widget');
}

/**
 * INCLUDES
 */
require_once dirname(__FILE__) . '/widget-upcoming-events.php';
require_once dirname(__FILE__) . '/template-tags.php';


add_action('restrict_manage_posts', 'am_restrict_events_by_category');
add_action('manage_am_event_posts_custom_column', 'am_custom_event_column');
//Only run our customization on the 'edit.php' page in the admin. */
add_action('load-edit.php', 'am_edit_event_load');



/* * ****************************************************************************
 * =SCRIPT
 * *************************************************************************** */

function am_custom_script() {
    global $post;
    if ($post->post_type === 'am_event' && is_admin()) {
		
		// include JQuery
        wp_enqueue_script(
                'jquery-custom', plugins_url('/script/jquery-ui-1.10.2.custom.min.js', __FILE__)
        );

		// JQuery datetime picker from http://trentrichardson.com/examples/timepicker/
		// datetimepicker localization
		$localization = array(
		
            // Date picker
            'clearText' => _x('Clear', 'date picker', 'am-events'), //Display text for clear link
            'clearStatus' => _x('Erase the current date', 'date picker', 'am-events'), //Status text for clear link
            'closeText' => _x('Close', 'date picker', 'am-events'), //Display text for close link
            'closeStatus' => _x('Close without change', 'date picker', 'am-events'), //Status text for close link
            'prevText' => _x('<Prev', 'date picker', 'am-events'), //Display text for previous month link
            'prevBigText' => _x('<<', 'date picker', 'am-events'), //Display text for previous year link
            'prevStatus' => _x('Show the previous month', 'date picker', 'am-events'), //Status text for previous month link
            'prevBigStatus' => _x('Show the previous year', 'date picker', 'am-events'), //Status text for previous year link
            'nextText' => _x('Next>', 'date picker', 'am-events'), //Display text for next month link
            'nextBigText' => _x('>>', 'date picker', 'am-events'), //Display text for next year link
            'nextStatus' => _x('Show the next month', 'date picker', 'am-events'), //Status text for next month link
            'nextBigStatus' => _x('Show the next year', 'date picker', 'am-events'), //Status text for next year link
            'currentText' => _x('Today', 'date picker', 'am-events'), //Display text for current month link
            'currentStatus' => _x('Show the current month', 'date picker', 'am-events'), //Status text for current month link
            'january' => __('January', 'am-events'), 'february' => __('February', 'am-events'), 'march' => __('March', 'am-events'),  'april' => __('April', 'am-events'),  'may' => __('May', 'am-events'), 'june' => __('June', 'am-events'), 'july' => __('July', 'am-events'), 'august' => __('August', 'am-events'), 'september' => __('September', 'am-events'), 'october' => __('October', 'am-events'), 'november' => __('November', 'am-events'), 'december' => __('December', 'am-events'),
            'januaryShort' => __('Jan', 'am-events'), 'februaryShort' => __('Feb', 'am-events'), 'marchShort' => __('Mar', 'am-events'),  'aprilShort' => __('Apr', 'am-events'),  'mayShort' => __('May', 'am-events'), 'juneShort' => __('Jun', 'am-events'), 'julyShort' => __('Jul', 'am-events'), 'augustShort' => __('Aug', 'am-events'), 'septemperShort' => __('Sep', 'am-events'), 'octoberShort' => __('Oct', 'am-events'), 'novemberShort' => __('Nov', 'am-events'), 'decemberShort' => __('Dec', 'am-events'),
            'monthStatus' => _x('Show a different month', 'date picker', 'am-events'), //Status text for selecting a month
            'yearStatus' => _x('Show a different year', 'date picker', 'am-events'), //Status text for selecting a year
            'weekHeader' => _x('Wk', 'date picker', 'am-events'), //Header for the week of the year column
            'weekStatus' => _x('Week of the year', 'date picker', 'am-events'), //Status text for the week of the year column
            'dayNameFullMon' => __('Monday', 'am-events'), 'dayNameFullTue' => __('Tuesday', 'am-events'), 'dayNameFullWed' => __('Wednesday', 'am-events'), 'dayNameFullThu' => __('Thursday', 'am-events'), 'dayNameFullFri' => __('Friday', 'am-events'), 'dayNameFullSat' => __('Saturday', 'am-events'), 'dayNameFullSun' => __('Sunday', 'am-events'),
            'dayNameShortMon' => __('Mon', 'am-events'), 'dayNameShortTue' => __('Tue', 'am-events'), 'dayNameShortWed' => __('Wed', 'am-events'), 'dayNameShortThu' => __('Thu', 'am-events'), 'dayNameShortFri' => __('Fri', 'am-events'), 'dayNameShortSat' => __('Sat', 'am-events'), 'dayNameShortSun' => __('Sun', 'am-events'),
            'dayNameMinMon' => __('Mo', 'am-events'), 'dayNameMinTue' => __('Tu', 'am-events'), 'dayNameMinWed' => __('We', 'am-events'), 'dayNameMinThu' => __('Th', 'am-events'), 'dayNameMinFri' => __('Fr', 'am-events'), 'dayNameMinSat' => __('Sa', 'am-events'), 'dayNameMinSun' => __('Su', 'am-events'),
            'dayStatus' => _x('Set DD as first week day', 'date picker', 'am-events'), //Status text for the day of the week selection
            'dateStatus' => _x('Select D, M d', 'date picker', 'am-events'), //Status text for the date selection
            'dateFormat' => _x('mm/dd/yy', 'date picker, see format options on parseDate', 'am-events'), //See format options on parseDate
            'firstDay' => 1, //The first day of the week, Sun = 0, Mon = 1, ...
            'initStatus' => _x('Select a date', 'date picker', 'am-events'), //Initial Status text on opening
            'isRTL' => false, //True if right-to-left language, false if left-to-right
            
            // Time picker
            'minuteStep' => get_option('am_timepicker_minutestep', 15),
            'currentText' => _x('Now', 'time picker', 'am-events'),
            'closeText' => _x('Done', 'time picker', 'am-events'),
            'amNames' => _x('AM', 'time picker', 'am-events'),
            'amNamesShort' => _x('A', 'time picker', 'am-events'),
            'pmNames' => _x('PM', 'time picker', 'am-events'),
            'pmNamesShort' => _x('P', 'time picker', 'am-events'),
            'timeFormat' => _x('HH:mm', 'time picker', 'am-events'),
            'timeSuffix' => _x('', 'time picker', 'am-events'),
            'timeOnlyTitle' => _x('Choose Time', 'time picker', 'am-events'),
            'timeText' => _x('Time', 'time picker', 'am-events'),
            'hourText' => _x('Hour', 'time picker', 'am-events'),
            'minuteText' => _x('Minute', 'time picker', 'am-events'),
            'secondText' => _x('Second', 'time picker', 'am-events'),
            'millisecText' => _x('Millisecond', 'time picker', 'am-events'),
            'timezoneText' => _x('Time Zone', 'time picker', 'am-events'),
            'isRTL' => false
        );
        
		// Add Timepicker script
        wp_register_script('jquery-ui-timepicker', plugins_url('/script/jquery-ui-timepicker-addon.js', __FILE__));
        wp_localize_script('jquery-ui-timepicker', 'localization', $localization); //pass any values to javascript
        wp_enqueue_script(
                'jquery-ui-timepicker', plugins_url('/script/jquery-ui-timepicker-addon.js', __FILE__), array('jquery-custom')
        );

        // Custom script for assigning jquery to inputs
        wp_enqueue_script(
                'am_custom_script', plugins_url('/script/am-events.js', __FILE__), array('jquery-custom')
        );
    }
}

function am_custom_css() {
    // Date picker styles
    wp_enqueue_style(
            'jquery.ui.theme', plugins_url('/css/jquery-ui-1.10.2.custom.css', __FILE__)
    );

    // Time picker styles
    wp_enqueue_style(
            'jquery.ui.timepicker', plugins_url('/css/jquery-ui-timepicker-addon.css', __FILE__));

    // Other styles (for metabox etc.)
    wp_enqueue_style(
            'am-events', plugins_url('/css/am_events.css', __FILE__));
}

/* * ****************************************************************************
 * =META BOX
 * *************************************************************************** */

/**
 * Custom meta box for events
 */
function am_add_custom_meta_box() {

    /*
     * Add a meta box in event edit
     * context: normal (display meta box under content)
     * priority: high
     * callback: am_meta_box_content()
     * parameters: null
     */
    add_meta_box('am_metabox', __('Event Details', 'am-events'), 'am_meta_box_content', 'am_event', 'normal', 'high', null);
}

/**
 * Meta box content
 */
function am_meta_box_content($post) {

    // Nonce for verification.
    if (function_exists('am_nonce'))
        wp_nonce_field(plugin_basename(__FILE__), 'am_nonce');

    
    // The actual fields for data entry
    // Use get_post_meta to retrieve an existing value from the database and use the value for the form
    // DATE FIELDS
    $metaStartDate = get_post_meta($post->ID, 'am_startdate', true);
    $metaEndDate = get_post_meta($post->ID, 'am_enddate', true);

    // Convert dates from 0000-00-00 00:00:00 to 00.00.0000 00:00
    $startDate = '';
    $endDate = '';
    if ($metaStartDate !== '')
        $startDate = date(_x('m/d/Y H:i','administration', 'am-events'), strtotime($metaStartDate));
    if ($metaEndDate !== '')
        $endDate = date(_x('m/d/Y H:i','administration', 'am-events'), strtotime($metaEndDate));
    
    // Echo content of the meta box
    ?>
    <table>
        <tr>
            <td align="right">
                <label for="am_startdate"> 
                    <?php _e("Start Date:", 'am-events') ?>
                </label>
            </td>
            <td>
                <input type="text" id="am_startdate" name="am_startdate" value="<?php echo esc_attr($startDate) ?>" />
            </td>
        </tr>
        <tr>
            <td align="right">
                <label for="am_enddate"><?php _e("End Date:", 'am-events') ?></label>
            </td>
            <td>
                <input type="text" id="am_enddate" name="am_enddate" value="<?php echo esc_attr($endDate) ?>" />
            </td>
        </tr>
    </table>

    <p style="margin: 20px 0 5px 0"><strong> <?php _e('Additional options:', 'am-events') ?></strong></p>
    <input style="margin-right:5px" type="checkbox" id="am_recurrent" name="am_recurrent" value="yes" />
    <label for="am_recurrent"><?php _e('Recurrent event:', 'am-events') ?></label>

    <div id="am_recurrent_fields" style="display: none">
        <br />

        <input type="radio" name="am_recurrence_type" value="am_weekly" checked /><span><?php _e('weekly', 'am-events') ?></span><br />
        <input type="radio" name="am_recurrence_type" value="am_biweekly" /><span><?php _e('every two weeks', 'am-events') ?></span><br />
        
        <br />

        <input style="width: 60px" name="am_recurrent_amount" type="number" min="1" max="99" id="am_recurrent_amount"></input>
        <span> <?php _e('times', 'am-events') ?></span>

        <p style="color: Red"> <?php _e('Recurrent events are created when the event is saved or updated.', 'am-events') ?> </p>

    </div>


    <?php
}

/**
 * Process the custom metabox fields
 */
function am_save_custom_meta($post_id) {
    global $post;

    // Verification check.
    if ( isset($_POST['am_nonce']) && !wp_verify_nonce( $_POST['am_nonce'], plugin_basename(__FILE__) ) )
          return;
    
    // And they're of the right level?
    if (!current_user_can('edit_posts'))
        return;
    /*
     * check if the $_POST variable is set, 
     * meaning that the form been submitted, 
     * and if so then update our post meta options using update_post_meta().
     */
    if ($_POST && get_post_type($post) === 'am_event') {

        // Has the field been used?
        $temp1 = trim($_POST['am_startdate']);
        if (empty($temp1))
            return;

        // Convert startdate to Wordpress default format (0000-00-00 00:00:00)
        $startdate = date(am_get_default_date_format(), strtotime($temp1));
        // Check if conversion succeeded
        if ($startdate != FALSE)
            update_post_meta($post_id, 'am_startdate', $startdate);
        else
            return;


        // Has the field been used?
        $temp2 = trim($_POST['am_enddate']);
        if (empty($temp2))
            return;

        //Convert enddate to Wordpress default format (0000-00-00 00:00:00)
        $enddate = date(am_get_default_date_format(), strtotime($temp2));
        if ($enddate != FALSE)
            update_post_meta($post_id, 'am_enddate', $enddate);
        else
            return;
        
    }
}

/**
 * Messages with the default wordpress classes
 */
function am_show_message($message, $errormsg = false)
{
    if ($errormsg) {
        echo '<div id="message" class="error">';
    }
    else {
        echo '<div id="message" class="updated fade">';
    }

    echo "<p>$message</p></div>";
}

/**
 * Display custom messages
 */
function am_show_admin_messages() {
    
    if(isset($_COOKIE['wp-admin-messages-normal'])) {

        //setcookie('wp-admin-messages-normal', null);
        
        $messages = strtok($_COOKIE['wp-admin-messages-normal'], "@@");

        while ($messages !== false) {
            am_show_message($messages, false);
            $messages = strtok("@@");
        }

        
    }

    if(isset($_COOKIE['wp-admin-messages-error'])) {
        $messages = strtok($_COOKIE['wp-admin-messages-error'], "@@");

        while ($messages !== false) {
            am_show_message($messages, true);
            $messages = strtok("@@");
        }

        setcookie('wp-admin-messages-error', null);
    }
}

/** 
  * Hook into admin notices 
  */
add_action('admin_notices', 'am_show_admin_messages');

/**
 * User Wrapper
 */
function add_admin_message($message, $error = false)
{
    if(empty($message)) return false;

    if($error) {
        setcookie('wp-admin-messages-error', $_COOKIE['wp-admin-messages-error'] . '@@' . $message, time()+3);
    } else {
        setcookie('wp-admin-messages-normal', $_COOKIE['wp-admin-messages-normal'] . '@@' . $message, time()+3);
    }
}   

/**
 * Save event meta and create recurring events.
 * @return type
 */
function am_save_event() {


    // Remove save_post action to avoid infinite loop when calling wp_insert_posts
    remove_action('save_post', 'am_save_event');

    
    if (!isset($_POST['post_ID']))
        return;
    
    $post_id = $_POST['post_ID'];
    $post = get_post($post_id);
    if ($_POST && get_post_type($post) === 'am_event') {

        // Determine if the specified post is a not revision or auto-save
        if (!( wp_is_post_revision($post_id) && wp_is_post_autosave($post_id) )) {

            // Check if 'Recurrent Event' has been checked
            if (isset($_POST['am_recurrent']))
            {
                $recurrent = $_POST['am_recurrent'];
            
                if ($recurrent === 'yes') { // If so, create the events
                    $recurrent_amount = $_POST['am_recurrent_amount'];
                    $recurrenceSelection = $_POST['am_recurrence_type'];

                    // Check if event category and venue have not been selected
                    $taxonomies = get_post_taxonomies($post_id);
                    if (!in_array('am_event_categories', $taxonomies, true)
                            || !in_array('am_venues', $taxonomies, true)) {
                        return; // do not create recurrent events.
                    }

                                    // Limit number of created events to 99
                    if ($recurrent_amount < 2 || $recurrent_amount > 99) {
                        return;
                    }

                    $startdate = get_post_meta($post_id, 'am_startdate', true);
                    $enddate = get_post_meta($post_id, 'am_enddate', true);

                    $start = DateTime::createFromFormat(am_get_default_date_format(), $startdate);
                    $end = DateTime::createFromFormat(am_get_default_date_format(), $enddate);

                    for ($i = 1; $i < $recurrent_amount; $i++) {
                        $new_post = array(
                            'post_title' => $post->post_title,
                            'post_content' => $post->post_content,
                            'post_status' => $post->post_status,
                            'post_date' => $post->post_date,
                            'post_author' => $post->post_author,
                            'post_type' => $post->post_type,
                            'post_category' => $post->post_category,
                            'post_excerpt' => $post->post_excerpt,
                            'comment_status' => $post->comment_status,
                            'ping_status' => $post->ping_status,
                            'post_password' => $post->post_password,
                        );
                        $new_post_id = wp_insert_post($new_post);
                        
                        wp_set_post_tags($new_post_id, wp_get_post_tags($post_id));
                        set_post_thumbnail($new_post_id, get_post_thumbnail_id($post_id));

                        switch ($recurrenceSelection) {
                            case 'am_weekly':
                                $start->modify('+7 days');
                                $end->modify('+7 days');
                                break;
                            case 'am_biweekly':
                                $start->modify('+14 days');
                                $end->modify('+14 days');
                                break;
                            default:
                                return;
                        }

                        update_post_meta($new_post_id, 'am_startdate', $start->format(am_get_default_date_format()));
                        update_post_meta($new_post_id, 'am_enddate', $end->format(am_get_default_date_format()));

                        $eventCategories = wp_get_post_terms($post_id, 'am_event_categories');
                        $venues = wp_get_post_terms($post_id, 'am_venues');
                        foreach ($eventCategories as $c) {
                            wp_set_post_terms($new_post_id, $c->term_id, 'am_event_categories', true);
                        }
                        foreach ($venues as $v) {
                            wp_set_post_terms($new_post_id, $v->term_id, 'am_venues', true);
                        }
                    }

                                    // TODO: Notify user when recurrent events have been created.
                    // add_admin_message( sprintf(__('Created %d recurrent events.', 'am-events'), $recurrent_amount) );

                }
            }
        }
    }

}

/**
 * Add columns to event list in administration
 * @param type $columns
 * @return type
 */
function am_add_event_columns($columns) {
    return array_merge($columns, array('am_startdate' => __('Start Date', 'am-events'),
                'am_enddate' => __('End Date', 'am-events')));
}
add_filter('manage_am_event_posts_columns', 'am_add_event_columns');

function am_custom_event_column($column) {
    global $post;
    $post_id = $post->ID;
    switch ($column) {
        case 'am_startdate':
            echo get_post_meta($post_id, 'am_startdate', true);
            break;
        case 'am_enddate':
            echo get_post_meta($post_id, 'am_enddate', true);
            break;
    }
}

/**
 *  Register the column as sortable
 */
function register_sortable_columns($columns) {
    $columns['am_startdate'] = 'am_startdate';
    return $columns;
}

add_filter('manage_edit-am_event_sortable_columns', 'register_sortable_columns');

function am_edit_event_load() {
    add_filter('request', 'am_sort_events');
}

/**
 *  Used to sort the events in the administration. 
 */
function am_sort_events($vars) {

    /* Check if we're viewing the 'movie' post type. */
    if (isset($vars['post_type']) && 'am_event' === $vars['post_type']) {

        /* Check if 'orderby' is set to 'am_startdate'. */
        if (isset($vars['orderby']) && 'am_startdate' === $vars['orderby']) {

            /* Merge the query vars with our custom variables. */
            $vars = array_merge(
                    $vars, array(
						'meta_key' => 'am_startdate',
						'orderby' => 'meta_value'
                    )
            );
        }
    }

    return $vars;
}

/* * ****************************************************************************
 * =LANGUAGE FILES
 * *************************************************************************** */

/**
 * Loads the language files
 */
function am_load_language_files() {
    load_plugin_textdomain('am-events', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

/* * ****************************************************************************
 * =CPT, CUSTOM POST TYPE
 * *************************************************************************** */

/**
 * Registers new post type 'am_event'
 */
function am_register_post_type() {

    $labels = array(
        'name' => __('Events', 'am-events'),
        'singular_name' => __('Event', 'am-events'),
        'menu_name' => __('Events', 'am-events'),
        'all_items' => __('All Events', 'am-events'),
        'add_new' => __('Add New Event', 'am-events'),
        'add_new_item' => __('Event', 'am-events'),
        'edit_item' => __('Edit Event', 'am-events'),
        'new_item' => __('New Event', 'am-events'),
        'view_item' => __('View Events', 'am-events'),
        'search_items' => __('Search Events', 'am-events'),
        'not_found' => __('No Events Found', 'am-events'),
        'not_found_in_trash' => __('No Events Found', 'am-events'),
    );

    $args = array(
        'label' => __('Event', 'am-events'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5, //Below Posts
        'has_archive' => true,
        'category_name' => 'etusivu',
        'labels' => $labels,
        'description' => __('Type for events', 'am-events'),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array('am_event_category', 'am_venue'),
        'rewrite' => array( 'slug' => get_option('am_rewrite_slug', 'am_event') ),
    );
    
    

    register_post_type('am_event', $args);
}

/*
 * Register custom taxonomy 'am_venues'
 */
function am_register_taxonomy_venues() {

    $labels = array(
        'name' => __('Venues', 'am-events'),
        'singular_name' => __('Venue', 'am-events'),
        'search_items' => __('Search Venues', 'am-events'),
        'popular_items' => __('Popular Venues', 'am-events'),
        'all_items' => __('All Venues', 'am-events'),
        'parent_item' => __('Parent Venue', 'am-events'),
        'parent_item_colon' => __('Parent Venue:', 'am-events'),
        'edit_item' => __('Edit Venue', 'am-events'),
        'update_item' => __('Update Venue', 'am-events'),
        'add_new_item' => __('Add New Venue', 'am-events'),
        'new_item_name' => __('New Venue', 'am-events'),
        'separate_items_with_commas' => __('Separate venues with commas', 'am-events'),
        'add_or_remove_items' => __('Add or remove venues', 'am-events'),
        'choose_from_most_used' => __('Choose from the most used venues', 'am-events'),
        'menu_name' => __('Venues', 'am-events'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => true,
        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy('am_venues', array('am_event'), $args);
}

/**
 * Registers custom taxonomy 'am_event_categories'
 */
function am_register_taxonomy_event_categories() {

    $labels = array(
        'name' => __('Event Categories', 'am-events'),
        'singular_name' => __('Event Category', 'am-events'),
        'search_items' => __('Search Event Categories', 'am-events'),
        'popular_items' => __('Popular Event Categories', 'am-events'),
        'all_items' => __('All Event Categories', 'am-events'),
        'parent_item' => __('Parent Event Category', 'am-events'),
        'parent_item_colon' => __('Parent Event Category:', 'am-events'),
        'edit_item' => __('Edit Event Category', 'am-events'),
        'update_item' => __('Update Event Category', 'am-events'),
        'add_new_item' => __('Add New Event Category', 'am-events'),
        'new_item_name' => __('New Event Category', 'am-events'),
        'separate_items_with_commas' => __('Separate event categories with commas', 'am-events'),
        'add_or_remove_items' => __('Add or remove event categories', 'am-events'),
        'choose_from_most_used' => __('Choose from the most used event categories', 'am-events'),
        'menu_name' => __('Event Categories', 'am-events'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => true,
        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy('am_event_categories', array('am_event'), $args);
}

/**
 * Add filter to ensure the text Event, or event, is displayed when user updates an event.
 */
function am_event_updated_messages($messages) {
    global $post, $post_ID;

    $messages['am_event'] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => sprintf(__('Event updated. <a href="%s">View event</a>', 'am-events'), esc_url(get_permalink($post_ID))),
        2 => __('Custom field updated.', 'am-events'),
        3 => __('Custom field deleted.', 'am-events'),
        4 => __('Event updated.', 'am-events'),
        /* translators: %s: date and time of the revision */
        5 => isset($_GET['revision']) ? sprintf(__('Event restored to revision from %s', 'am-events'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6 => sprintf(__('Event published. <a href="%s">View event</a>', 'am-events'), esc_url(get_permalink($post_ID))),
        7 => __('Event saved.', 'am-events'),
        8 => sprintf(__('Event submitted. <a target="_blank" href="%s">Preview event</a>', 'am-events'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
        9 => sprintf(__('Event scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview event</a>', 'am-events'),
                // translators: Publish box date format, see http://php.net/date
                date_i18n(_x('d.m.Y G:i', 'Publish box, see http://php.net/date', 'am-events'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
        10 => sprintf(__('Event draft updated. <a target="_blank" href="%s">Preview event</a>', 'am-events'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
    );

    return $messages;
}

add_filter('post_updated_messages', 'am_event_updated_messages');

/**
 * Init custom post type (CPT)
 */
function am_cpt_init() {
    am_register_post_type();
    am_register_taxonomy_venues();
    am_register_taxonomy_event_categories();
}

/**
 * Function used to get permalinks to work when you activate the plugin.
 * Pay attention to how am_cpt_init is called in the register_activation_hook callback:
 */
function am_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry, 
    // when you add a post of this CPT.
    am_cpt_init();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'am_rewrite_flush');

/* * ****************************************************************************
 * =TAXONOMY FILTER 
 * *************************************************************************** */

/**
 * Add event category filtering to the event listing in administration.
 */
function am_restrict_events_by_category() {
    remove_action('save_post', 'my_metabox_save');
    global $typenow;
    $post_type = 'am_event';
    $taxonomy = 'am_event_categories';
    if ($typenow == $post_type) {
        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => __("Show All {$info_taxonomy->label}", 'am-events'),
            'taxonomy' => $taxonomy,
            'name' => $taxonomy,
            'orderby' => 'name',
            'selected' => $selected,
            'show_count' => true,
            'hide_empty' => false,
        ));
    };
}

function am_convert_id_to_term_in_query($query) {
    global $pagenow;
    $post_type = 'am_event';
    $taxonomy = 'am_event_categories';
    $q_vars = &$query->query_vars;
    if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
        $q_vars[$taxonomy] = $term->slug;
    }
}
add_filter('parse_query', 'am_convert_id_to_term_in_query');

?>