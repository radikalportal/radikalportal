<?php

/***** Add CSS classes to HTML tag *****/

if (!function_exists('mh_html')) {
	function mh_html() {
		$options = mh_theme_options();
		$sidebars = ' mh-' . $options['sidebars'] . '-sb';
		$options['full_bg'] == 1 ? $fullbg = ' fullbg' : $fullbg = '';
		echo $sidebars . $fullbg;
	}
}
add_action('mh_html_class', 'mh_html');

/***** Add CSS classes to body tag *****/

if (!function_exists('mh_body_class')) {
	function mh_body_class($classes) {
		$options = mh_theme_options();
		$classes[] = 'mh-' . $options['sb_position'] . '-sb';
		$classes[] = 'wt-' . $options['wt_layout'];
		$classes[] = 'pt-' . $options['page_title_layout'];
		$classes[] = 'ab-' . $options['authorbox_layout'];
		$classes[] = 'rp-' . $options['related_layout'];
		$classes[] = 'loop-' . $options['loop_layout'];
		return $classes;
	}
}
add_filter('body_class', 'mh_body_class');

/***** Add Favicon and other stuff *****/

if (!function_exists('mh_head_misc')) {
	function mh_head_misc() {
		$options = mh_theme_options();
		if ($options['mh_favicon']) {
			echo '<link rel="shortcut icon" href="' . esc_url($options['mh_favicon']) . '">' . "\n";
		}
		echo '<!--[if lt IE 9]>' . "\n";
		echo '<script src="' . get_template_directory_uri() . '/js/css3-mediaqueries.js"></script>' . "\n";
		echo '<![endif]-->' . "\n";
		echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
		echo '<link rel="pingback" href="' . get_bloginfo('pingback_url') . '"/>' . "\n";
	}
}
add_action('wp_head', 'mh_head_misc', 1);

/***** News Ticker *****/

if (!function_exists('mh_newsticker')) {
	function mh_newsticker() {
		$options = mh_theme_options();
		if ($options['show_ticker']) { ?>
			<div class="news-ticker clearfix">
				<?php if ($options['ticker_title']) { ?>
					<div class="ticker-title">
						<?php echo esc_attr($options['ticker_title']); ?>
					</div>
				<?php } ?>
				<div class="ticker-content">
					<ul id="ticker"><?php
						$args = array('posts_per_page' => $options['ticker_posts'], 'cat' => $options['ticker_cats'], 'tag' => $options['ticker_tags'], 'offset' => $options['ticker_offset'], 'ignore_sticky_posts' => $options['ticker_sticky']);
						$ticker_loop = new WP_Query($args);
						while ($ticker_loop->have_posts()) : $ticker_loop->the_post(); ?>
						<li class="ticker-item">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<span class="meta ticker-item-meta">
									<?php $category = get_the_category(); ?>
									<?php printf(_x('%1$s in %2$s', 'post meta newsticker', 'mh'), get_the_date(), $category[0]->cat_name . ': '); ?>
								</span>
								<span class="meta ticker-item-title">
									<?php the_title(); ?>
								</span>
							</a>
						</li>
						<?php endwhile;
						wp_reset_postdata(); ?>
					</ul>
				</div>
			</div> <?php
		}
	}
}
add_action('mh_after_header', 'mh_newsticker');

/***** Author box *****/

if (!function_exists('mh_author_box')) {
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
}
add_action('mh_after_post_content', 'mh_author_box');

/***** Post / Image Navigation *****/

