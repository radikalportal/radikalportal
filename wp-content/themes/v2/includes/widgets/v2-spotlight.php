<?php

/***** MH Spotlight Widget (Homepage) *****/

class v2_spotlight_hp_widget extends WP_Widget {
    function __construct() {
		parent::__construct(
			'v2_spotlight_hp', esc_html__('v2 Spotlight Widget (Homepage)', 'v2'),
			array('classname' => 'mh_spotlight_hp', 'description' => esc_html__('Spotlight / Featured widget for use on homepage template.', 'mh'))
		);
	}
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $category = isset($instance['category']) ? $instance['category'] : '';
        $cats = empty($instance['cats']) ? '' : $instance['cats'];
        $tags = empty($instance['tags']) ? '' : $instance['tags'];
        $offset = empty($instance['offset']) ? '' : $instance['offset'];
        $order = isset($instance['order']) ? $instance['order'] : 'date';
        $width = isset($instance['width']) ? $instance['width'] : 'normal_sl';
        $excerpt_length = empty($instance['excerpt_length']) ? '175' : $instance['excerpt_length'];
        $excerpt = isset($instance['excerpt']) ? $instance['excerpt'] : 0;
        $meta = isset($instance['meta']) ? $instance['meta'] : 0;
        $date = isset($instance['date']) ? $instance['date'] : 0;
		$comments = isset($instance['comments']) ? $instance['comments'] : 0;

		if ($cats) {
	    	$category = $category . ', ' . $cats;
        }

        echo $before_widget; ?>
		<article class="spotlight"><?php
		$args = array('posts_per_page' => 1, 'cat' => $category, 'tag' => $tags, 'offset' => $offset, 'orderby' => $order, 'ignore_sticky_posts' => 1);
		$spotlight_loop = new WP_Query($args);
		while ($spotlight_loop->have_posts()) : $spotlight_loop->the_post(); ?>
			<?php if ($title) { ?>
				<div class="sl-caption"><?php echo esc_attr($title); ?></div>
			<?php } ?>
			<div class="sl-thumb">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php
					if (has_post_thumbnail()) {
						if ($width == 'normal_sl') {
							the_post_thumbnail('spotlight');
						} else {
							the_post_thumbnail('slider');
						}
					} else {
						if ($width == 'normal_sl') {
							echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_580x326.png' . '" alt="No Picture" />';
						} else {
							echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_940x400.png' . '" alt="No Picture" />';
						}
					} ?>
				</a>
			</div>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><h2 class="sl-title"><?php the_title(); ?></h2></a>
			<?php if ($meta == 0) { ?>
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
			<?php } ?>
			<?php if ($excerpt == 0) { ?>
				<?php mh_excerpt($excerpt_length); ?>
			<?php }
		endwhile; wp_reset_postdata(); ?>
		</article><?php
        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['category'] = absint($new_instance['category']);
        $instance['cats'] = sanitize_text_field($new_instance['cats']);
        $instance['tags'] = sanitize_text_field($new_instance['tags']);
        $instance['offset'] = absint($new_instance['offset']);
        $instance['order'] = strip_tags($new_instance['order']);
        $instance['width'] = strip_tags($new_instance['width']);
        $instance['excerpt_length'] = absint($new_instance['excerpt_length']);
        $instance['excerpt'] = isset($new_instance['excerpt']) ? strip_tags($new_instance['excerpt']) : '';
        $instance['meta'] = isset($new_instance['meta']) ? strip_tags($new_instance['meta']) : '';
        $instance['comments'] = isset($new_instance['comments']) ? strip_tags($new_instance['comments']) : '';
        return $instance;
    }
    function form($instance) {
        $defaults = array('title' => __('In the spotlight', 'mh'), 'category' => '', 'cats' => '', 'tags' => '', 'offset' => '0', 'order' => 'date', 'width' => 'normal_sl', 'excerpt_length' => '175', 'excerpt' => 0, 'meta' => 0, 'comments' => 0);
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
	    	<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Image size:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('width'); ?>" class="widefat" name="<?php echo $this->get_field_name('width'); ?>">
				<option value="normal_sl" <?php if ($instance['width'] == "normal_sl") { echo "selected='selected'"; } ?>>580x326px</option>
				<option value="large_sl" <?php if ($instance['width'] == "large_sl") { echo "selected='selected'"; } ?>>940x400px</option>
			</select>
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('excerpt_length'); ?>"><?php _e('Excerpt Character Limit:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['excerpt_length']); ?>" name="<?php echo $this->get_field_name('excerpt_length'); ?>" id="<?php echo $this->get_field_id('excerpt_length'); ?>" />
	    </p>
        <p>
      		<input id="<?php echo $this->get_field_id('excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>" type="checkbox" value="1" <?php checked('1', $instance['excerpt']); ?>/>
	  		<label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e('Disable Excerpt', 'mh'); ?></label>
    	</p>
    	<p>
      		<input id="<?php echo $this->get_field_id('meta'); ?>" name="<?php echo $this->get_field_name('meta'); ?>" type="checkbox" value="1" <?php checked('1', $instance['meta']); ?>/>
	  		<label for="<?php echo $this->get_field_id('meta'); ?>"><?php _e('Disable Post Meta', 'mh'); ?></label>
    	</p>
    	<p>
      		<input id="<?php echo $this->get_field_id('comments'); ?>" name="<?php echo $this->get_field_name('comments'); ?>" type="checkbox" value="1" <?php checked('1', $instance['comments']); ?>/>
	  		<label for="<?php echo $this->get_field_id('comments'); ?>"><?php _e('Disable Comments', 'mh'); ?></label>
    	</p><?php
    }
}

?>