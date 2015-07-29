<?php $options = mh_theme_options(); ?>
<?php get_header(); ?>
<div class="mh-wrapper clearfix">
	<div class="mh-main clearfix">
		<div id="main-content" class="mh-loop mh-content">
			<?php mh_before_page_content(); ?>
			<?php mh_author_box(); ?>
			<?php if (have_posts()) { ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php get_template_part('content', 'loop-' . $options['loop_layout']); ?>
				<?php endwhile; ?>
				<?php mh_pagination(); ?>
			<?php } else {  ?>
				<?php get_template_part('content', 'none'); ?>
			<?php } ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
    <?php mh_second_sb(); ?>
</div>
<?php get_footer(); ?>