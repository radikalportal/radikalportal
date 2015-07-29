<?php

/***** MH Authors Widget *****/

class mh_authors_widget extends WP_Widget {
    function __construct() {
		parent::__construct(
			'mh_authors', esc_html__('MH Authors Widget', 'mh'),
			array('classname' => 'mh_authors', 'description' => esc_html__('MH Authors widget to display a list of authors including the number of published posts.', 'mh'))
		);
	}
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $authorcount = empty($instance['authorcount']) ? '5' : $instance['authorcount'];
        $offset = empty($instance['offset']) ? '' : $instance['offset'];
        $role = isset($instance['role']) ? $instance['role'] : '';
        $orderby = isset($instance['orderby']) ? $instance['orderby'] : 'post_count';
        $order = isset($instance['order']) ? $instance['order'] : 'DESC';
        $avatar_size = isset($instance['avatar_size']) ? $instance['avatar_size'] : '48';

        echo $before_widget;
        if (!empty($title)) { echo $before_title . esc_attr($title) . $after_title; } ?>
        <ul class="user-widget row clearfix"><?php
		$args = array('number' => $authorcount, 'offset' => $offset, 'role' => $role, 'orderby' => $orderby, 'order' => $order);
        $wp_user_query = new WP_User_Query($args);
        $authors = $wp_user_query->get_results();
		if (!empty($authors)) {
			foreach ($authors as $author) {
			$author_ID = $author->ID; ?>
			<li class="uw-wrap clearfix">
				<?php if ($avatar_size != 'no_avatar') { ?>
					<div class="uw-avatar"><a href="<?php echo get_author_posts_url($author_ID); ?>" title="<?php printf(__('Articles by %s', 'mh'), $author->display_name); ?>"><?php echo get_avatar($author_ID, $avatar_size); ?></a></div>
				<?php } ?>
				<div class="uw-text">
					<a href="<?php echo get_author_posts_url($author_ID); ?>" title="<?php printf(__('Articles by %s', 'mh'), $author->display_name); ?>" class="author-name"><?php echo $author->display_name; ?></a>
					<p class="uw-data"><?php printf(_x('published %d articles', 'author post count', 'mh'), count_user_posts($author_ID)); ?></p>
				</div>
			</li><?php
			}
		} else {
			_e('No authors found', 'mh');
		} ?>
		</ul><?php
        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['authorcount'] = absint($new_instance['authorcount']);
        $instance['offset'] = absint($new_instance['offset']);
        $instance['role'] = strip_tags($new_instance['role']);
        $instance['orderby'] = strip_tags($new_instance['orderby']);
        $instance['order'] = strip_tags($new_instance['order']);
        $instance['avatar_size'] = strip_tags($new_instance['avatar_size']);
        return $instance;
    }
    function form($instance) {
        $defaults = array('title' => __('Authors', 'mh'), 'authorcount' => '5', 'offset' => '0', 'role' => '', 'orderby' => 'post_count', 'order' => 'DESC', 'avatar_size' => '48');
        $instance = wp_parse_args((array) $instance, $defaults); ?>

        <p>
        	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['title']); ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('authorcount'); ?>"><?php _e('Limit Author Number:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['authorcount']); ?>" name="<?php echo $this->get_field_name('authorcount'); ?>" id="<?php echo $this->get_field_id('authorcount'); ?>" />
	    </p>
	    <p>
        	<label for="<?php echo $this->get_field_id('offset'); ?>"><?php _e('Skip Authors (Offset):', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['offset']); ?>" name="<?php echo $this->get_field_name('offset'); ?>" id="<?php echo $this->get_field_id('offset'); ?>" />
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id('role'); ?>"><?php _e('User Role:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('role'); ?>" class="widefat" name="<?php echo $this->get_field_name('role'); ?>">
				<option value="" <?php if ($instance['role'] == "") { echo "selected='selected'"; } ?>><?php _e('All Users', 'mh') ?></option>
				<option value="administrator" <?php if ($instance['role'] == "administrator") { echo "selected='selected'"; } ?>><?php _e('Administrator', 'mh') ?></option>
				<option value="editor" <?php if ($instance['role'] == "editor") { echo "selected='selected'"; } ?>><?php _e('Editor', 'mh') ?></option>
				<option value="author" <?php if ($instance['role'] == "author") { echo "selected='selected'"; } ?>><?php _e('Author', 'mh') ?></option>
				<option value="contributor" <?php if ($instance['role'] == "contributor") { echo "selected='selected'"; } ?>><?php _e('Contributor', 'mh') ?></option>
				<option value="subscriber" <?php if ($instance['role'] == "subscriber") { echo "selected='selected'"; } ?>><?php _e('Subscriber', 'mh') ?></option>
			</select>
        </p>
        <p>
	    	<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order Authors by:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat" name="<?php echo $this->get_field_name('orderby'); ?>">
				<option value="post_count" <?php if ($instance['orderby'] == "post_count") { echo "selected='selected'"; } ?>><?php _e('Number of Posts', 'mh') ?></option>
				<option value="display_name" <?php if ($instance['orderby'] == "display_name") { echo "selected='selected'"; } ?>><?php _e('User Name', 'mh') ?></option>
			</select>
        </p>
        <p>
	    	<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('order'); ?>" class="widefat" name="<?php echo $this->get_field_name('order'); ?>">
				<option value="ASC" <?php if ($instance['order'] == "ASC") { echo "selected='selected'"; } ?>><?php _e('Ascending', 'mh') ?></option>
				<option value="DESC" <?php if ($instance['order'] == "DESC") { echo "selected='selected'"; } ?>><?php _e('Descending', 'mh') ?></option>
			</select>
        </p>
        <p>
	    	<label for="<?php echo $this->get_field_id('avatar_size'); ?>"><?php _e('Avatar Size in px:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('avatar_size'); ?>" class="widefat" name="<?php echo $this->get_field_name('avatar_size'); ?>">
				<option value="16" <?php if ($instance['avatar_size'] == "16") { echo "selected='selected'"; } ?>><?php echo '16 x 16'; ?></option>
				<option value="32" <?php if ($instance['avatar_size'] == "32") { echo "selected='selected'"; } ?>><?php echo '32 x 32'; ?></option>
				<option value="48" <?php if ($instance['avatar_size'] == "48") { echo "selected='selected'"; } ?>><?php echo '48 x 48'; ?></option>
				<option value="64" <?php if ($instance['avatar_size'] == "64") { echo "selected='selected'"; } ?>><?php echo '64 x 64'; ?></option>
				<option value="no_avatar" <?php if ($instance['avatar_size'] == "no_avatar") { echo "selected='selected'"; } ?>><?php _e('No Avatars', 'mh') ?></option>
			</select>
        </p><?php
    }
}

?>