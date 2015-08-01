<?php
/*
Plugin Name: MH Magazine Shortcodes
Plugin URI: http://www.mhthemes.com/
Description: This plugin provides shortcodes for the MH Magazine WordPress Theme.
Version: 1.0.0
Author: MH Themes
Author URI: http://www.mhthemes.com/
Text Domain: mhu
Domain Path: /languages/
License: GNU General Public License v2 or later
*/

/***** Remove empty p tags for custom shortcodes *****/

function the_content_filter($content) {

	// array of custom shortcodes requiring the fix
	$block = join("|",array("ad","row","half","third","two_third","fourth","three_fourth","fifth","dropcap","highlight","box","flexvid","slider","slide","testimonial"));

	// opening tag
	$rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);

	// closing tag
	$rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);

	return $rep;

}
add_filter("the_content", "the_content_filter");

/***** Ad area *****/

function ad($atts, $content = null) {
	$ad_area  = '';
	$ad_area .= '<div class="ad-label">' . __('Advertisement', 'mh') . '</div>';
	$ad_area .= '<div class="ad-area">' . $content . '</div>';
	return $ad_area;
}
add_shortcode('ad', 'ad');

/***** Columns *****/

function row($atts, $content = null) {
	return '<div class="row clearfix">' . do_shortcode($content) . '</div>';
}
add_shortcode('row', 'row');

function half($atts, $content = null) {
	return '<div class="col-1-2">' . do_shortcode($content) . '</div>';
}
add_shortcode('half', 'half');

function two_third($atts, $content = null) {
	return '<div class="col-2-3">' . do_shortcode($content) . '</div>';
}
add_shortcode('two_third', 'two_third');

function third($atts, $content = null) {
	return '<div class="col-1-3">' . do_shortcode($content) . '</div>';
}
add_shortcode('third', 'third');

function fourth($atts, $content = null) {
	return '<div class="col-1-4">' . do_shortcode($content) . '</div>';
}
add_shortcode('fourth', 'fourth');

function three_fourth($atts, $content = null) {
   	return '<div class="col-3-4">' . do_shortcode($content) . '</div>';
}
add_shortcode('three_fourth', 'three_fourth');

function fifth($atts, $content = null) {
	return '<div class="col-1-5">' . do_shortcode($content) . '</div>';
}
add_shortcode('fifth', 'fifth');

/***** Dropcap *****/

function dropcap($atts, $content = null) {
	return '<span class="dropcap">' . do_shortcode($content) . '</span>';
}
add_shortcode('dropcap', 'dropcap');

/***** Highlight *****/

function highlight($atts, $content = null) {
	return '<span class="highlight" style="background: ' . $atts['color'] . ';">' . do_shortcode($content) . '</span>';
}
add_shortcode('highlight', 'highlight');

/***** Boxes *****/

function box($atts, $content = null) {
	extract(shortcode_atts(array('type' => '', 'toggle' => '', 'height' => ''), $atts));
	if ($toggle == 1) {
		$toggle = '<span class="hide-box">x</span>';
	}
	$height = isset($atts['height']) ? ' style="min-height: ' . esc_attr($height) . '"' : '';
	return '<div class="box ' . esc_attr($type) . '"' . $height . '>' . do_shortcode($content) . $toggle . '</div>';
}
add_shortcode('box', 'box');

/***** Video *****/

function flexvid($atts, $content = null) {
	return '<div class="flex-vid">' . do_shortcode($content) . '</div>';
}
add_shortcode('flexvid', 'flexvid');

/***** Slider *****/

function slider($atts, $content = null) {
	extract(shortcode_atts(array('type' => 'images'), $atts));
	return '<div id="' . esc_attr($type) . '-' . rand(1, 9999) . '" class="flexslider clearfix"><ul class="slides">' . do_shortcode($content) . '</ul></div>';
}
add_shortcode('slider', 'slider');

/***** Slider Item *****/

function slide($atts, $content = null) {
	extract(shortcode_atts(array('author' => '', 'type' => 'image'), $atts));
	$author = isset($atts['author']) ? '<span class="testimonial-author"> - ' . esc_attr($author) . '</span>' : '';
	return '<li><div class="' . esc_attr($type) . '">' . do_shortcode($content) . wp_kses_post($author) . '</div></li>';
}
add_shortcode('slide', 'slide');

/***** Testimonial *****/

function testimonial($atts, $content = null) {
	extract(shortcode_atts(array('author' => ''), $atts));
	$author = isset($atts['author']) ? '<span class="testimonial-author"> - ' . esc_attr($author) . '</span>' : '';
	return '<div class="testimonial">' . do_shortcode($content) . wp_kses_post($author) . '</div>';
}
add_shortcode('testimonial', 'testimonial');

?>