<?php

/***** Fetch Options *****/

$options = get_option('mh_options');

/***** Custom Hooks *****/

function mh_html_class() {
    do_action('mh_html_class');
}
function mh_html_tag() {
    do_action('mh_html_tag');
}
function mh_before_header() {
    do_action('mh_before_header');
}
function mh_after_header() {
    do_action('mh_after_header');
}
function mh_before_page_content() {
    do_action('mh_before_page_content');
}
function mh_before_post_content() {
    do_action('mh_before_post_content');
}
function mh_post_header() {
    do_action('mh_post_header');
}
function mh_post_content_top() {
    do_action('mh_post_content_top');
}
function mh_post_content() {
    do_action('mh_post_content');
}
function mh_post_content_bottom() {
    do_action('mh_post_content_bottom');
}
function mh_loop_content() {
    do_action('mh_loop_content');
}
function mh_after_post_content() {
    do_action('mh_after_post_content');
}

/***** Enable Shortcodes inside Widgets	*****/

add_filter('widget_text', 'do_shortcode');

/***** Theme Setup *****/

if (!function_exists('mh_themes_setup')) {
	function mh_themes_setup() {
		$header = array(
			'default-image'	=> get_template_directory_uri() . '/images/logo.png',
			'default-text-color' => '000',
			'width' => 300,
			'height' => 100,
			'flex-width' => true,
			'flex-height' => true
		);
		add_theme_support('custom-header', $header);
		load_theme_textdomain('mh', get_template_directory() . '/languages');
		add_theme_support('title-tag');
		add_theme_support('automatic-feed-links');
		add_theme_support('custom-background');
		add_theme_support('post-thumbnails');
		add_image_size('slider', 940, 400, true);
		add_image_size('content', 620, 264, true);
		add_image_size('spotlight', 580, 326, true);
		add_image_size('loop', 174, 131, true);
		add_image_size('carousel', 174, 98, true);
		add_image_size('cp_large', 300, 225, true);
		add_image_size('cp_small', 70, 53, true);
		add_editor_style();
		register_nav_menus(array(
			'header_nav' => __('Header Navigation', 'mh'),
			'main_nav' => __('Main Navigation', 'mh'),
			'info_nav' => __('Additional Navigation (below Main Navigation)', 'mh'),
			'footer_nav' => __('Footer Navigation', 'mh')
		));
		add_filter('use_default_gallery_style', '__return_false');
	}
}
add_action('after_setup_theme', 'mh_themes_setup');

/***** Add Backwards Compatibility for Title Tag *****/

if (!function_exists('_wp_render_title_tag')) {
	function mh_magazine_render_title() { ?>
		<title><?php wp_title('|', true, 'right'); ?></title><?php
	}
	add_action('wp_head', 'mh_magazine_render_title');
}

/***** Set Content Width *****/

if (!function_exists('mh_set_content_width')) {
	function mh_set_content_width() {
		global $content_width;
		$options = mh_theme_options();
		if (!isset($content_width)) {
			if ($options['sidebars'] == 'no') {
				$content_width = 940;
			} elseif (is_page_template('page-full.php')) {
				if ($options['sidebars'] == 'two') {
					$content_width = 1260;
				} else {
					$content_width = 940;
				}
			} else {
				$content_width = 620;
			}
		}
	}
}
add_action('template_redirect', 'mh_set_content_width');

/***** Load CSS & JavaScript *****/

if (!function_exists('mh_scripts')) {
	function mh_scripts() {
		wp_enqueue_style('mh-style', get_stylesheet_uri(), false, '2.4.3');
		wp_enqueue_script('scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'));
		if (!is_admin()) {
			if (is_singular() && comments_open() && (get_option('thread_comments') == 1))
				wp_enqueue_script('comment-reply');
		}
	}
}
add_action('wp_enqueue_scripts', 'mh_scripts');

if (!function_exists('mh_admin_scripts')) {
	function mh_admin_scripts($hook) {
		if ('appearance_page_magazine' === $hook || 'widgets.php' === $hook) {
			wp_enqueue_style('mh-admin', get_template_directory_uri() . '/admin/admin.css');
		}
	}
}
add_action('admin_enqueue_scripts', 'mh_admin_scripts');

/***** Register Widget Areas / Sidebars	*****/

