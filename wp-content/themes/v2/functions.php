<?php

/***** Load Stylesheets *****/

function mh_magazine_child_styles() {
    wp_enqueue_style('mh-magazine-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('mh-magazine-child-style', get_stylesheet_directory_uri() . '/style.css', array('mh-magazine-parent-style'));
}
add_action('wp_enqueue_scripts', 'mh_magazine_child_styles');

function mh_logo() {
    echo '<div style="background-color: #ef4030; margin: 20px 20px; padding: 10px 15px; display: inline-block;">';
    echo '<h1>';
    echo '<a style="color: #fff;" href="/">Radikal Portal</a>';
    echo '</h1>';
    echo '</div>';
}

function mh_scripts() {
    wp_enqueue_script('scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'));
    if (!is_admin()) {
	if (is_singular() && comments_open() && (get_option('thread_comments') == 1))
	    wp_enqueue_script('comment-reply');
    }
}

require_once('includes/v2-widgets.php');
