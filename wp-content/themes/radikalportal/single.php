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
      <div class="span15">

<?php if (!strcmp(get_post_meta($post->ID, 'ingenforfatter', true), "1") == 0) { ?>

        <div style="height: 2px; background-color: #EEE; margin: 10px 0;">
        </div>
        <div class="row">
          <div class="span12">
            <div class="row">
              <div class="span2">
                <a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>">
				<?php userphoto_the_author_photo(
	                '',
	                '',
	                array('class' => 'img-rounded hidden-phone'),
	                get_template_directory_uri() . '/img/anon.gif'
	            ); ?>
                </a>
              </div>
              <div class="span9">
                <h4><?php the_author_posts_link() ?></h4>
                <?php the_author_description(); ?>
              </div>
            </div>
<?php
$forfatterids = get_post_custom_values('forfatterid');
if (isset($forfatterids)) {
	foreach ($forfatterids as $key => $value) {
		$userdata = get_userdata($value);
?>
            <div class="row">
              <div class="span2" style="margin-top: 20px;">
                <a href="/?author=<?= $value; ?>">
                  <?php userphoto($value, '', '', array('class' => 'img-rounded hidden-phone')); ?>
                </a>
              </div>
              <div class="span9" style="margin-top: 20px;">
	        <h4>
	  	  <a href="/?author=<?= $value; ?>"><?= $userdata->display_name; ?></a>
                </h4>
                <?= $userdata->user_description; ?>
              </div>
            </div>
<?php } ?>
<?php } ?>
            <br>
            <div class="publisert" style="float: clear;">
              Publisert <?php the_date(); ?> klokken <?php the_time() ?>
            </div>
          </div>
        </div> <!--/.row -->
<?php } ?>
        <div style="background-color:#f9f9f9;border-style:solid;border-color:#eeeeee;border-width:2px 0px 0px 0px;padding:4px 0px 0px 4px;">
	  <div style="float:left;padding:0px 0px;">
            <a class="btn btn-small" href="javascript:print();"><i class="icon-print"></i>Skriv ut</a>
	  </div>
	  <div style="float:left;padding:3px 0px 0px 14px;">
        <div class="fb-like" data-href="<?= get_permalink(); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
	  </div>
	  <div style="float:left;padding:3px 0px 0px 14px;">
            <?php dd_twitter_generate('Compact','radikalportal') ?>
	  </div>
	  <div style="float:left;padding:1px 0px;margin-left:-14px;">
            <?php dd_google1_generate('Compact (24px)') ?>
	  </div>
	  <div class="clearfix"></div>
        </div>
      </div>
      <br>
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
          <?php if (!strcmp(get_post_meta($post->ID, 'ingendiskusjon', true), "1") == 0) { ?>

        <div style="background-color:#f9f9f9;border-style:solid;border-color:#eeeeee;border-width:2px 0px 0px 0px;padding:4px 0px 0px 4px;">
	  <div style="float:left;padding:0px 0px;">
            <a class="btn btn-small" href="javascript:print();"><i class="icon-print"></i>Skriv ut</a>
	  </div>
	  <div style="float:left;padding:3px 0px 0px 14px;">
        <div class="fb-like" data-href="<?= get_permalink(); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
	  </div>
	  <div style="float:left;padding:3px 0px 0px 14px;">
            <?php dd_twitter_generate('Compact','radikalportal') ?>
	  </div>
	  <div style="float:left;padding:1px 0px;margin-left:-14px;">
            <?php dd_google1_generate('Compact (24px)') ?>
	  </div>
	  <div class="clearfix"></div>
        </div>
        </div>
      </div>
      <div class="span8">
        <h3 class="page-header">Tips oss</h3>
          <div class="textwidget"><p>Har du et tips om en sak du mener vi burde skrive om, eller en lenke vi burde dele på siden så send oss et tips <a href="http://radikalportal.no/tips-oss/">HER</a></p></div>
      </div>
	<div class="span7">
		<h3 class="page-header">Følg oss på Facebook</h3>
		<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fradikalportal&amp;width=292&amp;height=62&amp;show_faces=false&amp;colorscheme=light&amp;stream=false&amp;border_color=%23ffffff&amp;header=false&amp;appId=446811972038512" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:62px;" allowTransparency="true"></iframe>
	</div>
      <div class="span15">
        <h2 class="page-header">Diskusjon</h2>
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
<?php } ?>
    </div> <!--/.row -->
  </div> <!--/.post -->
<?php endwhile; else: ?>
    <p>Fant ingenting her. Sorry :(</p>
<?php endif; ?>
</div>
</div> <!--/.row -->

<?php get_footer(); ?>
