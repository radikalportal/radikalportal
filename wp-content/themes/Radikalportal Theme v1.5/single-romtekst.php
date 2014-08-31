<?php
/*
Template Name Posts: Single tekst romfolk ser oss
*/
?>
<?php get_header(rom); ?>



<div class="row">

<div class="span24">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="post single">



<div class="row">
	<div class="span18 shadow">

		
		<div class="thumb"><?php if (has_post_thumbnail()) { the_post_thumbnail('large');  echo get_post(get_post_thumbnail_id())->post_content; echo "<br>"; echo get_post(get_post_thumbnail_id())->post_excerpt;} ?></div>
		
		
		<h1><?php the_title(); ?></h1>
	

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php
foreach((get_the_category()) as $category)

 {
    if ($category->cat_name != 'Aktuelt' ) 
if ($category->cat_name != 'Romtekst' ) 
if ($category->cat_name != 'Romvideo' ) 
if ($category->cat_name != 'Romkronikk' ) 
	if ($category->cat_name != 'Fremhevet' ){
    echo ' ' . $category->cat_name . ' ';

}
}
?>
		



		</div>	
	
<div class="row">
<div class="span17">

<?php if (!strcmp(get_post_meta($post->ID, 'ingenforfatter', true), "1") == 0) { ?>

<div style="height: 2px; background-color: #EEE; margin: 10px 0;"></div>

		<div class="row">
			<div class="span14">
			
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
			<div class="span12">
				<h4><?php the_author_posts_link() ?></h4>
				<?php the_author_description(); ?>
			
			</div>
			</div>
			
			<?php

$custom_fields = get_post_custom();
$my_custom_field = $custom_fields['forfatterid'];
foreach ( $my_custom_field as $key => $value ) {
	$userdata = get_userdata($value);
	//var_dump($userdata);
?>
			<div class="row">
			<div class="span2" style="margin-top: 20px;">
			<a href="/?author=<?= $value; ?>">
				<?php userphoto($value, '', '', array('class' => 'img-rounded hidden-phone')); ?>
			</a>
			</div>
			<div class="span12" style="margin-top: 20px;">
				<h4><a href="/?author=<?= $value; ?>"><?= $userdata->display_name; ?></a></h4>
				<?= $userdata->user_description; ?>
				
			
			</div>
			</div>
			<?php } ?>
				
				<br>
			<div class="publisert" style="float: clear;">
				Publisert <?php the_date(); ?> klokken <?php the_time() ?> </div>
			</div>
			
			<div style="padding: 0 0 0 15px;"><?php echo sharing_display(); ?> <script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script>
<div style="margin: 0 0 0 118px;"><a class="btn btn-small print-hidden" rel="nofollow" href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>" onclick="return fbs_click()" target="_blank"><i class="icon-share"></i> Del på Facebook</a></div></div>

			<div class="span3 hidden-phone">

<!--/.oldshare<iframe src="//www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font=arial&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=446811972038512" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe>

<br>
<br>

<a href="https://twitter.com/share" class="twitter-share-button" data-lang="no">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

<br>
<br>

<a class="btn btn-small" href="javascript:print();"><i class="icon-print"></i> Skriv ut</a>
-->
<?php dd_fblike_generate('Like Button Count') ?>
<?php dd_fbshare_generate('Compact') ?>
<?php dd_google1_generate('Compact (24px)') ?>
<?php dd_twitter_generate('Compact','radikalportal') ?>
			</div>



		</div> 

<div style="height: 2px; background-color: #EEE; margin: 10px 0;"></div>
<div>
<!--/.<div style="float: right; text-align: right;color: #000000;
    font-family: News Cycle;
    font-size: 12px;
    line-height: 16px;
    margin-bottom: 6px;">DEL PÅ:</div>-->

<?php } ?>

	</div>

	<div class="span18 romentry">
		<div class="entry">

			<?php the_content(); ?>

<?php

$custom_fields = get_post_custom();
$my_custom_field = $custom_fields['faktaboks'];
foreach ( $my_custom_field as $key => $value ) {
	echo '<div class="well">' . $value . '</div>';
}

?>

<?php if (!strcmp(get_post_meta($post->ID, 'ingendiskusjon', true), "1") == 0) { ?>

<div style="height: 2px; background-color: #EEE; margin: 10px 0;"></div>



		</div>
	</div>

	

<?php } ?>



</div> <!--/.row -->

</div> <!--/.post -->
<?php endwhile; else: ?>
	<p>Fant ingenting her. Sorry :(</p>
<?php endif; ?>



</div>		






	<div class="span4">




	<?php
	   $my_query = new WP_Query( "cat=490" );
   if ( $my_query->have_posts() ) { 
       while ( $my_query->have_posts() ) { 
           $my_query->the_post();?>

<?php
if ( has_post_thumbnail($thumbnail->ID)) {
      echo '<a href="' . get_permalink( $thumbnail->ID ) . '" title="' . esc_attr( $thumbnail->post_title ) . '">';
      echo get_the_post_thumbnail($thumbnail->ID, 'thumbnail');
      echo '</a>';
    }  
?>



	<a href="<?php echo post_permalink( $ID ); ?>">  <h4>         <?php the_title(); ?></h4></a>
<?php       }
   }
   wp_reset_postdata();
	?>

	</div>	

</div>

<div class="disqus span18">
		<h2 class="page-header diskusjon">Diskusjon</h2>
<div class="well debattregler"><p><strong>DEBATTREGLER:</strong><br>
- Respekter dine meddebattanter og utøv normal folkeskikk<br>
- Vær saklig og hold deg til tema<br>
- Ta ballen – ikke spilleren!</p>
<p>Vi fjerner innlegg som er diskriminerende, hetsende og usaklige, spam og identiske kommentarer.</p></div>
		<div id="disqus_thread"></div>
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
</div> <!--/.row -->

<?php get_footer(); ?>