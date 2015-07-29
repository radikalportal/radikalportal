<?php get_header(); ?>
<div class="mh-wrapper clearfix">
	<div class="mh-main">
    	<div id="main-content" class="mh-content"><?php
    		if (have_posts()) :
    			while (have_posts()) : the_post();
					mh_before_page_content();
					get_template_part('content', 'page');
				endwhile;
				get_template_part('comments', 'pages');
            endif; ?>
        </div>
		<?php get_sidebar(); ?>
    </div>
    <?php mh_second_sb(); ?>
</div>
<?php get_footer(); ?>