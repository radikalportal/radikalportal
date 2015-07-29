<?php

/***** Subheading on Posts *****/

if (!function_exists('mh_subheading')) {
	function mh_subheading() {
		global $post;
		if (get_post_meta($post->ID, "mh-subheading", true)) {
			echo '<div class="subheading-top"></div>' . "\n";
			echo '<h2 class="subheading">' . esc_attr(get_post_meta($post->ID, "mh-subheading", true)) . '</h2>' . "\n";
		}
	}
}
add_action('mh_post_header', 'mh_subheading');

/***** Post Meta *****/

if (!function_exists('mh_post_meta')) {
	function mh_post_meta() {
		$options = mh_theme_options();
		$post_date = !$options['post_meta_date'];
		$post_author = !$options['post_meta_author'];
		$post_cat = !$options['post_meta_cat'];
		$post_comments = !$options['post_meta_comments'];
		if ($post_date || $post_author || $post_cat || $post_comments) {
			echo '<p class="meta post-meta">';
				if ($post_date || $post_author || $post_cat) {
					$post_date ? $date = sprintf(_x('on %s', 'post date', 'mh'), '<span class="updated">' . get_the_date() . '</span> ') : $date = '';
					$post_author ? $byline = sprintf(_x('by %s', 'post author', 'mh'), '<span class="vcard author"><a class="fn" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span> ') : $byline = '';
					$post_cat ? $category = sprintf(_x('in %s', 'post category', 'mh'), get_the_category_list(', ', '')) : $category = '';
					printf(_x('Posted %1$s %2$s %3$s', 'post meta', 'mh'), $date, $byline, $category);
					if ($post_comments) {
						echo ' // ';
					}
				}
				if ($post_comments) {
					mh_comment_count();
				}
			echo '</p>' . "\n";
		}
	}
}
add_action('mh_post_header', 'mh_post_meta');

/***** Post Meta (Loop) *****/

if (!function_exists('mh_loop_meta')) {
	function mh_loop_meta() {
		$options = mh_theme_options();
		$post_date = !$options['post_meta_date'];
		$post_comments = !$options['post_meta_comments'];
		if ($post_date || $post_comments) {
			echo '<p class="meta">';
			if ($post_date) {
				echo get_the_date();
			}
			if ($post_date && $post_comments) {
				echo ' // ';
			}
			if ($post_comments) {
				mh_comment_count();
			}
			echo '</p>' . "\n";
		}
	}
}

/***** Add Teaser Text above Post Content *****/

if (!function_exists('mh_teaser_text')) {
	function mh_teaser_text() {
		global $page, $post, $more;
		$options = mh_theme_options();
		if ($page == '1' && $options['teaser_text'] == 'enable') {
			if (has_excerpt()) {
				echo apply_filters('the_excerpt', '<p class="mh-teaser-text">' . get_the_excerpt() . '</p>');
			} elseif (strstr($post->post_content, '<!--more-->')) {
				$more = 0;
				echo '<p class="mh-teaser-text">' . do_shortcode(get_the_content('')) . '</p>';
				$more = 1;
			}
		}
	}
}
add_action('mh_post_content_top', 'mh_teaser_text');

/***** Display regular Post Content without Teaser Text *****/

if (!function_exists('mh_remove_teaser_text')) {
	function mh_remove_teaser_text() {
		global $post, $more;
		$options = mh_theme_options();
		if ($options['teaser_text'] == 'enable') {
			if (strstr($post->post_content, '<!--more-->') && !has_excerpt()) {
				$content = get_the_content('', true);
			} else {
				$content = get_the_content();
			}
		} else {
			$content = get_the_content();
		}
		echo apply_filters('the_content', $content);
	}
}
add_action('mh_post_content', 'mh_remove_teaser_text');

/***** Featured Image on Posts *****/

if (!function_exists('mh_featured_image')) {
	function mh_featured_image() {
		global $page, $post;
		$options = mh_theme_options();
		if (has_post_thumbnail() && $page == '1' && $options['featured_image'] == 'enable' && !get_post_meta($post->ID, 'mh-no-image', true)) {
			if ($options['sidebars'] == 'no') {
				$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'slider');
			} else {
				$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'content');
			}
			if ($options['link_featured_image'] == 'enable') {
				$att_url_begin = '<a href="' . esc_url(get_attachment_link(get_post_thumbnail_id())) . '">';
				$att_url_end = '</a>';
			} else {
				$att_url_begin = '';
				$att_url_end = '';
			}
			$caption_text = get_post(get_post_thumbnail_id())->post_excerpt;
			echo "\n" . '<div class="post-thumbnail">' . "\n";
				echo $att_url_begin . '<img src="' . esc_url($thumbnail[0]) . '" alt="' . esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)) . '" title="' . esc_attr(get_post(get_post_thumbnail_id())->post_title) . '" />' . $att_url_end . "\n";
				if ($caption_text) {
					echo '<span class="wp-caption-text">' . wp_kses_post($caption_text) . '</span>' . "\n";
				}
			echo '</div>' . "\n";
		}
	}
}
add_action('mh_post_content_top', 'mh_featured_image');

/***** Pagination for paginated Posts *****/

if (!function_exists('mh_posts_pagination')) {
	function mh_posts_pagination($content) {
		if (is_singular() && is_main_query()) {
			$content .= wp_link_pages(array('before' => '<div class="pagination clear">', 'after' => '</div>', 'link_before' => '<span class="pagelink">', 'link_after' => '</span>', 'nextpagelink' => __('&raquo;', 'mh'), 'previouspagelink' => __('&laquo;', 'mh'), 'pagelink' => '%', 'echo' => 0));
		}
		return $content;
	}
}
add_filter('the_content', 'mh_posts_pagination', 1);

?>