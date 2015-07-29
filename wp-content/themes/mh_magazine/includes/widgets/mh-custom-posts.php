<?php

/***** MH Custom Posts Widget *****/

class mh_custom_posts_widget extends WP_Widget {
    function __construct() {
		parent::__construct(
			'mh_custom_posts', esc_html__('MH Custom Posts', 'mh'),
			array('classname' => 'mh_custom_posts', 'description' => esc_html__('Custom Posts Widget to display posts based on categories or tags.', 'mh'))
		);
	}
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $category = isset($instance['category']) ? $instance['category'] : '';
        $cats = empty($instance['cats']) ? '' : $instance['cats'];
        $tags = empty($instance['tags']) ? '' : $instance['tags'];
        $postcount = empty($instance['postcount']) ? '5' : $instance['postcount'];
        $offset = empty($instance['offset']) ? '' : $instance['offset'];
        $order = isset($instance['order']) ? $instance['order'] : 'date';
        $excerpt = isset($instance['excerpt']) ? $instance['excerpt'] : 'none';
        $excerpt_length = empty($instance['excerpt_length']) ? '175' : $instance['excerpt_length'];
        $link = empty($instance['link']) ? '' : $instance['link'];
        $thumbnails = isset($instance['thumbnails']) ? $instance['thumbnails'] : 'show_thumbs';
        $sticky = isset($instance['sticky']) ? $instance['sticky'] : 1;
        $date = isset($instance['date']) ? $instance['date'] : 0;
		$comments = isset($instance['comments']) ? $instance['comments'] : 0;

        if ($link) {
	        $before_title = $before_title . '<a href="' . esc_url($link) . '" class="widget-title-link">';
	        $after_title = '</a>' . $after_title;
        } elseif ($category) {
        	$cat_url = get_category_link($category);
	        $before_title = $before_title . '<a href="' . esc_url($cat_url) . '" class="widget-title-link">';
	        $after_title = '</a>' . $after_title;
        }

        if ($cats) {
	    	$category = $category . ', ' . $cats;
        }

