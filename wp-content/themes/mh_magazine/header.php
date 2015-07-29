<!DOCTYPE html>
<html class="no-js<?php mh_html_class(); ?>" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if (is_active_sidebar('header')) { ?>
	<aside class="header-widget">
		<?php dynamic_sidebar('header'); ?>
	</aside>
<?php } ?>
<div class="mh-container">
<?php mh_before_header(); ?>
<header class="header-wrap">
	<?php if (has_nav_menu('header_nav')) { ?>
		<nav class="header-nav clearfix">
			<?php wp_nav_menu(array('theme_location' => 'header_nav', 'fallback_cb' => '')); ?>
		</nav>
	<?php } ?>
	<?php mh_logo(); ?>
	<nav class="main-nav clearfix">
		<?php wp_nav_menu(array('theme_location' => 'main_nav')); ?>
	</nav>
	<?php if (has_nav_menu('info_nav')) { ?>
		<nav class="info-nav clearfix">
			<?php wp_nav_menu(array('theme_location' => 'info_nav', 'fallback_cb' => '')); ?>
		</nav>
	<?php } ?>
</header>
<?php mh_after_header(); ?>