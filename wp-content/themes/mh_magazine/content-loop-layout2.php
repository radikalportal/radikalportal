<?php /* Loop Template Layout 2 used for index/archive/search */ ?>
<?php $options = mh_theme_options(); ?>
<article <?php post_class('loop-wrap'); ?>>
	<div class="clearfix">
		<div class="loop-thumb">
			<a href="<?php the_permalink(); ?>">
				<?php if (has_post_thumbnail()) { the_post_thumbnail('cp_large'); } else { echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_300x225.png' . '" alt="No Picture" />'; } ?>
			</a>
		</div>
		<div class="loop-content">
			<header class="loop-header">
				<h3 class="loop-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark">
						<?php the_title(); ?>
					</a>
				</h3>
			</header>
			<?php mh_excerpt($options['excerpt_length']); ?>
		</div>
	</div>
	<?php mh_loop_meta(); ?>
</article>