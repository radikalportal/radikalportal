<?php

/***** Load Social Scripts *****/

if (!function_exists('mh_social_scripts')) {
	function mh_social_scripts() {
		$options = mh_theme_options();
		if (is_single() && $options['social_buttons'] != 'disable') {
			echo '<script src="http://platform.twitter.com/widgets.js"></script>' . "\n";
			echo '<script src="https://apis.google.com/js/plusone.js"></script>' . "\n";
		}
	}
}
add_action('wp_footer', 'mh_social_scripts');

/***** Load Facebook Script (SDK) *****/

if (!function_exists('mh_magazine_facebook_sdk')) {
	function mh_magazine_facebook_sdk() {
		$options = mh_theme_options();
		if (is_active_widget('', '', 'mh_magazine_facebook_page') || is_single() && $options['social_buttons'] != 'disable') {
			global $locale; ?>
			<div id="fb-root"></div>
			<script>
				(function(d, s, id){
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/<?php echo esc_attr($locale); ?>/sdk.js#xfbml=1&version=v2.3";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
			</script> <?php
		}
	}
}
add_action('wp_footer', 'mh_magazine_facebook_sdk');

/***** Share Buttons Content *****/

if (!function_exists('mh_share_buttons_content')) {
	function mh_share_buttons_content() {
		$sb_output = '<section class="share-buttons-container clearfix">' . "\n";
		$sb_output .= '<div class="share-button"><div class="fb-like" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div></div>' . "\n";
		$sb_output .= '<div class="share-button"><a href="' . esc_url('https://twitter.com/share') . '" class="twitter-share-button">Tweet</a></div>' . "\n";
		$sb_output .= '<div class="share-button"><div class="g-plusone" data-size="medium"></div></div>' . "\n";
		$sb_output .= '</section>' . "\n";
		echo $sb_output;
	}
}

/***** Share Buttons on Posts *****/

if (!function_exists('mh_share_buttons_top')) {
	function mh_share_buttons_top() {
		$options = mh_theme_options();
		if ($options['social_buttons'] == 'both_social' || $options['social_buttons'] == 'top_social') {
			mh_share_buttons_content();
		}
	}
}
add_action('mh_post_content_top', 'mh_share_buttons_top');

if (!function_exists('mh_share_buttons_bottom')) {
	function mh_share_buttons_bottom() {
		$options = mh_theme_options();
		if ($options['social_buttons'] == 'both_social' || $options['social_buttons'] == 'bottom_social') {
			mh_share_buttons_content();
		}
	}
}
add_action('mh_after_post_content', 'mh_share_buttons_bottom', 9);

?>