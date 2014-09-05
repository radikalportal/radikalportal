<style>

.author-inline {
	border-bottom: 2px solid #eee;
	border-top: 2px solid #eee;
	margin: 10px 0;
	padding: 7px 0;
}

.author-inline-vertical-space {
	margin-top: 10px;
}

.author-name {
	font-family: "News Cycle", "Arial Narrow Bold", sans-serif;
	font-size: 1.4em;
	font-weight: bold;
	margin: 0 0 0.2em;
}

.author-name, .author-name a {
	color: black;
}

.author-more {
	font-weight: bold;
}

</style>

<div class="author-inline">
	<div class="row">
		<div class="span2">

<?php
userphoto_the_author_photo('', '',
	array('class' => 'img-rounded hidden-phone'),
	get_template_directory_uri() . '/img/anon.gif'
);
?>

		</div>
		<div class="span15">

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
		<div class="span2">

<?php userphoto($value, '', '', array('class' => 'img-rounded hidden-phone')); ?>

		</div>
		<div class="span15">
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
