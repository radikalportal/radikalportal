<?php

/***** Author box *****/

function mh_author_box($author_ID = '') {
    $options = mh_theme_options();
    if ($options['authorbox_layout'] != 'disable' && get_the_author_meta('description', $author_ID) && !is_attachment() || is_page_template('page-authors.php')) {
	if (!is_page_template('page-authors.php')) {
	    $author_ID = get_the_author_meta('ID');
	}
	$name = get_the_author_meta('display_name', $author_ID);
	$website = get_the_author_meta('user_url', $author_ID);
	$facebook = get_the_author_meta('facebook', $author_ID);
	$twitter = get_the_author_meta('twitter', $author_ID);
	$googleplus = get_the_author_meta('googleplus', $author_ID);
	$youtube = get_the_author_meta('youtube', $author_ID);
	echo '<section class="author-box">' . "\n";
	echo '<div class="author-box-wrap clearfix">' . "\n";
	echo '<div class="author-box-avatar">' . get_avatar($author_ID, 113) . '</div>' . "\n";
	echo '<h5 class="author-box-name">' . sprintf(__('About %s', 'mh'), esc_attr($name)) . '<span class="author-box-postcount"> (<a href="' . esc_url(get_author_posts_url($author_ID)) . '" title="' . sprintf(__('More articles written by %s', 'mh'), esc_attr($name)) . '">' . sprintf(__('%s Articles', 'mh'), count_user_posts($author_ID)) . '</a>)</span></h5>' . "\n";
	if (get_the_author_meta('description', $author_ID)) {
	    echo '<div class="author-box-desc">' . wp_kses_post(get_the_author_meta('description', $author_ID)) . '</div>' . "\n";
	} else {
	    echo '<div class="author-box-desc">' . __('The author has not yet added any personal or biographical info to his author profile.', 'mh') . '</div>' . "\n";
	}
	echo '</div>' . "\n";
	if ($options['author_contact'] == 'enable') {
	    if ($website || $facebook || $twitter || $googleplus || $youtube) {
		echo '<div class="author-box-contact">';
		echo '<span class="author-box-contact-start">' . __('Contact:', 'mh') . ' </span>';
		if ($website) {
		    echo '<a class="author-box-website" href="' . esc_url($website) . '" title="' . sprintf(__('Visit the website of %s', 'mh'), esc_attr($name)) . '" target="_blank">' . __('Website', 'mh') . '</a>';
		}
		if ($facebook) {
		    echo '<a class="author-box-facebook" href="' . esc_url($facebook) . '" title="' . sprintf(__('Follow %s on Facebook', 'mh'), esc_attr($name)) . '" target="_blank">' . __('Facebook', 'mh') . '</a>';
		}
		if ($twitter) {
		    echo '<a class="author-box-twitter" href="' . esc_url($twitter) . '" title="' . sprintf(__('Follow %s on Twitter', 'mh'), esc_attr($name)) . '" target="_blank">' . __('Twitter', 'mh') . '</a>';
		}
		if ($googleplus) {
		    echo '<a class="author-box-googleplus" href="' . esc_url($googleplus) . '" title="' . sprintf(__('Follow %s on Google+', 'mh'), esc_attr($name)) . '" target="_blank">' . __('Google+', 'mh') . '</a>';
		}
		if ($youtube) {
		    echo '<a class="author-box-youtube" href="' . esc_url($youtube) . '" title="' . sprintf(__('Follow %s on YouTube', 'mh'), esc_attr($name)) . '" target="_blank">' . __('YouTube', 'mh') . '</a>';
		}
		echo '</div>' . "\n";
	    }
	}
	echo '</section>' . "\n";
    }
}
add_action('mh_post_content_top', 'mh_author_box', 11);

