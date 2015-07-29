<?php

/***** MH Advertising Widget *****/

class mh_advertising_widget extends WP_Widget {
    function __construct() {
		parent::__construct(
			'mh_advertising', esc_html__('MH Advertising Widget', 'mh'),
			array('classname' => 'mh_advertising', 'description' => esc_html__('MH Advertising Widget to display ads in widget locations of your choice.', 'mh'))
		);
	}
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $mh_adtype = isset($instance['mh_adtype']) ? $instance['mh_adtype'] : 'sb';
        $mh_ad1 = empty($instance['mh_ad1']) ? '' : $instance['mh_ad1'];
        $mh_ad2 = empty($instance['mh_ad2']) ? '' : $instance['mh_ad2'];
        $mh_ad3 = empty($instance['mh_ad3']) ? '' : $instance['mh_ad3'];
        $mh_ad4 = empty($instance['mh_ad4']) ? '' : $instance['mh_ad4'];

        echo $before_widget;

        if (!empty($title)) { echo $before_title . esc_attr($title) . $after_title; }

        echo '<div class="ad-widget ad-' . esc_attr($mh_adtype) . ' clearfix">'. "\n";
        if ($mh_ad1) {
	    	echo '<div class="ad-item ad1">' . $mh_ad1 . '</div>'. "\n";
        }
        if ($mh_ad2) {
        	echo '<div class="ad-item ad2">' . $mh_ad2 . '</div>'. "\n";
        }
        if ($mh_ad3) {
        	echo '<div class="ad-item ad3">' . $mh_ad3 . '</div>'. "\n";
        }
        if ($mh_ad4) {
        	echo '<div class="ad-item ad4">' . $mh_ad4 . '</div>'. "\n";
        }
        echo '</div>'. "\n";

        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['mh_adtype'] = strip_tags($new_instance['mh_adtype']);
        $instance['mh_ad1'] = $new_instance['mh_ad1'];
        $instance['mh_ad2'] = $new_instance['mh_ad2'];
        $instance['mh_ad3'] = $new_instance['mh_ad3'];
        $instance['mh_ad4'] = $new_instance['mh_ad4'];
        return $instance;
    }
    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => __('Advertising', 'mh'), 'mh_adtype' => 'sb', 'mh_ad1' => '', 'mh_ad2' => '', 'mh_ad3' => '', 'mh_ad4' => '')); ?>

        <p>
        	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['title']); ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
	    	<label for="<?php echo $this->get_field_id('mh_adtype'); ?>"><?php _e('Banner Size:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('mh_adtype'); ?>" name="<?php echo $this->get_field_name('mh_adtype'); ?>" type="text">
				<option value="sb" <?php if ($instance['mh_adtype'] == "sb") { echo "selected='selected'"; } ?>>4 x Square Button (125x125px)</option>
				<option value="other" <?php if ($instance['mh_adtype'] == "other") { echo "selected='selected'"; } ?>>Other Format</option>
			</select>
        </p>
        <p>
	    	<label for="<?php echo $this->get_field_id('mh_ad1'); ?>"><?php _e('Ad Code', 'mh'); ?> 1:</label>
	    	<textarea cols="60" rows="3" style="width: 100%;" placeholder="Enter ad code or any HTML code" name="<?php echo $this->get_field_name('mh_ad1'); ?>" id="<?php echo $this->get_field_id('mh_ad1'); ?>"><?php echo esc_attr($instance['mh_ad1']); ?></textarea>
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id('mh_ad2'); ?>"><?php _e('Ad Code', 'mh'); ?> 2:</label>
	    	<textarea cols="60" rows="3" style="width: 100%;" placeholder="Enter ad code or any HTML code" name="<?php echo $this->get_field_name('mh_ad2'); ?>" id="<?php echo $this->get_field_id('mh_ad2'); ?>"><?php echo esc_attr($instance['mh_ad2']); ?></textarea>
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id('mh_ad3'); ?>"><?php _e('Ad Code', 'mh'); ?> 3:</label>
	    	<textarea cols="60" rows="3" style="width: 100%;" placeholder="Enter ad code or any HTML code" name="<?php echo $this->get_field_name('mh_ad3'); ?>" id="<?php echo $this->get_field_id('mh_ad3'); ?>"><?php echo esc_attr($instance['mh_ad3']); ?></textarea>
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id('mh_ad4'); ?>"><?php _e('Ad Code', 'mh'); ?> 4:</label>
	    	<textarea cols="60" rows="3" style="width: 100%;" placeholder="Enter ad code or any HTML code" name="<?php echo $this->get_field_name('mh_ad4'); ?>" id="<?php echo $this->get_field_id('mh_ad4'); ?>"><?php echo esc_attr($instance['mh_ad4']); ?></textarea>
	    </p> <?php
    }
}

?>