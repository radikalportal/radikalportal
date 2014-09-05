<?php get_header(); ?>

<div class="row">
  <div class="span5 visible-desktop"><?php get_sidebar(left); ?>
  </div>
  <div class="span18 shadow">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div class="post single">

<?php if (get_the_ID() == 8373) { ?>
<iframe src="http://new.livestream.com/accounts/2698249/events/1996825/player?width=705&height=397&autoPlay=true&mute=false" width="705" height="397" frameborder="0" scrolling="no"> </iframe>
<?php } else { ?>

      <div class="thumb">
<?php if (has_post_thumbnail()) { the_post_thumbnail('full'); echo "<br/>"; echo get_post(get_post_thumbnail_id())->post_content; echo "<br>"; echo get_post(get_post_thumbnail_id())->post_excerpt; } ?>
      </div>

<?php } ?>

      <div class="row">
        <div class="span18">
          <h1><?php the_title(); ?></h1>
          <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php
  foreach((get_the_category()) as $category) {
	if ($category->cat_name != 'Skjult fra forsiden' )
	if ($category->cat_name != 'Ingen kategori' )
	if ($category->cat_name != 'Aktuelt' ) 
	if ($category->cat_name != 'Romtekst' ) 
	if ($category->cat_name != 'Romvideo' ) 
	if ($category->cat_name != 'Romkronikk' ) 
		if ($category->cat_name != 'Fremhevet' ) {
			echo ' ' . $category->cat_name . ' ';
		}
	}
?>
          </div>	
        </div>
      </div>
      <div class="row">

<div class="span18">
  <?php get_template_part('author', 'inline'); ?>
  <?php get_template_part('sharebar'); ?>
</div>

<div class="span15">
  <div class="entry">
    <br>
<?php the_content(); ?>

<?php
$faktabokser = get_post_custom_values('faktaboks');
if (isset($faktabokser)) {
	foreach ($faktabokser as $key => $value) {
		echo '<div class="well">' . $value . '</div>';
	}
}
?>

    <br>
    <b>Publisert: <?php the_date("d.m.y"); ?></b>

  </div> <!-- /.entry -->
</div> <!-- /.span15 -->

<div class="span18">
  <hr>
  <div class="hidden-phone"><?php wp_related_posts(); ?></div>
  <h3 class="page-header">Tips oss</h3>
  Har du et tips om en sak du mener vi burde skrive om, eller en lenke vi burde dele på siden så send oss et <a href="http://radikalportal.no/tips-oss/">tips</a>.
</div>

<?php if (!strcmp(get_post_meta($post->ID, 'ingendiskusjon', true), "1") == 0) : ?>
<div class="span18">
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
  <div id="disqus_thread">
  </div>
  <script type="text/javascript">
    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
    var disqus_shortname = 'radikalportal'; // required: replace example with your forum shortname

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
  </script>
  <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
  <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
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
