<?php /* Loop Template Layout 1 used for index/archive/search */ ?>
<?php $options = mh_theme_options(); ?>
<article <?php post_class('loop-wrap clearfix'); ?>>
	<div class="loop-thumb">
		<a href="<?php the_permalink(); ?>">
			<?php if (has_post_thumbnail()) { the_post_thumbnail('full'); } else { echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_174x131.png' . '" alt="No Picture" />'; } ?>
		</a>
	</div>
	<header class="loop-header">
		<h3 class="loop-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h3>
		<?php mh_loop_meta(); ?>
	</header>
	<?php mh_excerpt($options['excerpt_length']); ?>
</article>