if (!function_exists('mh_widgets_init')) {
	function mh_widgets_init() {
		$options = mh_theme_options();
		register_sidebar(array('name' => _x('Header', 'widget area name', 'mh'), 'id' => 'header', 'description' => __('Widget area on top of the site', 'mh'), 'before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => _x('Sidebar', 'widget area name', 'mh'), 'id' => 'sidebar', 'description' => __('Widget area (sidebar left/right) on single posts, pages and archives', 'mh'), 'before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		if ($options['sidebars'] == 'two') {
			register_sidebar(array('name' => sprintf(_x('Sidebar %d', 'widget area name', 'mh'), 2), 'id' => 'sidebar-2', 'description' => __('Second sidebar on single posts, pages and archives', 'mh'), 'before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		}
		register_sidebar(array('name' => sprintf(_x('Home %d', 'widget area name', 'mh'), 1), 'id' => 'home-1', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget home-1 home-wide">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Home %d', 'widget area name', 'mh'), 2), 'id' => 'home-2', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget home-2 home-wide">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Home %d', 'widget area name', 'mh'), 3), 'id' => 'home-3', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget home-3">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Home %d', 'widget area name', 'mh'), 4), 'id' => 'home-4', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget home-4">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Home %d', 'widget area name', 'mh'), 5), 'id' => 'home-5', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget home-5 home-wide">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Home %d', 'widget area name', 'mh'), 6), 'id' => 'home-6', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget home-6">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Home %d', 'widget area name', 'mh'), 7), 'id' => 'home-7', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget home-7 home-wide">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Home %d', 'widget area name', 'mh'), 8), 'id' => 'home-8', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget home-8">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Home %d', 'widget area name', 'mh'), 9), 'id' => 'home-9', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget home-9">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Home %d', 'widget area name', 'mh'), 10), 'id' => 'home-10', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget home-10">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Home %d', 'widget area name', 'mh'), 11), 'id' => 'home-11', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget home-11 home-wide">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		if ($options['sidebars'] == 'two') {
			register_sidebar(array('name' => sprintf(_x('Home %d', 'widget area name', 'mh'), 12), 'id' => 'home-12', 'description' => __('Sidebar on homepage', 'mh'), 'before_widget' => '<div class="sb-widget home-12">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		}
		register_sidebar(array('name' => sprintf(_x('Posts %d', 'widget area name', 'mh'), 1), 'id' => 'posts-1', 'description' => __('Widget area above single post content', 'mh'), 'before_widget' => '<div class="sb-widget posts-1">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Posts %d', 'widget area name', 'mh'), 2), 'id' => 'posts-2', 'description' => __('Widget area below single post content', 'mh'), 'before_widget' => '<div class="sb-widget posts-2">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Pages %d', 'widget area name', 'mh'), 1), 'id' => 'pages-1', 'description' => __('Widget area above single page content', 'mh'), 'before_widget' => '<div class="sb-widget pages-1">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Pages %d', 'widget area name', 'mh'), 2), 'id' => 'pages-2', 'description' => __('Widget area below single page content', 'mh'), 'before_widget' => '<div class="sb-widget pages-2">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		register_sidebar(array('name' => sprintf(_x('Footer %d', 'widget area name', 'mh'), 1), 'id' => 'footer-1', 'description' => __('Widget area in footer', 'mh'), 'before_widget' => '<div class="footer-widget footer-1">', 'after_widget' => '</div>', 'before_title' => '<h6 class="footer-widget-title">', 'after_title' => '</h6>'));
		register_sidebar(array('name' => sprintf(_x('Footer %d', 'widget area name', 'mh'), 2), 'id' => 'footer-2', 'description' => __('Widget area in footer', 'mh'), 'before_widget' => '<div class="footer-widget footer-2">', 'after_widget' => '</div>', 'before_title' => '<h6 class="footer-widget-title">', 'after_title' => '</h6>'));
		register_sidebar(array('name' => sprintf(_x('Footer %d', 'widget area name', 'mh'), 3), 'id' => 'footer-3', 'description' => __('Widget area in footer', 'mh'), 'before_widget' => '<div class="footer-widget footer-3">', 'after_widget' => '</div>', 'before_title' => '<h6 class="footer-widget-title">', 'after_title' => '</h6>'));
		register_sidebar(array('name' => sprintf(_x('Footer %d', 'widget area name', 'mh'), 4), 'id' => 'footer-4', 'description' => __('Widget area in footer', 'mh'), 'before_widget' => '<div class="footer-widget footer-4">', 'after_widget' => '</div>', 'before_title' => '<h6 class="footer-widget-title">', 'after_title' => '</h6>'));
		register_sidebar(array('name' => _x('Contact', 'widget area name', 'mh'), 'id' => 'contact', 'description' => __('Widget area (sidebar) on contact page template', 'mh'), 'before_widget' => '<div class="sb-widget contact">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		if ($options['sidebars'] == 'two') {
			register_sidebar(array('name' => sprintf(_x('Contact %d', 'widget area name', 'mh'), 2), 'id' => 'contact-2', 'description' => __('2nd widget area (sidebar) on contact page template', 'mh'), 'before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
		}
	}
}
add_action('widgets_init', 'mh_widgets_init');

/***** Include Several Functions *****/

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

if (is_admin()) {
	require_once('admin/admin.php');
}

require_once('includes/mh-breadcrumb.php');
require_once('includes/mh-post-content.php');
require_once('includes/mh-options.php');
require_once('includes/mh-widgets.php');
require_once('includes/mh-custom-functions.php');
require_once('includes/mh-google-webfonts.php');
require_once('includes/mh-social.php');
require_once('includes/mh-advertising.php');

if (class_exists('Jetpack')) {
	require_once('includes/mh-jetpack.php');
}

if (is_plugin_active('woocommerce/woocommerce.php')) {
	require_once('woocommerce/mh-custom-woocommerce.php');
}

?>