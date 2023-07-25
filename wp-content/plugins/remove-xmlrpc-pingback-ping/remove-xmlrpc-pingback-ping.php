<?php
/*
Plugin Name:       Remove XMLRPC Pingback Ping
Plugin URI:        http://wordpress.org/plugins/remove-xmlrpc-pingback-ping
Description:       Prevent WordPress from participating in and being a victim of pingback denial of service attacks.
Version:           1.6
Author:            WP Security Ninja
Author URI:        https://wpsecurityninja.com/
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt

Copyright 2014 - 2019  Web factory Ltd  (email: support@webfactoryltd.com)
Copyright 2019 - WP Security Ninja (email: support@wpsecurityninja.com)

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

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

define('RXPP_PLUGIN_URL', plugin_dir_path(__FILE__));

require RXPP_PLUGIN_URL . 'vendor/autoload.php';
add_action('admin_init', array('PAnD', 'init'));

add_filter('wp_headers', 'remove_x_pingback_header' );
add_filter('admin_init', 'xrpp_settings_register_fields');
add_action('admin_notices', 'xrpp_admin_notice_blocked_attempts');
add_filter('xmlrpc_methods', 'rxpp_remove_xmlrpc_pingback_ping');
register_uninstall_hook(__FILE__, 'do_rxpp_uninstall_function');

/**
* Returns current plugin version (read dynamically from file itself)
*
* @author	Lars Koudal
* @since	v0.0.1
* @version	v1.0.0	Monday, August 9th, 2021.	
* @version	v1.0.1	Monday, July 24th, 2023.
* @global
* @return	mixed
*/
function xrpp_get_plugin_version()
{
	$plugin_data = get_file_data(__FILE__, array('version' => 'Version'), 'plugin');
	return $plugin_data['version'];
}





/**
* remove_x_pingback_header.
*
* @author	Lars Koudal
* @since	v0.0.1
* @version	v1.0.0	Monday, July 24th, 2023.
* @global
* @param	mixed	$headers	
* @return	mixed
*/
function remove_x_pingback_header( $headers ) {
	if (isset($headers['X-Pingback'])) {
		unset($headers['X-Pingback']);
	}
	return $headers;
}



add_action ('admin_menu', function () {
	/**
	 * @var		mixed	add_management_page('Remov
	 */
	add_management_page('Remove XMLRPC Pingback Ping', 'RXPP', 'install_plugins', 'xrpp-plugin', 'admin_tools_page', null);
});

