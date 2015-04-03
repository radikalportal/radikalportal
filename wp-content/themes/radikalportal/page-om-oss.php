<?php get_header(); ?>

<div class="row">
<div class="col-md-offset-1 col-md-10 main-content">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<div class="page-header">
	<h1><?php the_title(); ?></h1>
</div>

<?php the_content(); ?>

<div class="page-header">
	<h2>Redaksjonen</h2>
</div>

<?php

$redaksjonsmedlemmer = get_post_custom_values('redaksjonsmedlemmer');

if (isset($redaksjonsmedlemmer[0])) :

	$redaksjonsmedlemmer = unserialize($redaksjonsmedlemmer[0]);

	foreach (get_users(array('include' => $redaksjonsmedlemmer,
							 'orderby' => 'display_name',
							 'order'   => 'ASC')) as $key => $redaksjonsmedlem) :

		$userdata = get_userdata($redaksjonsmedlem->ID);

		echo '<div class="row">';
		echo '<div class="pull-left" style="margin: 10px;">';

		if (userphoto_exists($redaksjonsmedlem->ID)) {
			userphoto(
				$redaksjonsmedlem->ID,
				'',
				'',
				array(),
				get_template_directory_uri() . '/img/anon.gif'
			);
		} else {
			echo '<img class="img-rounded" src="' . get_template_directory_uri() . '/img/anon.gif">';
		}

		echo '</div>';
		echo '<div class="col-md-8">';

		echo "<h3>" . $userdata->display_name . "</h3>";
		echo "<p>" . $userdata->user_description . "</p>";

		echo '</div>';
		echo '</div>';

	endforeach;
endif;

?>

<hr>

<div class="row">
	<h2>Finn oss på</h2>
	<div class="col-md-6">
		<h3>Facebook</h3>
		<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fradikalportal&amp;width=292&amp;height=62&amp;show_faces=false&amp;colorscheme=light&amp;stream=false&amp;border_color=%23ffffff&amp;header=false&amp;appId=446811972038512" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:62px;" allowTransparency="true"></iframe>
	</div>
	<div class="col-md-6">
		<h3>Twitter</h3>
		<a href="https://twitter.com/radikalportal" class="twitter-follow-button" data-show-count="false" data-lang="no" data-size="large">Følg @radikalportal</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</div>
</div>

<?php break; ?>
<?php endwhile; else: ?>
	<p>Fant ingenting her.</p>
<?php endif; ?>

</div>
</div>

<?php get_footer(); ?>
