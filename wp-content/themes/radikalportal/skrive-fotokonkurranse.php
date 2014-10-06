<?php
/*
Template Name: Skrive-/Fotokonkurranse
*/
?>

<?php get_header(); ?>

<div class="row">
	<div class="col-md-offset-1 col-md-10 main-content">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<img src="/wp-content/themes/radikalportal/img/skrive-fotokonkurranse-innhold.jpg" alt="Skrive-/Fotokonkurranse" style="width: 100%;">

<?php endwhile; else: ?>
	<p>Fant ingenting her.</p>
<?php endif; ?>

	</div>
</div> <!-- /.row -->

<?php get_footer(); ?>
