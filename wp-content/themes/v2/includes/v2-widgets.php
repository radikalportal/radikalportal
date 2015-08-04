<?php

/***** Register Widgets *****/

function register_v2_widgets() {
	register_widget('v2_spotlight_hp_widget');
}
add_action('widgets_init', 'register_v2_widgets');

/***** Include Widgets *****/

require_once('widgets/v2-spotlight.php');

?>
