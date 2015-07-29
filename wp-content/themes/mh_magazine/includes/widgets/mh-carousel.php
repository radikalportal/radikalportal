<?php

/***** MH Carousel Widget (Homepage) *****/

class mh_carousel_hp_widget extends WP_Widget {
    function __construct() {
		parent::__construct(
			'mh_carousel_hp', esc_html__('MH Carousel Widget (Homepage)', 'mh'),
			array('classname' => 'mh_carousel_hp', 'description' => esc_html__('Carousel widget for use on homepage template.', 'mh'))
		);
	}
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $category = isset($instance['category']) ? $instance['category'] : '';
        $cats = empty($instance['cats']) ? '' : $instance['cats'];
        $tags = empty($instance['tags']) ? '' : $instance['tags'];
        $postcount = empty($instance['postcount']) ? '10' : $instance['postcount'];
        $offset = empty($instance['offset']) ? '' : $instance['offset'];
        $order = isset($instance['order']) ? $instance['order'] : 'date';
        $layout = isset($instance['layout']) ? $instance['layout'] : 'layout1';
        $sticky = isset($instance['sticky']) ? $instance['sticky'] : 1;

        if ($cats) {
	    	$category = $category . ', ' . $cats;
        }

        echo $before_widget;
        if (!empty( $title)) { echo '<h4 class="widget-title carousel-title-' . $layout . '">' . esc_attr($title) . '</h4>'; } ?>
        <div id="carousel-<?php echo rand(1, 9999); ?>" class="flexslider <?php echo 'carousel-' . $layout; ?>">
	        <ul class="slides"><?php
				$args = array('posts_per_page' => $postcount, 'cat' => $category, 'tag' => $tags, 'offset' => $offset, 'orderby' => $order, 'ignore_sticky_posts' => $sticky);
				$carousel_loop = new WP_Query($args);
				while ($carousel_loop->have_posts()) : $carousel_loop->the_post(); ?>
					<li class="carousel-item">
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php if (has_post_thumbnail()) { the_post_thumbnail('carousel'); } else { echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_174x98.png' . '" alt="No Picture" />'; } ?>
						</a>
						<div class="caption">
							<?php $category = get_the_category(); echo $category[0]->cat_name; ?>
						</div>
						<?php if ($layout == 'layout2') { ?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<h3 class="carousel-item-title">
									<?php the_title(); ?>
								</h3>
							</a>
						<?php } ?>
					</li><?php
				endwhile; wp_reset_postdata(); ?>
			</ul>
		</div><?php
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
        $instance['layout'] = strip_tags($new_instance['layout']);
        $instance['sticky'] = isset($new_instance['sticky']) ? strip_tags($new_instance['sticky']) : '';
        return $instance;
    }
    function form($instance) {
        $defaults = array('title' => '', 'category' => '', 'cats' => '', 'tags' => '', 'postcount' => '10', 'offset' => '0', 'order' => 'date', 'layout' => 'layout1', 'sticky' => 1);
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
	    	<label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Carousel Layout:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('layout'); ?>" class="widefat" name="<?php echo $this->get_field_name('layout'); ?>">
				<option value="layout1" <?php if ($instance['layout'] == "layout1") { echo "selected='selected'"; } ?>>Layout 1</option>
				<option value="layout2" <?php if ($instance['layout'] == "layout2") { echo "selected='selected'"; } ?>>Layout 2</option>
			</select>
        </p>
        <p>
      		<input id="<?php echo $this->get_field_id('sticky'); ?>" name="<?php echo $this->get_field_name('sticky'); ?>" type="checkbox" value="1" <?php checked('1', $instance['sticky']); ?>/>
	  		<label for="<?php echo $this->get_field_id('sticky'); ?>"><?php _e('Ignore Sticky Posts', 'mh'); ?></label>
    	</p><?php
    }
}

?>