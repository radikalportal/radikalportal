<?php
/*
Template Name Posts: Single video romfolk ser oss
*/
?>
<?php get_header(rom); ?>



<div class="row">

<div class="span24">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="post single">



<div class="row">
	<div class="span18 shadow">

		
		<?php if (has_post_thumbnail()) { the_post_thumbnail('medium'); echo get_post(get_post_thumbnail_id())->post_excerpt; } ?>
		
		
		<h1><?php the_title(); ?></h1>
<div style="padding: 0 0 0 0px;"><?php echo sharing_display(); ?><script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script>
<div style="margin: 0 0 0 126px;"><a class="btn btn-small print-hidden" rel="nofollow" href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>" onclick="return fbs_click()" target="_blank"><i class="icon-share"></i> Del på Facebook</a></div></div>

	
<!--/.oldshare <iframe src="//www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font=arial&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=446811972038512" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe>

<br>
<br>

<a href="https://twitter.com/share" class="twitter-share-button" data-lang="no">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>-->




<hr>
<div class="row">


	<div class="span18">
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
<div class="disqus span18">
		<h2 class="page-header">Diskusjon</h2>
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




	</div>

	

<?php } ?>



</div> <!--/.row -->


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



</div>







</div> <!--/.row -->

<?php get_footer(); ?>