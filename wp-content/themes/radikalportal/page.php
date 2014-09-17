<?php get_header(); ?>

<div class="row">
	<div class="col-md-3 hidden-xs hidden-sm">
		<?php get_sidebar(left); ?>
	</div>
	<div class="col-md-9 main-content">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div class="page-header">
			<h1><?php the_title(); ?></h1>
		</div>

<?php the_content(); ?>

<?php endwhile; else: ?>
	<p>Fant ingenting her.</p>
<?php endif; ?>

	</div>
</div> <!-- /.row -->

<?php get_footer(); ?>
