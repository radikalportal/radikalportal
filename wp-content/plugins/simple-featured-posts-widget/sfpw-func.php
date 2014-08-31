<?php
function first_image() {
	global $post, $posts;
	$first_img = "";
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches [1] [0];

	if(empty($first_img)){ //<- 	Defines a default image
		$first_img = plugin_dir_url(__FILE__)."images/default.png";
	}
		
	return $first_img;
}

function imgSize($img){

	if(strpos($img, "/") == 0){
		$img = substr($img,1);
	}
	
	$size = @getimagesize($img);
	return $size;
}

add_action('wp_head', 'first_image');
add_action('wp_head', 'imgSize');
?>