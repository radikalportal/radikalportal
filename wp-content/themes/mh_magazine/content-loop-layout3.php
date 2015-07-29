<?php /* Loop Template Layout 3 used for index/archive/search */ ?>
<?php $options = mh_theme_options(); ?>
<article <?php post_class('loop-wrap clearfix'); ?>>
	<div class="loop-thumb">
		<?php if ($options['sidebars'] == 'no') { ?>
			<a href="<?php the_permalink()?>">
				<?php if (has_post_thumbnail()) { the_post_thumbnail('slider'); } else { echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_940x400.png' . '" alt="No Picture" />'; } ?>
			</a>
		<?php } else { ?>
			<a href="<?php the_permalink()?>">
				<?php if (has_post_thumbnail()) { the_post_thumbnail('content'); } else { echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_620x264.png' . '" alt="No Picture" />'; } ?>
			</a>
		<?php } ?>
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
	<?php mh_loop_meta(); ?>
</article>