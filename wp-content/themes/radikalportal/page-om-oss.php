<?php get_header(); ?>

<div class="row">
<div class="span19">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<div class="page-header">
	<h1><?php the_title(); ?></h1>
</div>

<?php the_content(); ?>

<div class="page-header">
	<h2>Redaksjonen</h2>
</div>

<?php

// Redaksjonens user ids.
$redaksjon = array(
	235,  // Erling Westenvik
	121,  // Ingvar Kolbjørnsen
	 10,  // Irene Kosberg Skagestad
	 20,  // Jenny Dahl Bakken
	 76,  // Joakim Møllersen
	  6,  // John Y. Jones
 	 30,  // Julie Holm
	 21,  // Linda Skjold Oksnes
	195,  // Macel Ingles
	226,  // Magnus Eriksson
	225,  // Marek Jasinski
	 22,  // Olav Elgvin
	 13,  // Oscar Dybedahl
	 16,  // Vegard Velle
	 27,  // Wibeke Bergheim
);

foreach ($redaksjon as $r) {
	$userdata = get_userdata($r);

	echo '<div class="row">';
	echo '<div class="span2" style="padding-top: 8px; margin-bottom: 20px;">';

	if (userphoto_exists($r)) {
		userphoto(
			$r,
			'',
			'',
			array('class' => 'img-rounded'),
			get_template_directory_uri() . '/img/anon.gif'
		);
	} else {
		echo '<img class="img-rounded" src="/wp-content/themes/radikalportal/img/anon.gif">';
	}

	echo '</div>';
	echo '<div class="span16">';

	echo "<h3>" . $userdata->display_name . "</h3>";
	echo "<p>" . $userdata->user_description . "</p>";

	echo '</div>';
	echo '</div>';
}

?>
<!--
<h2>Kortnyttredaksjoner:</h2>
<p>Antirasisme: Macel Ingles, Åsne Hagen, Odd Arild Viste, Christian Stokke, Ahmed Fouikri</p>
<p>Miljø og klima: Björn Fröhlich, Hallgeir Opdal, Linda Skjold Oksnes</p>
<p>Antikrig: Oscar Dybedahl, Johannes Wilm, Gunnar Øyro, Aslak Storaker</p>
<p>Feminisme og kjønn: Jenny Dahl Bakken, Camilla Kitty Karlsen</p>
<p>Arbeidsliv: Wibeke Bergheim, Petter Torp, Anders Hamre Sveen, Nina Skranefjell, Lars Berge</p>
-->
<hr>


<div>
	<h2>Finn oss på</h2>
</div>

<div class="row">
	<div class="span9">
		<h3>Facebook</h3>
		<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fradikalportal&amp;width=292&amp;height=62&amp;show_faces=false&amp;colorscheme=light&amp;stream=false&amp;border_color=%23ffffff&amp;header=false&amp;appId=446811972038512" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:62px;" allowTransparency="true"></iframe>
	</div>
	<div class="span9">
		<h3>Twitter</h3>
		<a href="https://twitter.com/radikalportal" class="twitter-follow-button" data-show-count="false" data-lang="no" data-size="large">Følg @radikalportal</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</div>
</div>

<br>
<br>

<?php break; ?>
<?php endwhile; else: ?>
	<p>Fant ingenting her. Sorry :(</p>
<?php endif; ?>

</div>
</div>

<?php get_footer(); ?>
