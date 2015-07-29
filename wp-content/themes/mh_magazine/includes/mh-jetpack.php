<?php

/***** Add Support for Infinite Scroll *****/

function mh_infinite_scroll_init() {
	add_theme_support('infinite-scroll', array(
    	'container' 		=> 'mh-loop',
		'footer_widgets' 	=> array('footer-1', 'footer-2', 'footer-3', 'footer-4'),
		'render'   			=> 'mh_infinite_scroll_render',
	));
}
add_action('after_setup_theme', 'mh_infinite_scroll_init');

if (!function_exists('mh_infinite_scroll_render')) {
	function mh_infinite_scroll_render() {
		$options = mh_theme_options();
		while (have_posts()) {
			the_post();
			get_template_part('content', 'loop-' . $options['loop_layout']);
		}
	}
}

?>