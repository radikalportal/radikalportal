<?php
/*
  Plugin Name: AM Events
  Plugin URI: http://wordpress.org/extend/plugins/am-events/
  Description: Adds a post type for events and a customizable widget for displaying upcoming events.
  Version: 1.9.2
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
 *           [if cond="startdate-is-enddate"]
 *           [if cond="startdate-not-enddate"]
 *           [if cond="startday-is-endday"]
 *           [if cond="startday-not-endday"] 
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
 *
 */
 
add_filter('post_row_actions','am_action_row', 10, 2);

/**
 * SAVE_POST
 */
add_action('save_post', 'am_save_custom_meta');
add_action('add_meta_boxes', 'am_add_custom_meta_box');
add_action('admin_menu', 'am_remove_submit_meta_box' );
add_action('add_meta_boxes', 'am_replace_submit_meta_box' );
add_action('save_post', 'am_save_event');
add_action('wp_trash_post', 'am_wp_trash_event_recurring');

/**
 * SCRIPT AND STYLE
 */
add_action('admin_print_styles-post-new.php', 'am_custom_css');
add_action('admin_print_styles-post.php', 'am_custom_css');
add_action('admin_print_styles-edit.php', 'am_custom_css');
add_action('admin_print_scripts-post-new.php', 'am_custom_script_post');
add_action('admin_print_scripts-post.php', 'am_custom_script_post');
add_action('admin_footer-edit.php', 'am_admin_edit_event_foot', 11);
add_action('admin_enqueue_scripts', 'am_custom_script_edit');


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
 * =SCRIPT =STYLE
 * *************************************************************************** */
 
function am_custom_script_post() {
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
            'dateFormat' => _x('mm/dd/yy', 'date picker, see http://docs.jquery.com/UI/Datepicker/parseDate', 'am-events'), //See format options on parseDate
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
                'am_custom_script', plugins_url('/script/admin-post.js', __FILE__), array('jquery-custom')
        );
    }
}

function am_custom_script_edit($hook) {
	if( 'edit.php' != $hook )
        return;
	
	$localization = array(
		'confirmation' => __( 'Are you sure you want to move this and all other events in the series to the Trash?', 'am-events'),
	);
	
	wp_register_script('am_edit_script', plugins_url('/script/admin-edit.js', __FILE__));
	wp_localize_script('am_edit_script', 'localization', $localization); //pass any values to javascript
	wp_enqueue_script( 'am_edit_script');
}

