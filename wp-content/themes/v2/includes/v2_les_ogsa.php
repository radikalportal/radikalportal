<?php
function les_ogsa( $atts, $content = null  ){
	extract(shortcode_atts(array(
		'id' => 1,
		'float' => "none"  // default value if none supplied
    ), $atts));

$url = get_permalink($id);
$title = get_the_title( $id );
$img = get_the_post_thumbnail( $id, 'cp_small' );
if ($content) {
return "<div class=\"lesogsa\" style=\"float:$float;\"><table><tr><td>$img</td><td>Les også: <a href='$url' target=\"_blank\">$content</a></td></tr></table></div>";
} else	return "<div class=\"lesogsa\" style=\"float:$float;\"><table><tr><td>$img</td><td><b>Les også:</b> <a href='$url' target=\"_blank\">$title</a></td></tr></table></div>";
}
add_shortcode( 'lesogsa', 'les_ogsa' );
?>