if (!function_exists('mh_postnav')) {
	function mh_postnav() {
		global $post;
		$options = mh_theme_options();
		if ($options['post_nav'] == 'enable') {
			$parent_post = get_post($post->post_parent);
			$attachment = is_attachment();
			$previous = ($attachment) ? $parent_post : get_adjacent_post(false, '', true);
			$next = get_adjacent_post(false, '', false);

			if (!$next && !$previous)
			return;

			if ($attachment) {
				$attachments = get_children(array('post_type' => 'attachment', 'post_mime_type' => 'image', 'post_parent' => $parent_post->ID));
				$count = count($attachments);
			}
			echo '<nav class="section-title clearfix" role="navigation">' . "\n";
				if ($previous || $attachment) {
					echo '<div class="post-nav left">' . "\n";
						if ($attachment) {
							if (wp_attachment_is_image($post->id)) {
								if ($count == 1) {
									echo '<a href="' . esc_url(get_permalink($parent_post)) . '">' . __('&larr; Back to article', 'mh') . '</a>';
								} else {
									previous_image_link('%link', __('&larr; Previous image', 'mh'));
								}
							} else {
								echo '<a href="' . esc_url(get_permalink($parent_post)) . '">' . __('&larr; Back to article', 'mh') . '</a>';
							}
						} else {
							previous_post_link('%link', __('&larr; Previous article', 'mh'));
						}
					echo '</div>' . "\n";
				}
				if ($next || $attachment) {
					echo '<div class="post-nav right">' . "\n";
						if ($attachment && wp_attachment_is_image($post->id)) {
							next_image_link('%link', __('Next image &rarr;', 'mh'));
						} else {
							next_post_link('%link', __('Next article &rarr;', 'mh'));
						}
					echo '</div>' . "\n";
				}
			echo '</nav>' . "\n";
		}
	}
}

/***** Related Posts *****/

if (!function_exists('mh_related')) {
	function mh_related() {
		global $post;
		$options = mh_theme_options();
		$tags = wp_get_post_tags($post->ID);
		if ($options['related_layout'] != 'disable' && $tags) {
			$tag_ids = array();
			foreach($tags as $tag) $tag_ids[] = $tag->term_id;
			$args = array('tag__in' => $tag_ids, 'post__not_in' => array($post->ID), 'posts_per_page' => 5, 'ignore_sticky_posts' => 1, 'orderby' => 'rand');
			$related = new wp_query($args);
			if ($related->have_posts()) {
				echo '<section class="related-posts">' . "\n";
					echo '<h3 class="section-title">' . __('Related Articles', 'mh') . '</h3>' . "\n";
					echo '<ul>' . "\n";
						while ($related->have_posts()) : $related->the_post();
							echo '<li class="related-wrap clearfix">' . "\n";
								echo '<div class="related-thumb">' . "\n";
									echo '<a href="' . esc_url(get_permalink($post->ID)) . '" title="' . the_title_attribute('echo=0') . '">';
										if (has_post_thumbnail()) {
											the_post_thumbnail('cp_small');
										} else {
											echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_cp_small.png' . '" alt="No Picture" />';
										}
									echo '</a>' . "\n";
								echo '</div>' . "\n";
								echo '<div class="related-data">' . "\n";
									echo '<a href="' . esc_url(get_permalink($post->ID)) . '"><h4 class="related-title">' . get_the_title() . '</h4></a>' . "\n";
									echo '<span class="related-subheading">' . esc_attr(get_post_meta($post->ID, "mh-subheading", true)) . '</span>' . "\n";
								echo '</div>' . "\n";
							echo '</li>' . "\n";
						endwhile;
					echo '</ul>' . "\n";
				echo '</section>' . "\n";
				wp_reset_postdata();
			}
		}
	}
}
add_action('mh_after_post_content', 'mh_related');

/***** Page Title Output *****/

if (!function_exists('mh_page_title')) {
	function mh_page_title() {
		if (!is_front_page()) {
			echo '<div class="page-title-top"></div>' . "\n";
			echo '<h1 class="page-title">';
			if (is_archive()) {
				if (is_category() || is_tax()) {
					single_cat_title();
				} elseif (is_tag()) {
					single_tag_title();
				} elseif (is_author()) {
					global $author;
					$user_info = get_userdata($author);
					printf(_x('Articles by %s', 'post author', 'mh'), esc_attr($user_info->display_name));
				} elseif (is_day()) {
					echo get_the_date();
				} elseif (is_month()) {
					echo get_the_date('F Y');
				} elseif (is_year()) {
					echo get_the_date('Y');
				} elseif (is_post_type_archive()) {
					global $post;
					$post_type = get_post_type_object(get_post_type($post));
					echo $post_type->labels->name;
				} else {
					_e('Archives', 'mh');
				}
			} else {
				if (is_home()) {
					echo get_the_title(get_option('page_for_posts', true));
				} elseif (is_404()) {
					_e('Page not found (404)', 'mh');
				} elseif (is_search()) {
					printf(__('Search Results for %s', 'mh'), esc_attr(get_search_query()));
				} else {
					the_title();
				}
			}
			echo '</h1>' . "\n";
		}
	}
}
add_action('mh_before_page_content', 'mh_page_title');

