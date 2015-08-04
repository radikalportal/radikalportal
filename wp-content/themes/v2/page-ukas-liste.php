<?php
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
query_posts('cat=412&paged=' . $paged);
?>

<?php $options = mh_theme_options(); ?>
<?php get_header(); ?>
<div class="mh-wrapper clearfix">
	<div class="mh-main clearfix">
		<div id="main-content" class="mh-loop mh-content"><?php
			mh_before_page_content();
			if (category_description()) { ?>
				<section class="cat-desc">
					<?php echo category_description(); ?>
				</section><?php
			}
			if (have_posts()) {
				while (have_posts()) : the_post();
					get_template_part('content', 'loop-' . $options['loop_layout']);
				endwhile;
				mh_pagination();
			} else {
				get_template_part('content', 'none');
			} ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
	<?php mh_second_sb(); ?>
</div>
<?php get_footer(); ?>
