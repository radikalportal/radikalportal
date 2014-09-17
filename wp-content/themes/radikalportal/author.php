<?php get_header(); ?>

<?php

$curauth = (isset($_GET['author_name']))
	? get_user_by('slug', $author_name)
	: get_userdata(intval($author));

?>

<div class="row">
<div class="col-md-3 hidden-xs hidden-sm">
	<?php get_sidebar(left); ?>
</div>
<div class="col-md-9 main-content">

<div class="row">
	<div class="col-md-2">

<?php
if (userphoto_exists($author)) {
	userphoto(
		$author,
		'',
		'',
		array(),
		get_template_directory_uri() . '/img/anon.gif'
	);
} else {
	echo '<img class="img-rounded" src="/wp-content/themes/radikalportal/img/anon.gif">';
}
?>

	</div>
	<div class="col-md-10">

<h3><?php echo $curauth->display_name; ?></h3>
<p><?php echo $curauth->description; ?></p>

	</div>
</div>

<div class="page-header">
	<h3>Artikler</h3>
</div>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="post row">
	<div class="col-md-12">
		<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
		<div class="entry">
			<?php the_excerpt(); ?>
		</div>
	</div>
</div> <!--/.post -->
<?php endwhile; else: ?>
	<p>Fant ingenting her.</p>
<?php endif; ?>

</div>
</div> <!--/.row -->

<?php get_footer(); ?>
