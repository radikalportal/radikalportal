<?php /* Default template for displaying attachments. */ ?>
<?php if (is_single()) { ?>
	<article <?php post_class(); ?>>
		<header class="post-header">
			<h1 class="post-title">
				<?php the_title(); ?>
			</h1>
		</header>
		<?php dynamic_sidebar('posts-1'); ?>
		<div class="entry clearfix">
			<?php if (wp_attachment_is_image($post->id)) { ?>
				<?php $att_image = wp_get_attachment_image_src($post->id, 'full'); ?>
				<a href="<?php echo esc_url(wp_get_attachment_url($post->id)); ?>" title="<?php the_title_attribute(); ?>" rel="attachment" target="_blank">
					<img src="<?php echo esc_url($att_image[0]); ?>" width="<?php echo esc_attr($att_image[1]); ?>" height="<?php echo esc_attr($att_image[2]); ?>" class="attachment-medium" alt="<?php echo get_the_title(); ?>" />
				</a>
				<?php if (get_post(get_post_thumbnail_id())->post_excerpt) { ?>
					<p class="wp-caption-text"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>
				<?php } ?>
				<?php if (get_post(get_post_thumbnail_id())->post_content) { ?>
					<p><?php echo get_post(get_post_thumbnail_id())->post_content; ?></p>
				<?php } ?>
			<?php } else { ?>
				<?php the_content(); ?>
			<?php } ?>
		</div>
        <?php dynamic_sidebar('posts-2'); ?>
	</article>
<?php }	?>