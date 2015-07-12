<?php
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
query_posts('cat=2111&paged=' . $paged);
?>

<?php get_header(); ?>

<div class="row">

    <div class="col-md-3 hidden-xs hidden-sm">
        <?php get_sidebar(left); ?>
    </div>

    <div id="main-content" class="main-content col-md-9">
        <div class="page-header">
            <h1><?php the_title(); ?></h1>
        </div>

        <div id="primary" class="content-area">
            <div id="content" class="site-content" role="main">

                <?php
                if ( have_posts() ) :
                // Start the Loop.
                $first = true;
                while ( have_posts() ) : the_post();

                /*
                 * Include the post format-specific template for the content. If you want to
                 * use this in a child theme, then include a file called called content-___.php
                 * (where ___ is the post format) and that will be used instead.
                 */
                if ($first) {
                    get_template_part( 'content-oslo-featured', get_post_format() );
                } else {
                    get_template_part( 'content-oslo', get_post_format() );
                }
                $first = false;

                endwhile;

                get_template_part( 'pagination' );

                else :
                         // If no content, include the "No posts found" template.
                         get_template_part( 'content', 'none' );

                endif;
                ?>

            </div><!-- #content -->
        </div><!-- #primary -->
    </div><!-- #main-content -->
</div>

<?php get_footer(); ?>
