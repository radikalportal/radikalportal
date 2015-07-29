<?php get_header(); ?>
<div class="mh-wrapper clearfix">
	<div class="mh-main">
		<div id="main-content" class="mh-content">
			<?php mh_before_page_content(); ?>
			<div class="entry sb-widget">
				<div class="box">
					<p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'mh'); ?></p>
				</div>
				<h4 class="widget-title">
					<?php _e('Search', 'mh'); ?>
				</h4>
				<?php get_search_form(); ?>
			</div>
			<div class="clearfix">
				<div class="hp-sidebar hp-sidebar-left"><?php
					$instance = array('title' => __('Popular Articles', 'mh'), 'postcount' => '5', 'order' => 'comment_count', 'excerpt' => 'first', 'sticky' => 1);
					$args = array('before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>');
					the_widget('mh_custom_posts_widget', $instance , $args); ?>
				</div>
				<div class="hp-sidebar sb-right hp-sidebar-right"><?php
					$instance = array('title' => __('Random Articles', 'mh'), 'postcount' => '5', 'order' => 'rand', 'excerpt' => 'first', 'sticky' => 1);
					$args = array('before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>');
					the_widget('mh_custom_posts_widget', $instance , $args); ?>
				</div>
			</div>
		</div>
		<?php get_sidebar(); ?>
	</div>
	<?php mh_second_sb(); ?>
</div>
<?php get_footer(); ?>