/**
* admin_tools_page.
*
* @author	Lars Koudal
* @since	v0.0.1
* @version	v1.0.0	Monday, July 24th, 2023.	
* @version	v1.0.1	Monday, July 24th, 2023.
* @global
* @return	void
*/
function admin_tools_page() {
	$rxpp_blocked_methods_count = get_option('rxpp_blocked_methods_count', false);
	?>
	<div class="wrap">
		<h2>Remove XMLRPC Pingback Ping</h2>
	<h3>You are protected from XML-RPC pingback DOS attacks</h3>
	<p>You are preventing denial of service attacks on your website.</p>
	<?php
	if ($rxpp_blocked_methods_count) {
		?>
		<p><strong>
		<?php
		// translators: Show number of attempts blocked so far
		printf(esc_html__('%s blocked attempts so far!', 'remove-xmlrpc-pingback-ping'), esc_attr($rxpp_blocked_methods_count));
		?>
		</strong></p>
		<?php
	}
	
	$current_user = wp_get_current_user();
	$admin_name   = $current_user->user_firstname;
	
	if ($current_user->user_lastname) {
		$admin_name .= ' ' . $current_user->user_lastname;
	}
	
	if ('' === $admin_name) {
		$admin_name = $current_user->display_name;
	}
	?>
	
	<p>Serious about security? Try out our Security Ninja plugin that protects you for many more security problems. <a class="button button-primary button-small" href="https://wpsecurityninja.com/?utm_source=remove_xmlrpc_plugin&utm_medium=plugin&utm_content=notice" aria-label="Visit wpsecurityninja.com now" data-name="WP Security Ninja" target="_blank" rel="noopener">Visit wpsecurityninja.com</a></p>
		
		<form class="ml-block-form" action="https://static.mailerlite.com/webforms/submit/h9i9y0" data-code="h9i9y0" method="post" target="_blank"  style="float: left;
			margin-top: 20px;
			padding: 0.7em 2em 1em;
			border: 1px solid #c3c4c7;
			box-shadow: 0 1px 1px rgba(0,0,0,.04);
			background: #fff;
			box-sizing: border-box;">
			<p>Sign up for our newsletter here:</p>
			<table>
			<tbody>
			<tr>
			<td>
			<input type="text" class="regular-text" data-inputmask="" name="fields[name]" placeholder="Name" autocomplete="name" value="<?php echo esc_html($admin_name); ?>" style="width:15em;">
			</td>
			<td>
			<input aria-label="email" aria-required="true" data-inputmask="" type="email" class="regular-text required email" data-inputmask="" name="fields[email]" placeholder="Email" autocomplete="email" value="<?php echo esc_html($current_user->user_email); ?>" style="width:15em;">
			</td>
			<td>
			<button type="submit" class="button button-primary">Subscribe</button>
			</td>
			</tr>
			</table>
			
			<input type="hidden" name="fields[signupsource]" value="RXPP Plugin <?php echo esc_attr(xrpp_get_plugin_version()); ?>">
			<input type="hidden" name="ml-submit" value="1">
			<input type="hidden" name="anticsrf" value="true">
			</form>
			
			
			</div>
			<?php
			
		}
		
		
		
		/**
		* xrpp_admin_notice_blocked_attempts.
		*
		* @author  Lars Koudal
		* @since   v0.0.1
		* @version v1.0.0  Monday, August 9th, 2021.
		* @return  void
		*/
		function xrpp_admin_notice_blocked_attempts()
		{
			
			if (PAnD::is_admin_notice_active('xrpp-newsletter-notice-90')) {
				
				$current_user = wp_get_current_user();
				$admin_name   = $current_user->user_firstname;
				
				if ($current_user->user_lastname) {
					$admin_name .= ' ' . $current_user->user_lastname;
				}
				?>
				<div data-dismissible="xrpp-newsletter-notice-90" class="updated notice notice-success is-dismissible">
				<h3>Join the WP Security Ninja Newsletter</h3>
				
				<p>WordPress security made easy - Protect your website from hackers and malicious software.</p>
				
				<form class="ml-block-form" action="https://static.mailerlite.com/webforms/submit/h9i9y0" data-code="h9i9y0" method="post" target="_blank">
					
					<table>
					<tbody>
					<tr>
					<td>
					<img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/sn-icon.png'); ?>" class="alignleft" width="100" style="margin-right:10px;" alt="Sign up for wpsecurityninja.com newsletter">
					</td>
					<td>
					<input type="text" class="regular-text" data-inputmask="" name="fields[name]" placeholder="Name" autocomplete="name" value="<?php echo esc_html($admin_name); ?>" style="width:15em;">
					</td>
					<td>
					<input aria-label="email" aria-required="true" data-inputmask="" type="email" class="regular-text required email" data-inputmask="" name="fields[email]" placeholder="Email" autocomplete="email" value="<?php echo esc_html($current_user->user_email); ?>" style="width:15em;">
					</td>
					<td>
					<button type="submit" class="button button-primary">Subscribe</button>
					</td>
					</tr>
					</table>
					
					<input type="hidden" name="fields[signupsource]" value="RXPP Plugin <?php echo esc_attr(xrpp_get_plugin_version()); ?>">
					<input type="hidden" name="ml-submit" value="1">
					<input type="hidden" name="anticsrf" value="true">
					</form>
					<p>You can unsubscribe anytime. For more details, review our <a href="https://wpsecurityninja.com/privacy-policy/" target="_blank">Privacy Policy</a>.</p>
					<p><small>Signup form is shown every 30 days until dismissed</small></p>
					</div>
					
					<?php
				}
				
				if (!PAnD::is_admin_notice_active('xrpp-admin-notice-14')) {
					return;
				}
				$rxpp_blocked_methods_count = get_option('rxpp_blocked_methods_count', false);
				?>
				<div data-dismissible="xrpp-admin-notice-14" class="updated notice notice-success is-dismissible">
				<h2>You are protected from XML-RPC pingback DOS attacks!</h2>
				<p>You are preventing denial of service attacks on your website.</p>
				<?php
				if ($rxpp_blocked_methods_count) {
					?>
					<p><strong>
					<?php
					// translators: Show number of attempts blocked so far
					printf(esc_html__('%s blocked attempts so far!', 'remove-xmlrpc-pingback-ping'), esc_attr($rxpp_blocked_methods_count));
					?>
					</strong></p>
					<?php
				}
				?>
				<?php
				
				$current_user = wp_get_current_user();
				$admin_name   = $current_user->user_firstname;
				
				if ($current_user->user_lastname) {
					$admin_name .= ' ' . $current_user->user_lastname;
				}
				
				if ('' === $admin_name) {
					$admin_name = $current_user->display_name;
				}
				?>
				
				<p>Serious about security? Try out our Security Ninja plugin that protects you for many more security problems. <a class="button button-primary button-small" href="https://wpsecurityninja.com/?utm_source=remove_xmlrpc_plugin&utm_medium=plugin&utm_content=notice" aria-label="Visit wpsecurityninja.com now" data-name="WP Security Ninja" target="_blank" rel="noopener">Visit wpsecurityninja.com</a></p>
					
					<form class="ml-block-form" action="https://static.mailerlite.com/webforms/submit/h9i9y0" data-code="h9i9y0" method="post" target="_blank">
						<hr>
						<p>Sign up for our newsletter here:</p>
						<table>
						<tbody>
						<tr>
						<td>
						<input type="text" class="regular-text" data-inputmask="" name="fields[name]" placeholder="Name" autocomplete="name" value="<?php echo esc_html($admin_name); ?>" style="width:15em;">
						</td>
						<td>
						<input aria-label="email" aria-required="true" data-inputmask="" type="email" class="regular-text required email" data-inputmask="" name="fields[email]" placeholder="Email" autocomplete="email" value="<?php echo esc_html($current_user->user_email); ?>" style="width:15em;">
						</td>
						<td>
						<button type="submit" class="button button-primary">Subscribe</button>
						</td>
						</tr>
						</table>
						
						<input type="hidden" name="fields[signupsource]" value="RXPP Plugin <?php echo esc_attr(xrpp_get_plugin_version()); ?>">
						<input type="hidden" name="ml-submit" value="1">
						<input type="hidden" name="anticsrf" value="true">
						</form>
						<br/>
						
						
						</div>
						<?php
					}
					
					
					
					/**
					 * rxpp_remove_xmlrpc_pingback_ping.
					 *
					 * @author	Lars Koudal
					 * @since	v0.0.1
					 * @version	v1.0.0	Monday, August 9th, 2021.	
					 * @version	v1.0.1	Monday, July 24th, 2023.
					 * @param	mixed	$methods	
					 * @return	mixed
					 */
					function rxpp_remove_xmlrpc_pingback_ping($methods) {
						unset($methods['pingback.ping']);
						// update count
						$rxpp_blocked_methods_count = get_option('rxpp_blocked_methods_count', 0);
						$rxpp_blocked_methods_count++;
						update_option('rxpp_blocked_methods_count', $rxpp_blocked_methods_count, false);
						return $methods;
					}
					
					
					/**
					* do_rxpp_uninstall_function.
					*
					* @author	Lars Koudal
					* @since	v0.0.1
					* @version	v1.0.0	Monday, August 9th, 2021.	
					* @version	v1.0.1	Monday, July 24th, 2023.
					* @global
					* @return	void
					*/
					function do_rxpp_uninstall_function()
					{
						delete_option('rxpp_blocked_methods_count');
					}
					
					
					/**
					* xrpp_settings_register_fields.
					*
					* @author  Lars Koudal
					* @since   v0.0.1
					* @version v1.0.0  Tuesday, August 10th, 2021.
					* @global
					* @return  void
					*/
					function xrpp_settings_register_fields()
					{
						register_setting('general', 'blocked_pingback_ping', 'esc_attr');
						add_settings_field('blocked_pingback_ping', '<label for="blocked_pingback_ping">' . __('Blocked pingback.ping attacks', 'xrpp_blocked_count') . '</label>', 'xrpp_settings_fields_html', 'general');
					}
					
					
					/**
					* xrpp_settings_fields_html.
					*
					* @author  Lars Koudal
					* @since   v0.0.1
					* @version v1.0.0  Tuesday, August 10th, 2021.
					* @global
					* @return  void
					*/
					function xrpp_settings_fields_html()
					{
						$value = get_option('rxpp_blocked_methods_count', 0);
						echo esc_html(number_format_i18n($value));
						echo '<p class="description">Protected by the Remove & Disable XML-RPC Pingback plugin.</p>';
					}
					