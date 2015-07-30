<?php

/***** Load Stylesheets *****/

function mh_magazine_child_styles() {
    wp_enqueue_style('mh-magazine-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('mh-magazine-child-style', get_stylesheet_directory_uri() . '/style.css', array('mh-magazine-parent-style'));
}
add_action('wp_enqueue_scripts', 'mh_magazine_child_styles');
