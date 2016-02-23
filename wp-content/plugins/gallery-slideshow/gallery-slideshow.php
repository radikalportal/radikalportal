<?php
/*
Plugin Name: Galleri Slideshow
Description: Erstatter standard gallerivisning med slider visning. Gallery Slideshow (https://wordpress.org/plugins/gallery-slideshow/) tilpasset til Radikalportal. Baserer seg på cycle2_carousel
Version: 1.3.1
Author: jethin, Radikalportal
License: GPL2
*/

class gallery_ss{
    static function init() {
		remove_shortcode('gallery', 'gallery_shortcode');
        add_shortcode( 'gallery', array(__CLASS__, 'gss_shortcode') );
        add_action( 'wp_enqueue_scripts', array(__CLASS__, 'gss_enqueue_scripts') );
    }

    static function gss_shortcode($atts) { 
		extract( shortcode_atts( array( 'ids' => '', 'name' => 'gslideshow', 'size'=>'', 'style' => '', 'options' => '', 'type' => '' ), $atts ) );
        if ( !function_exists('gss_html_output') ) {
			require 'gss_html.php';
		}
		$output = gss_html_output($ids,$name,$size,$style,$options,$type);
        return $output;
    }

    static function gss_enqueue_scripts() {
        wp_register_script( 'cycle2', plugins_url( 'jquery.cycle2.min.js' , __FILE__ ), array('jquery'), '2.1.3' );
		wp_register_script( 'cycle2_center', plugins_url( 'jquery.cycle2.center.min.js' , __FILE__ ), array('cycle2'), 'v20140128' );
		wp_register_script( 'cycle2_carousel', plugins_url( 'jquery.cycle2.carousel.min.js' , __FILE__ ), array('cycle2'), 'v20140114' );
		wp_register_script( 'cycle2_swipe', plugins_url( 'jquery.cycle2.swipe.min.js' , __FILE__ ), array('cycle2'), 'v20141007' );
		wp_register_script( 'gss_js', plugins_url( 'gss.js', __FILE__ ) );
		wp_register_style( 'gss_css', plugins_url( 'gss.css', __FILE__ ) );
		wp_enqueue_script( 'cycle2' );
		wp_enqueue_script( 'cycle2_center' );
		wp_enqueue_script( 'cycle2_carousel' );
		wp_enqueue_script( 'cycle2_swipe' );
		wp_enqueue_script( 'gss_js' );
		wp_enqueue_style( 'gss_css' );
		$custom_js = plugin_dir_path( __FILE__ ) . 'gss-custom.js';
		if ( file_exists($custom_js) ) {
			wp_register_script( 'gss-custom-js', plugins_url( 'gss-custom.js' , __FILE__ ) );
			wp_enqueue_script( 'gss-custom-js' );
		}
    }
}

gallery_ss::init();

function gss_embed_metadata( $post_id ){
	if ( wp_is_post_revision( $post_id ) ){ return; }
	$post_object = get_post( $post_id );
	$pattern = get_shortcode_regex();
    if ( preg_match_all( '/'. $pattern .'/s', $post_object->post_content, $matches )
	  && array_key_exists( 2, $matches )
	  && in_array( 'gss', $matches[2] ) ){
		// print_r($matches);
		foreach( $matches[2] as $k => $sc_name ){ 
			if( $sc_name == 'gss' ){
				$atts_string =  $matches[3][$k];
				$atts_string = trim( $atts_string );
				$atts = shortcode_parse_atts( $atts_string );
				$name = 'gss_' . $post_id;
				$name .= empty($atts['name']) ? '' : '_' . $atts['name'];
				// extract( shortcode_atts( array( 'ids' => '', 'name' => 'gslideshow', 'style' => '', 'options' => '', $carousel ), $atts ) );
				update_post_meta($post_id, $name, $atts_string);
			}
		}
    }
	// if( has_shortcode( $post_object->post_content, 'gss' ) ) { }
}
add_action( 'save_post', 'gss_embed_metadata' );


add_filter( 'jetpack_gallery_types', 'gallery_types' );
function gallery_types( $gallery_types ) {
	$gallery_types['fx=carousel'] = 'Navigasjon med thumbnail';
	return $gallery_types;
}
?>