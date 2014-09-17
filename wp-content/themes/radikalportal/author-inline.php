<div class="author-inline">
	<div class="row">
	<div class="col-md-12">
		<div class="author-photo pull-left">

<?php
userphoto_the_author_photo('', '',
	array(),
	get_template_directory_uri() . '/img/anon.gif'
);
?>

		</div>

			<?php if (get_the_author_posts() > 1) : ?>
			<div class="author-name"><?php the_author_posts_link(); ?></div>
			<?php else : ?>
			<div class="author-name"><?php the_author(); ?></div>
			<?php endif; ?>

			<div class="author-bio">
			<?php the_author_description(); ?>
			</div>
	</div>
	</div> <!-- /.row -->

<?php
$forfatterids = get_post_custom_values('forfatterid');
if (isset($forfatterids)) :
	foreach ($forfatterids as $key => $value) :
		$userdata = get_userdata($value);
?>

	<div class="row author-inline-vertical-space">
	<div class="col-md-12">

		<div class="author-photo pull-left">
<?php userphoto($value, '', '', array()); ?>
		</div>

		<div class="author-name">
			<?= $userdata->display_name; ?>
		</div>

		<div class="author-bio">
		<?= $userdata->user_description; ?>
		</div>
	</div>
	</div> <!-- /.row -->

<?php endforeach; endif; ?>

</div> <!-- /.author-inline -->