        echo $before_widget;
        if (!empty($title)) { echo $before_title . esc_attr($title) . $after_title; }
        $thumbnails == 'show_thumbs' || $thumbnails == 'hide_large' ? $cp_no_image = '' : $cp_no_image = ' cp-no-image'; ?>
        <ul class="cp-widget<?php echo $cp_no_image; ?> clearfix"> <?php
		$args = array('posts_per_page' => $postcount, 'offset' => $offset, 'cat' => $category, 'tag' => $tags, 'orderby' => $order, 'ignore_sticky_posts' => $sticky);
		$counter = 1;
		$widget_loop = new WP_Query($args);
		while ($widget_loop->have_posts()) : $widget_loop->the_post();
			if ($counter == 1 && $excerpt == 'first' || $excerpt == 'all') : ?>
			<li class="cp-wrap cp-large clearfix">
				<?php if ($thumbnails == 'show_thumbs' || $thumbnails == 'hide_small') : ?>
					<div class="cp-thumb-xl"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php if (has_post_thumbnail()) { the_post_thumbnail('cp_large'); } else { echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_300x225.png' . '" alt="No Picture" />'; } ?></a></div>
				<?php endif; ?>
				<div class="cp-data">
					<h3 class="cp-xl-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					<?php if ($date == 0 || $comments == 0) { ?>
						<p class="meta"><?php
							if ($date == 0) {
								$post_date = get_the_date();
								echo $post_date;
							}
							if ($date == 0 && $comments == 0) {
								echo ' // ';
							}
							if ($comments == 0) {
								mh_comment_count();
							} ?>
						</p>
					<?php } ?>
				</div>
				<?php mh_excerpt($excerpt_length); ?>
			</li><?php
			else : ?>
			<li class="cp-wrap cp-small clearfix">
				<?php if ($thumbnails == 'show_thumbs' || $thumbnails == 'hide_large') : ?>
					<div class="cp-thumb"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php if (has_post_thumbnail()) { the_post_thumbnail('cp_small'); } else { echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_cp_small.png' . '" alt="No Picture" />'; } ?></a></div>
				<?php endif; ?>
				<div class="cp-data">
					<p class="cp-widget-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></p>
					<?php if ($comments == 0) { ?>
						<p class="meta"><?php mh_comment_count(); ?></p>
					<?php } ?>
				</div>
			</li><?php
			endif;
			$counter++;
		endwhile;
		wp_reset_postdata(); ?>
        </ul><?php
        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['category'] = absint($new_instance['category']);
        $instance['cats'] = sanitize_text_field($new_instance['cats']);
        $instance['tags'] = sanitize_text_field($new_instance['tags']);
        $instance['postcount'] = absint($new_instance['postcount']);
        $instance['offset'] = absint($new_instance['offset']);
        $instance['order'] = strip_tags($new_instance['order']);
        $instance['excerpt'] = strip_tags($new_instance['excerpt']);
        $instance['excerpt_length'] = absint($new_instance['excerpt_length']);
        $instance['link'] = esc_url_raw($new_instance['link']);
        $instance['thumbnails'] = strip_tags($new_instance['thumbnails']);
        $instance['sticky'] = isset($new_instance['sticky']) ? strip_tags($new_instance['sticky']) : '';
        $instance['date'] = isset($new_instance['date']) ? strip_tags($new_instance['date']) : '';
        $instance['comments'] = isset($new_instance['comments']) ? strip_tags($new_instance['comments']) : '';
        return $instance;
    }
    function form($instance) {
        $defaults = array('title' => '', 'category' => '', 'cats' => '', 'tags' => '', 'postcount' => '5', 'offset' => '0', 'order' => 'date', 'excerpt' => 'none', 'excerpt_length' => '175', 'link' => '', 'thumbnails' => 'show_thumbs', 'sticky' => 1, 'date' => 0, 'comments' => 0);
        $instance = wp_parse_args((array) $instance, $defaults); ?>

        <p>
        	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['title']); ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select a Category:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('category'); ?>" class="widefat" name="<?php echo $this->get_field_name('category'); ?>">
				<option value="0" <?php if (!$instance['category']) echo 'selected="selected"'; ?>><?php _e('All', 'mh'); ?></option>
				<?php
				$categories = get_categories(array('type' => 'post'));
				foreach($categories as $cat) {
					echo '<option value="' . $cat->cat_ID . '"';
					if ($cat->cat_ID == $instance['category']) { echo ' selected="selected"'; }
					echo '>' . $cat->cat_name . ' (' . $cat->category_count . ')';
					echo '</option>';
				}
				?>
			</select>
		</p>
		<p>
        	<label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Multiple Categories Filter by ID:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['cats']); ?>" name="<?php echo $this->get_field_name('cats'); ?>" id="<?php echo $this->get_field_id('cats'); ?>" />
	    </p>
		<p>
        	<label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Filter Posts by Tags (e.g. lifestyle):', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['tags']); ?>" name="<?php echo $this->get_field_name('tags'); ?>" id="<?php echo $this->get_field_id('tags'); ?>" />
	    </p>
	    <p>
        	<label for="<?php echo $this->get_field_id('postcount'); ?>"><?php _e('Limit Post Number:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['postcount']); ?>" name="<?php echo $this->get_field_name('postcount'); ?>" id="<?php echo $this->get_field_id('postcount'); ?>" />
	    </p>
	    <p>
        	<label for="<?php echo $this->get_field_id('offset'); ?>"><?php _e('Skip Posts (Offset):', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['offset']); ?>" name="<?php echo $this->get_field_name('offset'); ?>" id="<?php echo $this->get_field_id('offset'); ?>" />
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Post Order:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('order'); ?>" class="widefat" name="<?php echo $this->get_field_name('order'); ?>">
				<option value="date" <?php if ($instance['order'] == "date") { echo "selected='selected'"; } ?>><?php _e('Latest Posts', 'mh') ?></option>
				<option value="rand" <?php if ($instance['order'] == "rand") { echo "selected='selected'"; } ?>><?php _e('Random Posts', 'mh') ?></option>
				<option value="comment_count" <?php if ($instance['order'] == "comment_count") { echo "selected='selected'"; } ?>><?php _e('Popular Posts', 'mh') ?></option>
			</select>
        </p>
        <p>
	    	<label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e('Display Excerpts:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('excerpt'); ?>" class="widefat" name="<?php echo $this->get_field_name('excerpt'); ?>">
				<option value="first" <?php if ($instance['excerpt'] == "first") { echo "selected='selected'"; } ?>><?php _e('Excerpt for first Post', 'mh') ?></option>
				<option value="all" <?php if ($instance['excerpt'] == "all") { echo "selected='selected'"; } ?>><?php _e('Excerpt for all Posts', 'mh') ?></option>
				<option value="none" <?php if ($instance['excerpt'] == "none") { echo "selected='selected'"; } ?>><?php _e('No Excerpts', 'mh') ?></option>
			</select>
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('excerpt_length'); ?>"><?php _e('Excerpt Character Limit:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['excerpt_length']); ?>" name="<?php echo $this->get_field_name('excerpt_length'); ?>" id="<?php echo $this->get_field_id('excerpt_length'); ?>" />
	    </p>
	    <p>
        	<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link Title to custom URL (optional):', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_url($instance['link']); ?>" name="<?php echo $this->get_field_name('link'); ?>" id="<?php echo $this->get_field_id('link'); ?>" />
        </p>
        <p>
	    	<label for="<?php echo $this->get_field_id('thumbnails'); ?>"><?php _e('Thumbnails:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('thumbnails'); ?>" class="widefat" name="<?php echo $this->get_field_name('thumbnails'); ?>">
				<option value="show_thumbs" <?php if ($instance['thumbnails'] == "show_thumbs") { echo "selected='selected'"; } ?>><?php _e('Show all Thumbnails', 'mh') ?></option>
				<option value="hide_thumbs" <?php if ($instance['thumbnails'] == "hide_thumbs") { echo "selected='selected'"; } ?>><?php _e('Hide all Thumbnails', 'mh') ?></option>
				<option value="hide_large" <?php if ($instance['thumbnails'] == "hide_large") { echo "selected='selected'"; } ?>><?php _e('Hide only large Thumbnails', 'mh') ?></option>
				<option value="hide_small" <?php if ($instance['thumbnails'] == "hide_small") { echo "selected='selected'"; } ?>><?php _e('Hide only small Thumbnails', 'mh') ?></option>
			</select>
        </p>
        <p>
      		<input id="<?php echo $this->get_field_id('sticky'); ?>" name="<?php echo $this->get_field_name('sticky'); ?>" type="checkbox" value="1" <?php checked('1', $instance['sticky']); ?>/>
	  		<label for="<?php echo $this->get_field_id('sticky'); ?>"><?php _e('Ignore Sticky Posts', 'mh'); ?></label>
    	</p>
    	<p>
      		<input id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" type="checkbox" value="1" <?php checked('1', $instance['date']); ?>/>
	  		<label for="<?php echo $this->get_field_id('date'); ?>"><?php _e('Hide Date', 'mh'); ?></label>
    	</p>
    	<p>
      		<input id="<?php echo $this->get_field_id('comments'); ?>" name="<?php echo $this->get_field_name('comments'); ?>" type="checkbox" value="1" <?php checked('1', $instance['comments']); ?>/>
	  		<label for="<?php echo $this->get_field_id('comments'); ?>"><?php _e('Hide Comment Count', 'mh'); ?></label>
    	</p><?php
    }
}

?>