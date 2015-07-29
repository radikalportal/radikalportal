<?php

/***** MH Affiliate Widget *****/

class mh_affiliate_widget extends WP_Widget {
    function __construct() {
		parent::__construct(
			'mh_affiliate', esc_html__('MH Affiliate Widget', 'mh'),
			array('classname' => 'mh_affiliate', 'description' => esc_html__('MH Affiliate Widget to earn money by promoting WordPress themes by MH Themes.', 'mh'))
		);
	}
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $mh_username = empty($instance['mh_username']) ? 'MHthemes' : $instance['mh_username'];

        echo $before_widget;

        if (!empty($title)) { echo $before_title . esc_attr($title) . $after_title; } ?>
       	<a href="https://creativemarket.com/MHthemes/?u=<?php echo esc_attr($mh_username); ?>" target="_blank" title="Premium Magazine WordPress Themes by MH Themes" rel="nofollow"><img src="<?php echo get_template_directory_uri() . '/images/mh_magazine_300x250.png' ?>" alt="MH Magazine WordPress Theme" /></a> <?php

        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['mh_username'] = sanitize_text_field($new_instance['mh_username']);
        return $instance;
    }
    function form($instance) {
        $defaults = array('title' => 'WordPress Magazine Theme', 'mh_username' => '');
        $instance = wp_parse_args((array) $instance, $defaults); ?>

        <p>
        	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['title']); ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
	    	<label for="<?php echo $this->get_field_id('mh_username'); ?>"><?php _e('Creative Market Username:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['mh_username']); ?>" name="<?php echo $this->get_field_name('mh_username'); ?>" id="<?php echo $this->get_field_id('mh_username'); ?>" />
	    </p>
        <p><?php echo __('With this widget you can earn money by promoting WordPress themes by MH Themes. If you do not have a Creative Market Username yet, please visit our', 'mh') . ' <a href="' . esc_url('http://www.mhthemes.com/affiliates/') . '" target="_blank">' . __('infopage for affiliates', 'mh'). '</a>'; ?>.</p> <?php
    }
}

?>