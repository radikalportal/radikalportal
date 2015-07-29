<?php

/***** MH Slider Widget (Homepage) *****/

class mh_slider_hp_widget extends WP_Widget {
    function __construct() {
		parent::__construct(
			'mh_slider_hp', esc_html__('MH Slider Widget (Homepage)', 'mh'),
			array('classname' => 'mh_slider_hp', 'description' => esc_html__('Slider widget for use on homepage template.', 'mh'))
		);
	}
    function widget($args, $instance) {
        extract($args);
        $category = isset($instance['category']) ? $instance['category'] : '';
        $cats = empty($instance['cats']) ? '' : $instance['cats'];
        $tags = empty($instance['tags']) ? '' : $instance['tags'];
        $postcount = empty($instance['postcount']) ? '5' : $instance['postcount'];
        $offset = empty($instance['offset']) ? '' : $instance['offset'];
        $order = isset($instance['order']) ? $instance['order'] : 'date';
        $width = isset($instance['width']) ? $instance['width'] : 'large';
        $layout = isset($instance['layout']) ? $instance['layout'] : 'layout1';
        $excerpt_length = empty($instance['excerpt_length']) ? '175' : $instance['excerpt_length'];
        $sticky = isset($instance['sticky']) ? $instance['sticky'] : 1;
        $excerpt = isset($instance['excerpt']) ? $instance['excerpt'] : 0;

        if ($cats) {
	    	$category = $category . ', ' . $cats;
        }

        echo $before_widget; ?>
        <section id="slider-<?php echo rand(1, 9999); ?>" class="flexslider <?php echo 'slider-' . $width . ' slider-' . $layout; ?>">
			<ul class="slides"><?php
			$args = array('posts_per_page' => $postcount, 'cat' => $category, 'tag' => $tags, 'offset' => $offset, 'orderby' => $order, 'ignore_sticky_posts' => $sticky);
			$slider = new WP_query($args);
			while ($slider->have_posts()) : $slider->the_post(); ?>
				<li>
				<article class="slide-wrap">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php
						if (has_post_thumbnail()) {
							if ($width == 'large') {
								the_post_thumbnail('slider');
							} else {
								the_post_thumbnail('content');
							}
						} else {
							if ($width == 'large') {
								echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_940x400.png' . '" alt="No Picture" />';
							} else {
								echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_620x264.png' . '" alt="No Picture" />';
							}
						} ?>
					</a>
					<div class="slide-caption">
						<div class="slide-data">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><h2 class="slide-title"><?php the_title(); ?></h2></a>
							<?php if ($excerpt == 0) { ?>
								<?php mh_excerpt($excerpt_length); ?>
							<?php } ?>
						</div>
					</div>
				</article>
				</li><?php
			endwhile; wp_reset_postdata(); ?>
			</ul>
		</section><?php
        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['category'] = absint($new_instance['category']);
        $instance['cats'] = sanitize_text_field($new_instance['cats']);
        $instance['tags'] = sanitize_text_field($new_instance['tags']);
        $instance['postcount'] = absint($new_instance['postcount']);
        $instance['offset'] = absint($new_instance['offset']);
        $instance['order'] = strip_tags($new_instance['order']);
        $instance['width'] = strip_tags($new_instance['width']);
        $instance['layout'] = strip_tags($new_instance['layout']);
        $instance['excerpt_length'] = absint($new_instance['excerpt_length']);
        $instance['sticky'] = isset($new_instance['sticky']) ? strip_tags($new_instance['sticky']) : '';
        $instance['excerpt'] = isset($new_instance['excerpt']) ? strip_tags($new_instance['excerpt']) : '';
        return $instance;
    }
    function form($instance) {
        $defaults = array('category' => '', 'cats' => '', 'tags' => '', 'postcount' => '5', 'offset' => '0', 'order' => 'date', 'width' => 'large', 'layout' => 'layout1', 'excerpt_length' => '175', 'sticky' => 1, 'excerpt' => 0);
        $instance = wp_parse_args((array) $instance, $defaults); ?>

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
	    	<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Slider Image Size:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('width'); ?>" class="widefat" name="<?php echo $this->get_field_name('width'); ?>">
				<option value="normal" <?php if ($instance['width'] == "normal") { echo "selected='selected'"; } ?>>620x264px</option>
				<option value="large" <?php if ($instance['width'] == "large") { echo "selected='selected'"; } ?>>940x400px</option>
			</select>
        </p>
        <p>
	    	<label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Slider Layout:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('layout'); ?>" class="widefat" name="<?php echo $this->get_field_name('layout'); ?>">
				<option value="layout1" <?php if ($instance['layout'] == "layout1") { echo "selected='selected'"; } ?>>Layout 1</option>
				<option value="layout2" <?php if ($instance['layout'] == "layout2") { echo "selected='selected'"; } ?>>Layout 2</option>
			</select>
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('excerpt_length'); ?>"><?php _e('Excerpt Character Limit:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['excerpt_length']); ?>" name="<?php echo $this->get_field_name('excerpt_length'); ?>" id="<?php echo $this->get_field_id('excerpt_length'); ?>" />
	    </p>
        <p>
      		<input id="<?php echo $this->get_field_id('sticky'); ?>" name="<?php echo $this->get_field_name('sticky'); ?>" type="checkbox" value="1" <?php checked('1', $instance['sticky']); ?>/>
	  		<label for="<?php echo $this->get_field_id('sticky'); ?>"><?php _e('Ignore Sticky Posts', 'mh'); ?></label>
    	</p>
    	<p>
      		<input id="<?php echo $this->get_field_id('excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>" type="checkbox" value="1" <?php checked('1', $instance['excerpt']); ?>/>
	  		<label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e('Disable Excerpt', 'mh'); ?></label>
    	</p><?php
    }
}

?>