/* load scripts in the footer */
function am_admin_edit_event_foot() {
    $slug = 'am_event';
    # load only when editing a event
    if (   (isset($_GET['page']) && $_GET['page'] == $slug)
        || (isset($_GET['post_type']) && $_GET['post_type'] == $slug))
    {
        echo '<script type="text/javascript" src="', plugins_url('script/admin-edit-foot.js', __FILE__), '"></script>';
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

function am_remove_submit_meta_box() {
    remove_meta_box('submitdiv', 'am_event', 'core');
}

function am_replace_submit_meta_box() {
    add_meta_box('submitdiv', __('Publish'), 'am_post_submit_meta_box', 'am_event', 'side', 'high', null);
}
 
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
    <label for="am_recurrent"><?php _e('Create recurring events:', 'am-events') ?></label>

    <div id="am_recurrent_fields" style="display: none">
        <br />

        <input type="radio" name="am_recurrence_type" value="am_weekly" checked /><span><?php _e('weekly', 'am-events') ?></span><br />
        <input type="radio" name="am_recurrence_type" value="am_biweekly" /><span><?php _e('every two weeks', 'am-events') ?></span><br />
        
        <br />

        <input style="width: 60px" name="am_recurrent_amount" type="number" min="1" max="99" id="am_recurrent_amount"></input>
        <span> <?php _e('times (starting from this event)', 'am-events') ?></span>

        <p style="color: Red"> <?php _e('Recurring events are created when the event is published, saved or updated.', 'am-events') ?> </p>

    </div>


    <?php
}

/**
 * Process the custom metabox fields
 */
function am_save_custom_meta($post_id) {
    global $post;
	
	if (isset($_GET['action']) && $_GET['action'] == 'trash')
		return;

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
 * Display post submit form fields. A modified version of post_submit_meta_box() (wp-admin/includes/meta-boxes.php, line 12).
 *
 * @param object $post
 */
function am_post_submit_meta_box($post, $args = array() ) {
	global $action;

	$post_type = $post->post_type;
	$post_type_object = get_post_type_object($post_type);
	$can_publish = current_user_can($post_type_object->cap->publish_posts);
	$recurring_count = am_get_recurring_count($post->ID);
	?>
	<div class="submitbox" id="submitpost">

	<div id="minor-publishing">

	<?php // Hidden submit button early on so that the browser chooses the right button when form is submitted with Return key ?>
	<div style="display:none;">
	<?php submit_button( __( 'Save' ), 'button', 'save' ); ?>
	</div>

	<div id="minor-publishing-actions">
	<div id="save-action">
	<?php if ( 'publish' != $post->post_status && 'future' != $post->post_status && 'pending' != $post->post_status ) { ?>
	<input <?php if ( 'private' == $post->post_status ) { ?>style="display:none"<?php } ?> type="submit" name="save" id="save-post" value="<?php esc_attr_e('Save Draft'); ?>" class="button" />
	<?php } elseif ( 'pending' == $post->post_status && $can_publish ) { ?>
	<input type="submit" name="save" id="save-post" value="<?php esc_attr_e('Save as Pending'); ?>" class="button" />
	<?php } ?>
	<span class="spinner"></span>
	</div>
	<?php if ( $post_type_object->public ) : ?>
	<div id="preview-action">
	<?php
	if ( 'publish' == $post->post_status ) {
		$preview_link = esc_url( get_permalink( $post->ID ) );
		$preview_button = __( 'Preview Changes' );
	} else {
		$preview_link = set_url_scheme( get_permalink( $post->ID ) );
		/**
		 * Filter the URI of a post preview in the post submit box.
		 *
		 * @since 2.0.5
		 *
		 * @param string $preview_link URI the user will be directed to for a post preview.
		 */
		$preview_link = esc_url( apply_filters( 'preview_post_link', add_query_arg( 'preview', 'true', $preview_link ) ) );
		$preview_button = __( 'Preview' );
	}
	?>
	<a class="preview button" href="<?php echo $preview_link; ?>" target="wp-preview-<?php echo (int) $post->ID; ?>" id="post-preview"><?php echo $preview_button; ?></a>
	<input type="hidden" name="wp-preview" id="wp-preview" value="" />
	</div>
	<?php endif; // public post type ?>
	<div class="clear"></div>
	</div><!-- #minor-publishing-actions -->

	<div id="misc-publishing-actions">

	<div class="misc-pub-section misc-pub-post-status"><label for="post_status"><?php _e('Status:') ?></label>
	<span id="post-status-display">
	<?php
	switch ( $post->post_status ) {
		case 'private':
			_e('Privately Published');
			break;
		case 'publish':
			_e('Published');
			break;
		case 'future':
			_e('Scheduled');
			break;
		case 'pending':
			_e('Pending Review');
			break;
		case 'draft':
		case 'auto-draft':
			_e('Draft');
			break;
	}
	?>
	</span>
	<?php if ( 'publish' == $post->post_status || 'private' == $post->post_status || $can_publish ) { ?>
	<a href="#post_status" <?php if ( 'private' == $post->post_status ) { ?>style="display:none;" <?php } ?>class="edit-post-status hide-if-no-js"><span aria-hidden="true"><?php _e( 'Edit' ); ?></span> <span class="screen-reader-text"><?php _e( 'Edit status' ); ?></span></a>

	<div id="post-status-select" class="hide-if-js">
	<input type="hidden" name="hidden_post_status" id="hidden_post_status" value="<?php echo esc_attr( ('auto-draft' == $post->post_status ) ? 'draft' : $post->post_status); ?>" />
	<select name='post_status' id='post_status'>
	<?php if ( 'publish' == $post->post_status ) : ?>
	<option<?php selected( $post->post_status, 'publish' ); ?> value='publish'><?php _e('Published') ?></option>
	<?php elseif ( 'private' == $post->post_status ) : ?>
	<option<?php selected( $post->post_status, 'private' ); ?> value='publish'><?php _e('Privately Published') ?></option>
	<?php elseif ( 'future' == $post->post_status ) : ?>
	<option<?php selected( $post->post_status, 'future' ); ?> value='future'><?php _e('Scheduled') ?></option>
	<?php endif; ?>
	<option<?php selected( $post->post_status, 'pending' ); ?> value='pending'><?php _e('Pending Review') ?></option>
	<?php if ( 'auto-draft' == $post->post_status ) : ?>
	<option<?php selected( $post->post_status, 'auto-draft' ); ?> value='draft'><?php _e('Draft') ?></option>
	<?php else : ?>
	<option<?php selected( $post->post_status, 'draft' ); ?> value='draft'><?php _e('Draft') ?></option>
	<?php endif; ?>
	</select>
	 <a href="#post_status" class="save-post-status hide-if-no-js button"><?php _e('OK'); ?></a>
	 <a href="#post_status" class="cancel-post-status hide-if-no-js button-cancel"><?php _e('Cancel'); ?></a>
	</div>

	<?php } ?>
	</div><!-- .misc-pub-section -->

	<div class="misc-pub-section misc-pub-visibility" id="visibility">
	<?php _e('Visibility:'); ?> <span id="post-visibility-display"><?php

	if ( 'private' == $post->post_status ) {
		$post->post_password = '';
		$visibility = 'private';
		$visibility_trans = __('Private');
	} elseif ( !empty( $post->post_password ) ) {
		$visibility = 'password';
		$visibility_trans = __('Password protected');
	} elseif ( $post_type == 'post' && is_sticky( $post->ID ) ) {
		$visibility = 'public';
		$visibility_trans = __('Public, Sticky');
	} else {
		$visibility = 'public';
		$visibility_trans = __('Public');
	}

	echo esc_html( $visibility_trans ); ?></span>
	<?php if ( $can_publish ) { ?>
	<a href="#visibility" class="edit-visibility hide-if-no-js"><span aria-hidden="true"><?php _e( 'Edit' ); ?></span> <span class="screen-reader-text"><?php _e( 'Edit visibility' ); ?></span></a>

	<div id="post-visibility-select" class="hide-if-js">
	<input type="hidden" name="hidden_post_password" id="hidden-post-password" value="<?php echo esc_attr($post->post_password); ?>" />
	<?php if ($post_type == 'post'): ?>
	<input type="checkbox" style="display:none" name="hidden_post_sticky" id="hidden-post-sticky" value="sticky" <?php checked(is_sticky($post->ID)); ?> />
	<?php endif; ?>
	<input type="hidden" name="hidden_post_visibility" id="hidden-post-visibility" value="<?php echo esc_attr( $visibility ); ?>" />
	<input type="radio" name="visibility" id="visibility-radio-public" value="public" <?php checked( $visibility, 'public' ); ?> /> <label for="visibility-radio-public" class="selectit"><?php _e('Public'); ?></label><br />
	<?php if ( $post_type == 'post' && current_user_can( 'edit_others_posts' ) ) : ?>
	<span id="sticky-span"><input id="sticky" name="sticky" type="checkbox" value="sticky" <?php checked( is_sticky( $post->ID ) ); ?> /> <label for="sticky" class="selectit"><?php _e( 'Stick this post to the front page' ); ?></label><br /></span>
	<?php endif; ?>
	<input type="radio" name="visibility" id="visibility-radio-password" value="password" <?php checked( $visibility, 'password' ); ?> /> <label for="visibility-radio-password" class="selectit"><?php _e('Password protected'); ?></label><br />
	<span id="password-span"><label for="post_password"><?php _e('Password:'); ?></label> <input type="text" name="post_password" id="post_password" value="<?php echo esc_attr($post->post_password); ?>"  maxlength="20" /><br /></span>
	<input type="radio" name="visibility" id="visibility-radio-private" value="private" <?php checked( $visibility, 'private' ); ?> /> <label for="visibility-radio-private" class="selectit"><?php _e('Private'); ?></label><br />

	<p>
	 <a href="#visibility" class="save-post-visibility hide-if-no-js button"><?php _e('OK'); ?></a>
	 <a href="#visibility" class="cancel-post-visibility hide-if-no-js button-cancel"><?php _e('Cancel'); ?></a>
	</p>
	</div>
	<?php } ?>

	</div><!-- .misc-pub-section -->

	<?php
	/* translators: Publish box date format, see http://php.net/date */
	$datef = __( 'M j, Y @ G:i' );
	if ( 0 != $post->ID ) {
		if ( 'future' == $post->post_status ) { // scheduled for publishing at a future date
			$stamp = __('Scheduled for: <b>%1$s</b>');
		} else if ( 'publish' == $post->post_status || 'private' == $post->post_status ) { // already published
			$stamp = __('Published on: <b>%1$s</b>');
		} else if ( '0000-00-00 00:00:00' == $post->post_date_gmt ) { // draft, 1 or more saves, no date specified
			$stamp = __('Publish <b>immediately</b>');
		} else if ( time() < strtotime( $post->post_date_gmt . ' +0000' ) ) { // draft, 1 or more saves, future date specified
			$stamp = __('Schedule for: <b>%1$s</b>');
		} else { // draft, 1 or more saves, date specified
			$stamp = __('Publish on: <b>%1$s</b>');
		}
		$date = date_i18n( $datef, strtotime( $post->post_date ) );
	} else { // draft (no saves, and thus no date specified)
		$stamp = __('Publish <b>immediately</b>');
		$date = date_i18n( $datef, strtotime( current_time('mysql') ) );
	}

	if ( ! empty( $args['args']['revisions_count'] ) ) :
		$revisions_to_keep = wp_revisions_to_keep( $post );
	?>
	<div class="misc-pub-section misc-pub-revisions">
	<?php
		if ( $revisions_to_keep > 0 && $revisions_to_keep <= $args['args']['revisions_count'] ) {
			echo '<span title="' . esc_attr( sprintf( __( 'Your site is configured to keep only the last %s revisions.' ),
				number_format_i18n( $revisions_to_keep ) ) ) . '">';
			printf( __( 'Revisions: %s' ), '<b>' . number_format_i18n( $args['args']['revisions_count'] ) . '+</b>' );
			echo '</span>';
		} else {
			printf( __( 'Revisions: %s' ), '<b>' . number_format_i18n( $args['args']['revisions_count'] ) . '</b>' );
		}
	?>
		<a class="hide-if-no-js" href="<?php echo esc_url( get_edit_post_link( $args['args']['revision_id'] ) ); ?>"><span aria-hidden="true"><?php _ex( 'Browse', 'revisions' ); ?></span> <span class="screen-reader-text"><?php _e( 'Browse revisions' ); ?></span></a>
	</div>
	<?php endif;
	
	?>
	<div class="misc-pub-section misc-pub-recurrent" id="recurrence">
	<?php
		$r = $recurring_count > 1 ? sprintf( __( 'Yes, %d events', 'am-events' ), $recurring_count ) : ' ' . __( 'No', 'am-events' );
		_e( 'Recurring:', 'am-events' );
		echo '<span id="post-recurrence-display"> ' . $r . '</span>';
	?>
	</div>
	<?php

	if ( $can_publish ) : // Contributors don't get to choose the date of publish ?>
	<div class="misc-pub-section curtime misc-pub-curtime">
		<span id="timestamp">
		<?php printf($stamp, $date); ?></span>
		<a href="#edit_timestamp" class="edit-timestamp hide-if-no-js"><span aria-hidden="true"><?php _e( 'Edit' ); ?></span> <span class="screen-reader-text"><?php _e( 'Edit date and time' ); ?></span></a>
		<div id="timestampdiv" class="hide-if-js"><?php touch_time(($action == 'edit'), 1); ?></div>
	</div><?php // /misc-pub-section ?>
	<?php endif; ?>

	<?php
	/**
	 * Fires after the post time/date setting in the Publish meta box.
	 *
	 * @since 2.9.0
	 */
	do_action( 'post_submitbox_misc_actions' );
	?>
	</div>
	<div class="clear"></div>
	</div>

	<div id="major-publishing-actions">
	<?php
	/**
	 * Fires at the beginning of the publishing actions section of the Publish meta box.
	 *
	 * @since 2.7.0
	 */
	do_action( 'post_submitbox_start' );
	?>
	
	<div style="margin-bottom: 10px">
		<?php 
			if ($recurring_count > 0) {
				echo '' . __( 'This is a recurring event. You can update just this one or all events in the series.' ) . '';
			}
		?>
	</div>
	
	<div id="delete-action">
	<?php
	if ( current_user_can( "delete_post", $post->ID ) ) {
		if ( !EMPTY_TRASH_DAYS )
			$delete_text = __('Delete Permanently');
		else
			$delete_text = __('Move to Trash');
		?>
	<a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php
	}
	?></div>

	<div id="publishing-action">
	<span class="spinner"></span>
	<input type="hidden" name="submit_all" id="submit_all" value="no" />
	<?php
	if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
		if ( $can_publish ) :
			if ( !empty($post->post_date_gmt) && time() < strtotime( $post->post_date_gmt . ' +0000' ) ) : ?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Schedule') ?>" />
			<?php submit_button( __( 'Schedule' ), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
	<?php	else : ?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Publish') ?>" />
			<?php submit_button( __( 'Publish' ), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
			
			<?php 
			if ($recurring_count > 1) { 
				submit_button( __('Publish All', 'am-events'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p', 'onclick' => "document.getElementById('submit_all').value='yes'") );
			} ?>
	<?php	endif;
		else : ?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Submit for Review') ?>" />
			<?php submit_button( __( 'Submit for Review' ), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
			<?php
			if ($recurring_count > 1) { 
				submit_button( __('Submit All for Review', 'am-events'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p', 'onclick' => "document.getElementById('submit_all').value='yes'") );
			} ?>
	<?php
		endif;
	} else { ?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Update') ?>" />
			<input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e('Update') ?>" />
			<?php if ($recurring_count > 1) { ?>
			<input name="save" type="submit" onclick="document.getElementById('submit_all').value='yes'" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e('Update All', 'am-events') ?>" />
			<?php } ?>
	<?php
	} ?>
	</div>
	<div class="clear"></div>
	</div>
	</div>

	<?php
}

/* * ****************************************************************************
 * =MESSAGES
 * *************************************************************************** */

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

    echo "$message";
	
	echo "</div>";
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
function am_add_admin_message($message, $error = false)
{
    if(empty($message)) return false;

    if($error) {
		setcookie('wp-admin-messages-error', /*$_COOKIE['wp-admin-messages-error'] . '@@' . */$message, time()+2);
    } else {
		setcookie('wp-admin-messages-normal', /*$_COOKIE['wp-admin-messages-normal'] . '@@' .*/ $message, time()+2);
    }
}

/* * ****************************************************************************
 * =ROW ACTIONS
 * *************************************************************************** */

/**
 * Add action for trashing recurring events
 */
function am_action_row($actions, $post){
    //check for your post type
	
    if ($post->post_type === 'am_event'){
		if ($post->post_status !== 'trash') { 
			$post_type = 'am_event';
			$recurrence_id = get_post_meta($post->ID, 'am_recurrence_id', true);
			if (isset($recurrence_id) && $recurrence_id) {
				$class = 'submitdelete recurrent recurrent-' . $recurrence_id;;
				
				$title = esc_attr( __( 'Move this and all recurring items to trash', 'am-events' ) );
				$span_id = "trash-recurring-event" . $post->ID;
				$href = get_delete_post_link( $post->ID );
				$href_recurrent = add_query_arg( 'recurrent', 'yes', $href );
				$text_trash = __( 'Trash&nbsp;recurring', 'am-events' );
				$actions = am_array_insert_after("trash", $actions, "trash_recurrent", "<a class=\"submitdelete\" href=\"$href_recurrent\" title=\"$title\">$text_trash</a>");
			}
		}

    }
    return $actions;
}

/*
 * Inserts a new key/value after the key in the array.
 *
 * @param $key
 *   The key to insert after.
 * @param $array
 *   An array to insert in to.
 * @param $new_key
 *   The key to insert.
 * @param $new_value
 *   An value to insert.
 *
 * @return
 *   The new array if the key exists, FALSE otherwise.
 *
 * @see array_insert_before()
 */
function am_array_insert_after($key, array &$array, $new_key, $new_value) {
  if (array_key_exists($key, $array)) {
    $new = array();
    foreach ($array as $k => $value) {
      $new[$k] = $value;
      if ($k === $key) {
        $new[$new_key] = $new_value;
      }
    }
    return $new;
  }
  return FALSE;
}

/* * ****************************************************************************
 * =TRASHING
 * *************************************************************************** */

/**
 * Delete event and recurring events.
 * @return type
 */
function am_wp_trash_event_recurring($post_id) {
	
	if (!isset($_GET['recurrent'])) 
		return;
	
	$recurrent = $_GET['recurrent'];
	if ($recurrent === 'yes') {
		$post_type = get_post_type( $post_id );
		$post_status = get_post_status( $post_id );
		if( $post_type == 'am_event' && in_array($post_status, array('publish','draft','future')) ) {
			$recurrence_id = get_post_meta($post_id, 'am_recurrence_id', true);
			if (isset($recurrence_id) && $recurrence_id !== '') {
			
				// clear recurrence id to avoid infinite loop
				//update_post_meta($post_id, 'am_recurrence_id', '');
				$args = array(
					'post_type' => 'am_event',
					'post_status' => 'any',
					'post_count' => 9999,
					'posts_per_page' => 9999,
					'meta_query' => array(
						array(
							'key' => 'am_recurrence_id',
							'value' => $recurrence_id,
							'compare' => "=",
						),
					),
					'post__not_in' => array($post_id), //exclude current event
				);
				
				$the_query = new WP_Query( $args );
				$post_count = $the_query->post_count;
				$ids_array = array();
				while ($the_query->have_posts()) {
					$the_query->the_post();
					// clear recurrence id to avoid infinite loop
					array_push($ids_array, get_the_ID());
					remove_action('wp_trash_post', 'am_wp_trash_event_recurring');
					wp_trash_post();
					add_action('wp_trash_post', 'am_wp_trash_event_recurring');
				}
				$ids = implode(',', $ids_array);
				// TODO: cookie messes up the undo link
				//$link = '<a href="' . admin_url( wp_nonce_url( "edit.php?post_type=am_event&doaction=undo&action=untrash&ids=$ids", "bulk-posts" )) . '">' . __('Undo', 'am-events') . '</a>';
				$link = '';
				am_add_admin_message( '<p>' . sprintf( __('%d recurrent posts moved to the Trash.', 'am-events'), $post_count) . ' ' . $link . '<p>' );
				
			}
		}
	}
}



/* * ****************************************************************************
 * =SAVING
 * *************************************************************************** */

/**
 * Save event meta and create recurring events.
 * @return type
 */
function am_save_event($post_id) {

	// Quick edit
	$_POST += array("am_quickedit_nonce" => '');
	if ( wp_verify_nonce( $_POST["am_quickedit_nonce"], plugin_basename( __FILE__ ) ) ) {
		// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
		// to do anything
		if ( $_POST['post_type'] !== 'am_event' ) {
			return;
		}
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_REQUEST['am_startdate'] ) ) {
			$startdate = strtotime($_REQUEST['am_startdate'] );
			if ($startdate)
				update_post_meta( $post_id, 'am_startdate', date( am_get_default_date_format(), $startdate) );
		}
		if ( isset( $_REQUEST['am_enddate'] ) ) {
			$enddate = strtotime($_REQUEST['am_enddate'] );
			if ($enddate)
				update_post_meta( $post_id, 'am_enddate', date( am_get_default_date_format(), $enddate));
		}
	}
	// Normal edit
	else {
		// Remove save_post action to avoid infinite loop when calling wp_insert_posts
		remove_action('save_post', 'am_save_event');
		
		if (!isset($_POST['post_ID']))
			return;
			
		$post_id = $_POST['post_ID'];
		$orig_post = get_post($post_id);
		
		if ($_POST && get_post_type($orig_post) === 'am_event') {

			// Check if 'Recurrent Event' has been checked
			if (isset($_POST['am_recurrent']))
			{
				$recurrent = $_POST['am_recurrent'];
			
				if ($recurrent === 'yes') { // If so, create the events
					$recurrent_amount = $_POST['am_recurrent_amount'];
					$recurrence_type = $_POST['am_recurrence_type'];

					// Limit number of created events between 2 and 99
					if ($recurrent_amount < 2 || $recurrent_amount > 99) {
						return;
					}

					//change recurrence id
					$recurrence_id = am_create_recurrence_id($post_id);
					update_post_meta($post_id, 'am_recurrence_id', $recurrence_id);

					$startdate = get_post_meta($post_id, 'am_startdate', true);
					$enddate = get_post_meta($post_id, 'am_enddate', true);

					$start = DateTime::createFromFormat(am_get_default_date_format(), $startdate);
					$end = DateTime::createFromFormat(am_get_default_date_format(), $enddate);

					for ($i = 1; $i < $recurrent_amount; $i++) {
						$new_post = array(
							'post_title' => $orig_post->post_title,
							'post_content' => $orig_post->post_content,
							'post_status' => $orig_post->post_status,
							'post_date' => $orig_post->post_date,
							'post_author' => $orig_post->post_author,
							'post_type' => $orig_post->post_type,
							'post_category' => $orig_post->post_category,
							'post_excerpt' => $orig_post->post_excerpt,
							'comment_status' => $orig_post->comment_status,
							'ping_status' => $orig_post->ping_status,
							'post_password' => $orig_post->post_password,
						);
						$new_post_id = wp_insert_post($new_post);
						
						wp_set_post_tags($new_post_id, wp_get_post_tags($post_id));
						set_post_thumbnail($new_post_id, get_post_thumbnail_id($post_id));

						switch ($recurrence_type) {
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
						update_post_meta($new_post_id, 'am_recurrence_id', $recurrence_id);

						$eventCategories = wp_get_post_terms($post_id, 'am_event_categories');
						$venues = wp_get_post_terms($post_id, 'am_venues');
						foreach ($eventCategories as $c) {
							wp_set_post_terms($new_post_id, $c->term_id, 'am_event_categories', true);
						}
						foreach ($venues as $v) {
							wp_set_post_terms($new_post_id, $v->term_id, 'am_venues', true);
						}
					}

					// Notify user when recurrent events have been created.
					am_add_admin_message( '<p>' . sprintf(__('Created %d recurrent events.', 'am-events') . '</p>', $recurrent_amount) );

				}
			}
		
			// Determine if the specified post is not a revision or auto-save
			if (!( wp_is_post_revision($post_id) && wp_is_post_autosave($post_id) )) {

				// Check if 'Update All' has been clicked
				if (isset($_POST['submit_all']) && $_POST['submit_all'] === 'yes') {
					// Update all recurring events
					$recurrence_id = get_post_meta($post_id, 'am_recurrence_id', true);
					
					$args = array(
						'post_type' => 'am_event',
						'post_status' => 'any',
						'post_count' => 99999,
						'posts_per_page' => 99999,
						'meta_query' => array(
							array(
								'key' => 'am_recurrence_id',
								'value' => $recurrence_id,
								'compare' => "=",
							),
						),
						'post__not_in' => array($post_id), //exclude current event
					);
					
					$the_query = new WP_Query( $args );
					$post_count = $the_query->post_count;
					
					while ($the_query->have_posts()) {
						$the_query->the_post();
						$id = get_the_ID();
						
						$recurrent_post = array(
							'ID' => $id,
							'post_title' => $orig_post->post_title,
							'post_content' => $orig_post->post_content,
							'post_status' => $orig_post->post_status,
							'post_author' => $orig_post->post_author,
							'post_excerpt' => $orig_post->post_excerpt,
							'comment_status' => $orig_post->comment_status,
							'ping_status' => $orig_post->ping_status,
							'post_password' => $orig_post->post_password,
						);
						remove_action('save_post', 'am_save_custom_meta');
						wp_update_post( $recurrent_post );
						add_action('save_post', 'am_save_custom_meta');
						
						wp_set_post_tags($id, wp_get_post_tags($post_id));
						set_post_thumbnail($id, get_post_thumbnail_id($post_id));
						
						// Clear all event categories and venues
						wp_delete_object_term_relationships( $id, 'am_event_categories' );
						wp_delete_object_term_relationships( $id, 'am_venues' );
						
						// Update event categories and venues to match current post
						$event_categories = wp_get_post_terms($post_id, 'am_event_categories');
						$venues = wp_get_post_terms($post_id, 'am_venues');
						foreach ($event_categories as $c) {
							wp_set_post_terms($id, $c->term_id, 'am_event_categories', true);
						}
						foreach ($venues as $v) {
							wp_set_post_terms($id, $v->term_id, 'am_venues', true);
						}
						
					}
					
					// Notify user when recurrent events have been created.
					am_add_admin_message( '<p>' . sprintf(__('%d recurring events updated.', 'am-events') . '</p>', $post_count) );
					
				}
				
			}
		}
	}

    
}


/**
 * Creates an id from the event's slug to group recurrent events for easy deletion
 */
function am_create_recurrence_id($post_id) {
	$slug = get_post($post_id)->post_name;
	$count = 0;
	$id = '';
	do {
		$id = $count === 0 ? $slug : $slug . '-' . $count;
		$args = array(
			'meta_query' => array(
				array(
					'key' => 'am_recurrence_id',
					'value' => $id,
					'compare' => "=",
				),
			),
		);
		$the_query = new WP_Query( $args );
	} while ($the_query->have_posts());
	return $id;
}

/* * ****************************************************************************
 * =COLUMNS
 * *************************************************************************** */

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
            echo date(get_option('date_format') . ' ' . get_option('time_format'), strtotime(get_post_meta($post_id, 'am_startdate', true)));
            break;
        case 'am_enddate':
            echo date(get_option('date_format') . ' ' . get_option('time_format'), strtotime(get_post_meta($post_id, 'am_enddate', true)));
            break;
    }
}

/**
 *  Register the column as sortable
 */
function am_register_sortable_columns($columns) {
    $columns['am_startdate'] = 'am_startdate';
    return $columns;
}

add_filter('manage_edit-am_event_sortable_columns', 'am_register_sortable_columns');

function am_edit_event_load() {
    add_filter('request', 'am_sort_events');
}

/**
 *  Used to sort the events in the administration. 
 */
function am_sort_events($vars) {

    /* Check if we're viewing the 'am_event' post type. */
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
		'menu_icon' => 'dashicons-calendar',
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

if(!function_exists('_log')){
  function _log( $message ) {
    if( WP_DEBUG === true ){
      if( is_array( $message ) || is_object( $message ) ){
        error_log( print_r( $message, true ) );
      } else {
        error_log( $message );
      }
    }
  }
}

// Add to our admin_init function
add_action('quick_edit_custom_box',  'am_add_quick_edit', 10, 2);
 
function am_add_quick_edit($column_name, $post_type) {

	static $printNonce = TRUE;
    if ( $printNonce ) {
        $printNonce = FALSE;
        wp_nonce_field( plugin_basename( __FILE__ ), 'am_quickedit_nonce' );
    }

	?>
	
	<fieldset class="inline-edit-col-left inline-edit-event">
	  <div class="inline-edit-col column-<?php echo $column_name ?>">
		<label class="inline-edit-group">
		<?php 
		 switch ( $column_name ) {
		 
		 case 'am_startdate':
			 ?><span class="title"><?php _e("Start Date", 'am-events') ?></span><span class="input-text-wrap"><input name="am_startdate" type="text" /></span><?php
			 break;
		 case 'am_enddate':
			 ?><span class="title"><?php _e("End Date", 'am-events') ?></span><span class="input-text-wrap"><input name="am_enddate" type="text" /></span><?php
			 break;
		 }
		?>
		</label>
	  </div>
	</fieldset>
	
	<?php
    
}




function am_get_recurring_count($post_id) {
	$recurrence_id = get_post_meta($post_id, 'am_recurrence_id', true);
	if (isset($recurrence_id) && $recurrence_id !== '') {
	
		$args = array(
			'post_type' => 'am_event',
			'post_status' => 'any',
			'post_count' => 99999,
			'posts_per_page' => 99999,
			'meta_query' => array(
				array(
					'key' => 'am_recurrence_id',
					'value' => $recurrence_id,
					'compare' => "=",
				),
			),
		);
		
		$the_query = new WP_Query( $args );
		return $the_query->post_count;
		
	} else {
		return 0;
	}
}





?>