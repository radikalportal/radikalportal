<?php
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
					$post_date ? $date = sprintf(_x('on %s', 'post date', 'mh'), '<span itemprop="dateModified" class="updated">' . get_the_date() . '</span> ') : $date = '';
					$post_author ? $byline = sprintf(_x('by %s', 'post author', 'mh'), '<span class="vcard author" itemprop="author" itemscope itemtype="http://schema.org/Person"><a class="fn" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" itemprop="name">' . esc_html(get_the_author()) . '</a></span> ') : $byline = '';
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
		$content='<div class="brodtekst" itemprop="articleBody">'.$content.'</div>';
		echo apply_filters('the_content', $content);
	}
}
add_action('mh_post_content', 'mh_remove_teaser_text');

/***** Legger til siste artikler i artikkelen*****/

if (!function_exists('sisteartikler')) {
	function sisteartikler() {
		global $post;
		echo '<div class="sisteartikler lesogsa"><h4>Siste artikler</h4><table>';
		$args = array('posts_per_page' => 5, 'post__not_in'=> array($post->ID), 'offset' => 0, 'orderby' => 'date');
		$widget_loop = new WP_Query($args);
		while ($widget_loop->have_posts()) : $widget_loop->the_post();
			$url = get_permalink();
			$title = get_the_title();
			$img = get_the_post_thumbnail($post->ID,'cp_small');
			echo "<tr><td>$img</td><td><a href='$url' target=\"_blank\">$title</a></td></tr>";
		endwhile;
		wp_reset_postdata();
		echo '</table></div>' . "\n";
}
}
add_action('mh_post_content', 'sisteartikler',0);

?>