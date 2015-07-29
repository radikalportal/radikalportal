<?php /* Template Name: Contact */ ?>
<?php $options = mh_theme_options(); ?>
<?php get_header(); ?>
<div class="mh-wrapper clearfix">
	<div class="mh-main">
    	<div id="main-content" class="mh-content"><?php
    		if (have_posts()) :
    			while (have_posts()) : the_post();
					mh_before_page_content(); ?>
					<div <?php post_class(); ?>>
						<div class="entry clearfix">
							<?php the_content(); ?>
						</div>
					</div><?php
				endwhile;
            endif; ?>
        </div>
        <?php if ($options['sidebars'] != 'no') { ?>
        	<aside class="mh-sidebar">
    			<?php dynamic_sidebar('contact'); ?>
			</aside>
		<?php } ?>
    </div>
    <?php if ($options['sidebars'] == 'two') { ?>
    	<aside class="mh-sidebar-2 sb-right">
    		<?php dynamic_sidebar('contact-2'); ?>
		</aside>
    <?php } ?>
</div>
<?php get_footer(); ?>