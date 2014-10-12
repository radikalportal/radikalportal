<?php get_header(); ?>

<div class="row">
	<div class="col-md-12">
		<h1 class="page-header"><?php the_title(); ?></h1>
		<img src="<?= wp_get_attachment_url(); ?>">
	</div>
</div> <!--/.row -->

<?php get_footer(); ?>
