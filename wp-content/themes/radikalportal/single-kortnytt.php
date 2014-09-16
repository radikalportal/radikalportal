<?php get_header(); ?>

<div class="row">
  <div class="col-md-3 hidden-xs hidden-sm">
    <?php get_sidebar(left); ?>
  </div>

  <div class="col-md-9 main-column">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="post single">
		<div class="thumb">
<?php if (has_post_thumbnail()) { the_post_thumbnail('full'); echo get_post(get_post_thumbnail_id())->post_content; echo "<br>"; echo get_post(get_post_thumbnail_id())->post_excerpt; } ?>
		</div>

	<div class="row">
		<div class="col-md-12">
			<h1><?php the_title(); ?></h1>

			<div class="uppercase"><?php 
			$terms = get_the_terms($post->ID, 'kortnytt_category' );
			if ($terms && ! is_wp_error($terms)) :
				$term_slugs_arr = array();
				foreach ($terms as $term) {
				    $term_slugs_arr[] = $term->name;
				}
				$terms_slug_str = join( " ", $term_slugs_arr);
			endif;
			echo $terms_slug_str;
			?>
			</div>

			<div class="publisert">Publisert <?php the_date(); ?> klokken <?php the_time() ?></div>
		</div>
	</div> <!--/.row -->

<?php get_template_part('sharebar'); ?>

	<div class="row">
		<div class="col-md-12">
			<div class="entry">
				<?php the_content(); ?>
			</div>
		</div>
	</div> <!--/.row -->

</div> <!--/.post -->

<?php endwhile; else: ?>
	<p>Fant ingenting her.</p>
<?php endif; ?>

</div>
</div> <!--/.row -->

<?php get_footer(); ?>
