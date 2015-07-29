<?php /* Default template for displaying page content. */ ?>
<div <?php post_class(); ?>>
	<div class="entry clearfix">
		<?php dynamic_sidebar('pages-1'); ?>
		<?php the_content(); ?>
	</div>
</div>
<?php dynamic_sidebar('pages-2'); ?>