<?php

function v2_login_logo() { ?>
    <style type="text/css">
     .login h1 a {
         background-image: url(http://radikalportal.no/apple-touch-icon-180x180.png);
     }
    </style>
<?php }
add_action('login_enqueue_scripts', 'v2_login_logo');

function mh_share_buttons_content() {
    $sb_output = '<section class="share-buttons-container clearfix">' . "\n";
    $sb_output .= '<div class="share-button"><div class="fb-like" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div></div>' . "\n";
    $sb_output .= '<div class="share-button"><a href="' . esc_url('https://twitter.com/share') . '" class="twitter-share-button" data-text="' . get_the_title() . '" data-via="radikalportal">Tweet</a></div>' . "\n";
    $sb_output .= '<div class="share-button"><div class="g-plusone" data-size="medium"></div></div>' . "\n";
    $sb_output .= '</section>' . "\n";
    echo $sb_output;
}

/***** Load Stylesheets *****/

function mh_magazine_child_styles() {
    wp_enqueue_style('mh-magazine-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('mh-magazine-child-style', get_stylesheet_directory_uri() . '/style.css', array('mh-magazine-parent-style'), '151012');
}
add_action('wp_enqueue_scripts', 'mh_magazine_child_styles');

function mh_scripts() {
    wp_enqueue_script('scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'));
    if (!is_admin()) {
	if (is_singular() && comments_open() && (get_option('thread_comments') == 1))
	    wp_enqueue_script('comment-reply');
    }
}

require_once('includes/v2-widgets.php');
require_once('includes/v2-custom-functions.php');

function v2_apple_touch_icons() {
    $output = "";

    $output .= "<link rel=\"shortcut icon\" href=\"/favicon.ico\" type=\"image/x-icon\">\n";
    $output .= "<link rel=\"apple-touch-icon\" href=\"/apple-touch-icon.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"57x57\" href=\"/apple-touch-icon-57x57.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"/apple-touch-icon-72x72.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"76x76\" href=\"/apple-touch-icon-76x76.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"/apple-touch-icon-114x114.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"120x120\" href=\"/apple-touch-icon-120x120.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"/apple-touch-icon-144x144.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"152x152\" href=\"/apple-touch-icon-152x152.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"/apple-touch-icon-180x180.png\">\n";

    echo $output;
}
add_action('wp_head', 'v2_apple_touch_icons');
