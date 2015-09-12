<?php

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
    wp_enqueue_style('mh-magazine-child-style', get_stylesheet_directory_uri() . '/style.css', array('mh-magazine-parent-style'));
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
