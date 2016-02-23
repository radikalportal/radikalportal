<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Content-type: application/json; charset=utf-8');

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' ); 

/* $ids = $_GET['ids'];
$name = empty($_GET['name']) ? 'gslideshow' : $_GET['name'];
$style = empty($_GET['style']) ? '' : $_GET['style'];
$options = empty($_GET['options']) ? '' : $_GET['options']; */

$embed_meta = $_GET['embed_meta'];
preg_match("/^gss_([0-9]*)/", $embed_meta, $matches);
$post_id = $matches[1];
$meta_string = get_post_meta( $post_id, $embed_meta, true );
$atts = shortcode_parse_atts( $meta_string );
extract( shortcode_atts( array( 'ids' => '', 'name' => 'gslideshow', 'style' => '', 'options' => '', 'carousel' => '' ), $atts ) );

if ( !function_exists('gss_html_output') ) {
	require 'gss_html.php';
}
$output = gss_html_output($ids,$name,$style,$options,$carousel);
$output = trim($output);

echo $_GET['callback'] . '(' . json_encode($output) . ')';

?>