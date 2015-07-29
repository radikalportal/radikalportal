<?php

/***** MH Comments Widget *****/

class mh_comments_widget extends WP_Widget {
    function __construct() {
		parent::__construct(
			'mh_comments', esc_html__('MH Recent Comments', 'mh'),
			array('classname' => 'mh_comments', 'description' => esc_html__('MH Recent Comments widget to display your recent comments including user avatars.', 'mh'))
		);
	}
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $number = empty($instance['number']) ? '5' : $instance['number'];
        $offset = empty($instance['offset']) ? '' : $instance['offset'];
        $avatar_size = isset($instance['avatar_size']) ? $instance['avatar_size'] : '48';

        echo $before_widget;
        if (!empty($title)) { echo $before_title . esc_attr($title) . $after_title; } ?>
        <ul class="user-widget row clearfix"><?php
		$comments = get_comments(array('number' => $number, 'offset' => $offset, 'status' => 'approve', 'type' => 'comment'));
		if ($comments) {
			foreach ($comments as $comment) { ?>
				<li class="uw-wrap clearfix"><?php
					if ($avatar_size != 'no_avatar') { ?>
						<div class="uw-avatar"><a href="<?php echo get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID; ?>" title="<?php echo $comment->comment_author; ?>"><?php echo get_avatar($comment->comment_author_email, $avatar_size); ?></a></div><?php
					} ?>
					<div class="uw-text"><?php printf(_x('%1$s on %2$s', 'comment widget', 'mh'), $comment->comment_author, ''); ?><a href="<?php echo get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID; ?>" title="<?php echo $comment->comment_author . ' | ' . get_the_title($comment->comment_post_ID); ?>"><?php echo get_the_title($comment->comment_post_ID); ?></a></div>
				</li><?php
			}
		} ?>
        </ul><?php
        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        $instance['offset'] = absint($new_instance['offset']);
        $instance['avatar_size'] = strip_tags($new_instance['avatar_size']);
        return $instance;
    }
    function form($instance) {
        $defaults = array('title' => __('Recent Comments', 'mh'), 'number' => '5', 'offset' => '0', 'avatar_size' => '48');
        $instance = wp_parse_args((array) $instance, $defaults); ?>

        <p>
        	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['title']); ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Limit Comment Number:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['number']); ?>" name="<?php echo $this->get_field_name('number'); ?>" id="<?php echo $this->get_field_id('number'); ?>" />
	    </p>
	    <p>
        	<label for="<?php echo $this->get_field_id('offset'); ?>"><?php _e('Skip Comments (Offset):', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['offset']); ?>" name="<?php echo $this->get_field_name('offset'); ?>" id="<?php echo $this->get_field_id('offset'); ?>" />
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