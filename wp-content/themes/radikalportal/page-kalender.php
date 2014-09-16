<?php get_header(); ?>                              

<div class="row">
<div class="span5 visible-desktop"><?php get_sidebar(left); ?></div>
<div class="span13">


<div class="page-header">
    <h1><?php the_title(); ?></h1>
</div> 

<?php 
	while ( have_posts() ) : the_post();
        ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="entry-header">
                     
                </header>

                <div class="entry-content calendar">
                    <?php
                    
                    the_content();
                    
                    // WP_Query arguments
                    $args = array(
                        'post_type' => 'am_event',
                        'post_status' => 'publish',
                        'orderby' => 'meta_value',
                        'meta_key' => 'am_startdate',
                        'order' => 'ASC',
                        'meta_query' => array(
                            array(
                            'key' => 'am_enddate',
                            'value' => date('Y-m-d H:i:s', time()), //don't change date format here!
                            'compare' => ">",
                            ),
                        ),
                    );
                    $the_query = new WP_Query($args);

                    // Display the page content and
                    ?>

                    <?php // Display the event table ?>
                    <ul>
                        <?php
                        // The Loop
                        if ($the_query->have_posts()) {
                            while ($the_query->have_posts()) {
                                $the_query->the_post();
                                $postId = $post->ID;

                                
				 echo '<h4 class="title"><a href="' . get_permalink() . '"> ' . get_the_title() . '</a></h4> ';
                                echo '<strong><p>';
                                    am_the_startdate('d/m Y H:i', ' Tidspunkt: ', '</p></strong>');
                                   

                                   
                                   
                                    echo '<p>Sted: ' . am_get_the_venue_list(',', 'multiple') . '</p>';
                                echo '<br>';
                            }
                        }
                        ?>
                    </ul>

                    <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'twentytwelve' ), 'after' => '</div>' ) ); ?>

                </div><!-- .entry-content -->

                <footer class="entry-meta">
                        <?php edit_post_link( __( 'Edit', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?>
                </footer><!-- .entry-meta -->

            </article><!-- #post -->
            
            
            
        <?php endwhile; // end of the loop. ?>

</div>

<div class="span6 visible-desktop">
	<?php get_sidebar(); ?>
</div>

</div>

<?php get_footer(); ?>