/***** Logo / Header Image Fallback *****/

if (!function_exists('mh_logo')) {
	function mh_logo() {
		$header_img = get_header_image();
		$header_title = get_bloginfo('name');
		$header_desc = get_bloginfo('description');
		echo '<a href="' . esc_url(home_url('/')) . '" title="' . esc_attr($header_title) . '" rel="home">' . "\n";
		echo '<div class="logo-wrap" role="banner">' . "\n";
		if ($header_img) {
			echo '<img src="' . esc_url($header_img) . '" height="' . get_custom_header()->height . '" width="' . get_custom_header()->width . '" alt="' . esc_attr($header_title) . '" />' . "\n";
		}
		if (display_header_text()) {
			$header_img ? $logo_pos = 'logo-overlay' : $logo_pos = 'logo-text';
			$text_color = get_header_textcolor();
			if ($text_color != get_theme_support('custom-header', 'default-text-color')) {
				echo '<style type="text/css" id="mh-header-css">';
					echo '.logo-name, .logo-desc { color: #' . esc_attr($text_color) . '; }';
					echo '.logo-name { border-bottom: 3px solid #' . esc_attr($text_color) . '; }';
				echo '</style>' . "\n";
			}
			echo '<div class="logo ' . $logo_pos . '">' . "\n";
			if ($header_title) {
				echo '<h1 class="logo-name">' . esc_attr($header_title) . '</h1>' . "\n";
			}
			if ($header_desc) {
				echo '<h2 class="logo-desc">' . esc_attr($header_desc) . '</h2>' . "\n";
			}
			echo '</div>' . "\n";
		}
		echo '</div>' . "\n";
		echo '</a>' . "\n";
	}
}

/***** Custom Excerpts *****/

if (!function_exists('mh_trim_excerpt')) {
	function mh_trim_excerpt($text = '') {
		$raw_excerpt = $text;
		if ('' == $text) {
			$text = get_the_content('');
			$text = do_shortcode($text);
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]&gt;', $text);
			$excerpt_length = apply_filters('excerpt_length', '200');
			$excerpt_more = apply_filters('excerpt_more', ' [...]');
			$text = wp_trim_words($text, $excerpt_length, $excerpt_more);
		}
		return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
	}
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'mh_trim_excerpt');

if (!function_exists('mh_excerpt')) {
	function mh_excerpt($excerpt_length = '175') {
		global $post;
		$options = mh_theme_options();
		$excerpt = get_the_excerpt();
		if (!has_excerpt()) {
			$excerpt = substr($excerpt, 0, intval($excerpt_length));
			$excerpt = substr($excerpt, 0, strrpos($excerpt, ' '));
		}
		echo '<div class="mh-excerpt">' . wp_kses_post($excerpt) . ' <a href="' . esc_url(get_permalink($post->ID)) . '" title="' . the_title_attribute('echo=0') . '">' . esc_attr($options['excerpt_more']) . '</a></div>' . "\n";
	}
}

/***** Enable Custom Excerpts for Pages *****/

if (!function_exists('mh_excerpts_pages')) {
	function mh_excerpts_pages() {
		add_post_type_support('page', 'excerpt');
	}
}
add_action('init', 'mh_excerpts_pages');

/***** Custom Commentlist *****/

