<?php

/***** Declare WooCommerce Compatibility *****/

add_theme_support('woocommerce');

/***** Register WooCommerce Sidebar *****/

function mh_woocommerce_sb_init() {
	register_sidebar(array('name' => __('WooCommerce', 'mh'), 'id' => 'woocommerce', 'description' => __('Widget area (sidebar) on WooCommerce pages', 'mh'), 'before_widget' => '<div class="sb-widget sb-woocommerce">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
}
add_action('widgets_init', 'mh_woocommerce_sb_init');

/***** Custom WooCommerce Markup *****/

remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

function mh_themes_wrapper_start() { ?>
	<div class="mh-wrapper clearfix">
		<div class="mh-main">
			<div id="main-content" class="mh-content entry"> <?php
}
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
add_action('woocommerce_before_main_content', 'mh_themes_wrapper_start', 10);

function mh_themes_wrapper_end() { ?>
			</div>
			<?php $options = mh_theme_options(); ?>
			<?php if ($options['sidebars'] != 'no') { ?>
			<aside class="mh-sidebar <?php mh_sb_class(); ?>">
	  			<?php dynamic_sidebar('woocommerce'); ?>
	  		</aside>
	  		<?php } ?>
	  	</div>
	  	<?php mh_second_sb(); ?>
  	</div> <?php
}
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_after_main_content', 'mh_themes_wrapper_end', 10);

/***** Load Custom WooCommerce CSS *****/

function mh_woocommerce_css() {
    wp_register_style('mh-woocommerce', get_template_directory_uri() . '/woocommerce/woocommerce.css');
    wp_enqueue_style('mh-woocommerce');
}
add_action('wp_enqueue_scripts', 'mh_woocommerce_css');

?>