function v2_coauthors_box($author_ID = '') {
    $options = mh_theme_options();
    if ($options['authorbox_layout'] != 'disable' && !is_attachment() || is_page_template('page-authors.php')) {
	$name = get_the_author_meta('display_name', $author_ID);
	$website = get_the_author_meta('user_url', $author_ID);
	$facebook = get_the_author_meta('facebook', $author_ID);
	$twitter = get_the_author_meta('twitter', $author_ID);
	$googleplus = get_the_author_meta('googleplus', $author_ID);
	$youtube = get_the_author_meta('youtube', $author_ID);
	echo '<section class="author-box">' . "\n";
	echo '<div class="author-box-wrap clearfix">' . "\n";
	echo '<div class="author-box-avatar">' . get_avatar($author_ID, 113) . '</div>' . "\n";
	echo '<h5 class="author-box-name">' . sprintf(__('About %s', 'mh'), esc_attr($name)) . '<span class="author-box-postcount"> (<a href="' . esc_url(get_author_posts_url($author_ID)) . '" title="' . sprintf(__('More articles written by %s', 'mh'), esc_attr($name)) . '">' . sprintf(__('%s Articles', 'mh'), count_user_posts($author_ID)) . '</a>)</span></h5>' . "\n";
	if (get_the_author_meta('description', $author_ID)) {
	    echo '<div class="author-box-desc">' . wp_kses_post(get_the_author_meta('description', $author_ID)) . '</div>' . "\n";
	} else {
	    echo '<div class="author-box-desc">' . __('The author has not yet added any personal or biographical info to his author profile.', 'mh') . '</div>' . "\n";
	}
	echo '</div>' . "\n";
	if ($options['author_contact'] == 'enable') {
	    if ($website || $facebook || $twitter || $googleplus || $youtube) {
		echo '<div class="author-box-contact">';
		echo '<span class="author-box-contact-start">' . __('Contact:', 'mh') . ' </span>';
		if ($website) {
		    echo '<a class="author-box-website" href="' . esc_url($website) . '" title="' . sprintf(__('Visit the website of %s', 'mh'), esc_attr($name)) . '" target="_blank">' . __('Website', 'mh') . '</a>';
		}
		if ($facebook) {
		    echo '<a class="author-box-facebook" href="' . esc_url($facebook) . '" title="' . sprintf(__('Follow %s on Facebook', 'mh'), esc_attr($name)) . '" target="_blank">' . __('Facebook', 'mh') . '</a>';
		}
		if ($twitter) {
		    echo '<a class="author-box-twitter" href="' . esc_url($twitter) . '" title="' . sprintf(__('Follow %s on Twitter', 'mh'), esc_attr($name)) . '" target="_blank">' . __('Twitter', 'mh') . '</a>';
		}
		if ($googleplus) {
		    echo '<a class="author-box-googleplus" href="' . esc_url($googleplus) . '" title="' . sprintf(__('Follow %s on Google+', 'mh'), esc_attr($name)) . '" target="_blank">' . __('Google+', 'mh') . '</a>';
		}
		if ($youtube) {
		    echo '<a class="author-box-youtube" href="' . esc_url($youtube) . '" title="' . sprintf(__('Follow %s on YouTube', 'mh'), esc_attr($name)) . '" target="_blank">' . __('YouTube', 'mh') . '</a>';
		}
		echo '</div>' . "\n";
	    }
	}
	echo '</section>' . "\n";
    }
}

function v2_coauthors_boxes() {
    $forfatterids = get_post_custom_values('forfatterid');
    if (isset($forfatterids)) {
	foreach ($forfatterids as $key => $value) {
            v2_coauthors_box($value);
        }
    }
}
add_action('mh_post_content_top', 'v2_coauthors_boxes', 12);


//Anbefalinger
function rp_create_anbefalinger() {
	// set up labels
	$labels = array(
 		'name' => 'Anbefalinger',
    	'singular_name' => 'Anbefaling',
    	'add_new' => 'Ny anbefaling',
    	'add_new_item' => 'Ny anbefaling',
    	'edit_item' => 'Rediger anbefaling',
    	'new_item' => 'Ny anbefaling',
    	'all_items' => 'Alle anbefalinger',
    	'view_item' => 'Vis anbefaling',
    	'search_items' => 'Søk anbefalinger',
    	'not_found' =>  'Ingen anbefaling funnet',
    	'not_found_in_trash' => 'Ingen anbefaling funnet i papirkurven.', 
    	'parent_item_colon' => '',
    	'menu_name' => 'Anbefalinger',
    );
    //register post type
	register_post_type( 'Anbefalinger', array(
		'labels' => $labels,
		'has_archive' => true,
 		'public' => true,
		'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail','page-attributes' ),
		'taxonomies' => array( 'post_tag', 'category' ),	
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'rewrite' => array( 'slug' => 'anbefalinger' ),
		'menu_icon'   => 'dashicons-layout',
		)
	);
}
add_action( 'init', 'rp_create_anbefalinger' );

?>