if (!function_exists('mh_comments')) {
	function mh_comments($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
			<div id="comment-<?php comment_ID(); ?>">
				<div class="vcard meta"><?php
					echo get_avatar($comment->comment_author_email, 30);
					echo get_comment_author_link(); ?> //
					<a href="<?php echo esc_url(get_comment_link($comment->comment_ID)) ?>">
						<?php printf(__('%1$s at %2$s', 'mh'), get_comment_date(),  get_comment_time()) ?>
					</a> // <?php
					if (comments_open() && $args['max_depth']!=$depth) {
						comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
					}
					edit_comment_link(__('(Edit)', 'mh'),'  ',''); ?>
				</div>
				<?php if ($comment->comment_approved == '0') : ?>
					<div class="comment-info">
						<?php _e('Your comment is awaiting moderation.', 'mh') ?>
					</div>
				<?php endif; ?>
				<div class="comment-text">
					<?php comment_text() ?>
				</div>
			</div><?php
	}
}

/***** Custom Comment Fields *****/

if (!function_exists('mh_comment_fields')) {
	function mh_comment_fields($fields) {
		$commenter = wp_get_current_commenter();
		$req = get_option('require_name_email');
		$aria_req = ($req ? " aria-required='true'" : '');
		$fields =  array(
			'author'	=>	'<p class="comment-form-author"><label for="author">' . __('Name ', 'mh') . '</label>' . ($req ? '<span class="required">*</span>' : '') . '<br/><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>',
			'email' 	=>	'<p class="comment-form-email"><label for="email">' . __('Email ', 'mh') . '</label>' . ($req ? '<span class="required">*</span>' : '' ) . '<br/><input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>',
			'url' 		=>	'<p class="comment-form-url"><label for="url">' . __('Website', 'mh') . '</label><br/><input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>'
		);
		return $fields;
	}
}
add_filter('comment_form_default_fields', 'mh_comment_fields');

/***** Comment Count Output *****/

if (!function_exists('mh_comment_count')) {
	function mh_comment_count() {
		printf(_nx('1 Comment', '%1$s Comments', get_comments_number(), 'comments number', 'mh'), number_format_i18n(get_comments_number()));
	}
}

/***** Pagination *****/

if (!function_exists('mh_pagination')) {
	function mh_pagination() {
		global $wp_query;
	    $big = 9999;
	    $paginate_links = paginate_links(array(
	    	'base' 		=> str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
	    	'format' 	=> '?paged=%#%',
	    	'current' 	=> max(1, get_query_var('paged')),
	    	'prev_next' => true,
	    	'prev_text' => __('&laquo;', 'mh'),
	    	'next_text' => __('&raquo;', 'mh'),
	    	'total' 	=> $wp_query->max_num_pages)
	    );
		if ($paginate_links) {
	    	echo '<div class="loop-pagination clearfix">';
				echo $paginate_links;
			echo '</div>';
		}
	}
}

/***** Second Sidebar *****/

if (!function_exists('mh_second_sb')) {
	function mh_second_sb() {
		$options = mh_theme_options();
		if ($options['sidebars'] == 'two') {
			echo '<aside class="mh-sidebar-2 sb-wide sb-right">';
				dynamic_sidebar('sidebar-2');
			echo '</aside>' . "\n";
    	}
	}
}

/***** Fix links of carousel widget to work on mobile devices *****/

if (!function_exists('mh_carousel_fix')) {
	function mh_carousel_fix() {
		if (wp_is_mobile() && is_active_widget('', '', 'mh_carousel_hp')) {
			echo '<style type="text/css">.flex-direction-nav { display: none; }</style>';
		}
	}
}
add_action('wp_head', 'mh_carousel_fix');

/***** Add Tracking Code *****/

if (!function_exists('mh_add_trackingcode')) {
	function mh_add_trackingcode() {
		$options = mh_theme_options();
		if ($options['tracking_code']) {
			echo $options['tracking_code'];
		}
	}
}
add_filter('wp_footer', 'mh_add_trackingcode');

/***** Add Featured Image Size to Media Gallery Selection *****/

if (!function_exists('mh_custom_image_size_choose')) {
	function mh_custom_image_size_choose($sizes) {
		$options = mh_theme_options();
		if ($options['sidebars'] == 'no') {
			$custom_sizes = array('slider' => 'Featured Image (large)', 'content' => 'Featured Image (normal)');
		} else {
			$custom_sizes = array('content' => 'Featured Image');
		}
		return array_merge($sizes, $custom_sizes);
	}
}
add_filter('image_size_names_choose', 'mh_custom_image_size_choose');

?>