<?php

/***** Include Content Ad on Posts *****/

if (!function_exists('mh_advertising')) {
	function mh_advertising($content) {
		if (is_singular('post') && is_main_query()) {
			global $post;
			$options = mh_theme_options();
			$ad_position = 1;
			if ($options['teaser_text'] == 'enable') {
				if (strstr($post->post_content, '<!--more-->') && !has_excerpt()) {
					$ad_position = 2;
				}
			}
			$paragraphs = explode("<p", $content);
			$counter = 0;
			foreach($paragraphs as $paragraph) {
				if ($counter == 0) {
					$content = $paragraph;
				}
				if ($counter > 0) {
					$content .= '<p' . $paragraph;
				}
				if ($counter == $ad_position) {
           			if (!get_post_meta($post->ID, 'mh-no-ad', true)) {
			   			if (get_post_meta($post->ID, 'mh-alt-ad', true)) {
				   			$adcode = '<div class="content-ad">' . do_shortcode(get_post_meta($post->ID, 'mh-alt-ad', true)) . '</div>' . "\n";
				   		} else {
							$adcode = !empty($options['content_ad']) ? '<div class="content-ad">' . do_shortcode($options['content_ad']) . '</div>' . "\n" : '';
						}
						$content .= $adcode;
					}
				}
				$counter++;
			}
			return $content;
		} else {
			return $content;
		}
	}
}
add_filter('the_content', 'mh_advertising');

/***** Include Ads on Archives *****/

if (!function_exists('mh_loop_ads')) {
	function mh_loop_ads($post) {
		global $wp_query, $options;
		$adcode = empty($options['loop_ad']) ? '' : '<div class="loop-ad">' . do_shortcode($options['loop_ad']) . '</div>' . "\n";
		$adcount = empty($options['loop_ad_no']) ? '3' : $options['loop_ad_no'];
		if (in_the_loop() && is_archive() || in_the_loop() && is_home()) {
			if ($wp_query->post != $post)
			return;
			if ($wp_query->current_post == 0)
			return;
			if ($wp_query->current_post % $adcount == 0) {
				echo $adcode;
			}
		}
	}
}
add_action('the_post', 'mh_loop_ads');

?>