<?php get_header(); ?>

<?php

$curauth = (isset($_GET['author_name']))
	? get_user_by('slug', $author_name)
	: get_userdata(intval($author));

?>

<div class="row">
<div class="span5 visible-desktop">
	<?php get_sidebar(left); ?>
</div>
<div class="span13">

<div class="row">
	<div class="span3">

<?php
if (userphoto_exists($author)) {
	userphoto(
		$author,
		'',
		'',
		array('class' => 'img-rounded'),
		get_template_directory_uri() . '/img/anon.gif'
	);
} else {
	echo '<img class="img-rounded" src="/wp-content/themes/radikalportal/img/anon.gif">';
}
?>

	</div>
	<div class="span10">

<h3><?php echo $curauth->display_name; ?></h3>
<p><?php echo $curauth->description; ?></p>

	</div>
</div>

<div class="page-header">
	<h3>Artikler</h3>
</div>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="post row">
	<div class="span13">
		<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
		<div class="entry">
			<?php the_excerpt(); ?>
		</div>
	</div>
</div> <!--/.post -->
<?php endwhile; else: ?>
	<p>Fant ingenting her. Sorry :(</p>
<?php endif; ?>

</div>
</div> <!--/.row -->

<?php get_footer(); ?>
