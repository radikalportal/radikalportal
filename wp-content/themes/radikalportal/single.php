<?php get_header(); ?>

<div class="row">
  <div class="col-md-3 hidden-xs hidden-sm">
    <?php get_sidebar(left); ?>
  </div>

  <div class="col-md-9 main-content">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div class="post single">

<?php if (get_the_ID() == 8373) { ?>
<iframe src="http://new.livestream.com/accounts/2698249/events/1996825/player?width=705&height=397&autoPlay=true&mute=false" width="705" height="397" frameborder="0" scrolling="no"> </iframe>
<?php } else { ?>

<div class="thumb">
<?php if (has_post_thumbnail()) { the_post_thumbnail('large'); echo get_post(get_post_thumbnail_id())->post_content; echo "<br>"; echo get_post(get_post_thumbnail_id())->post_excerpt; } ?>
</div>

<?php } ?>

<div class="row">
  <div class="col-md-12">
    <div class="entry-category">
             <?php
        $klartale_eller_skrivekonkurranse = false;
        foreach( get_the_category() as $category) {
          if( $category->cat_name != 'Skjult fra forsiden'
          &&  $category->cat_name != 'Ingen kategori'
          &&  $category->cat_name != 'Romtekst'
          &&  $category->cat_name != 'Romvideo'
          &&  $category->cat_name != 'Romkronikk') {
            if( $klartale_eller_skrivekonkurranse == false) {
              echo $category->cat_name;
            }

            if( $category->cat_name == 'Klar tale'
            ||  $category->cat_name == 'Aktuelt'
            ||  $category->cat_name == 'Skrivekonkurranse') {
              $klartale_eller_skrivekonkurranse = true;
            }
          }
        }
    ?>
    </div>
        | <span class="pubdate"><?php echo get_the_date("d.m.Y"); ?></span>

    <h1><?php the_title(); ?></h1>
  </div>
</div>

<div>

<div class="ingress">

<?php

global $more;    // Declare global $more (before the loop).
$more = 0;       // Set (inside the loop) to display content above the more tag.
the_content('', true);
$more = 1;       // Set (inside the loop) to display content above the more tag.

?>

</div>

      <div class="row">

<div class="col-md-12">
  <?php get_template_part('author', 'inline'); ?>
  <?php get_template_part('sharebar'); ?>
</div>

<div class="col-md-12">
  <div class="entry">

<?php the_content('', true); ?>

<?php
$faktabokser = get_post_custom_values('faktaboks');
if (isset($faktabokser)) {
	foreach ($faktabokser as $key => $value) {
		echo '<div class="well">' . $value . '</div>';
	}
}
?>

    <hr>
  </div> <!-- /.entry -->
</div> <!-- /.span15 -->

<div class="col-md-12">
  <?php get_template_part('sharebar'); ?>
</div>

<div class="col-md-12 hidden-xs">
    <br>
  <div class="hidden-phone"><?php wp_related_posts(); ?></div>
</div>

<div class="col-md-12">
  <h3 class="page-header">Tips oss</h3>
  Har du et tips om en sak du mener vi burde skrive om, eller en lenke vi burde dele på siden så send oss et <a href="http://radikalportal.no/tips-oss/">tips</a>.
</div>

<?php if (!strcmp(get_post_meta($post->ID, 'ingendiskusjon', true), "1") == 0) : ?>
<div class="col-md-12">
  <h3 class="page-header">Diskusjon</h3>
  <div class="well debattregler">
    <p>
      <strong>DEBATTREGLER:</strong>
      <br>
      - Respekter dine meddebattanter og utøv normal folkeskikk<br>
      - Vær saklig og hold deg til tema<br>
      - Ta ballen – ikke spilleren!
    </p>
    <p>Vi fjerner innlegg som er diskriminerende, hetsende og usaklige, spam og identiske kommentarer.</p>
  </div>
  <div id="disqus_thread"></div>
    <script type="text/javascript">
    var disqus_shortname = 'radikalportal';

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
  </script>
</div>
<?php endif; ?>

    </div> <!--/.row -->
  </div> <!--/.post -->
<?php endwhile; else: ?>
    <p>Fant ingenting her.</p>
<?php endif; ?>
</div>
</div> <!--/.row -->

<?php get_footer(); ?>
