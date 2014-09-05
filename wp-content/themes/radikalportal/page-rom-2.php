<?php get_header(rom); ?>





<?php 
global $more;    // Declare global $more (before the loop).
$more = 0;       // Set (inside the loop) to display content above the more tag.
the_content();
?>

<div class="row">
<div class="span6"><blockquote>	"Dette er en test er en test er en test er en test"	<div align="right"><p>- Kvinne 54</p></div></blockquote></div><!--/.SITAT1-->
<div class="span6"><blockquote>	"Dette er test nr to er test nr to er test nr to er test nr to"	<div align="right"><p>- Kvinne 23</p></div></blockquote></div><!--/.SITAT2-->
<div class="span6"><blockquote>	"Dette er test nr tre er test nr tre er test nr tre"<div align="right"><p>- Mann 35</p></div></blockquote></div><!--/.SITAT3-->
<div class="span6"><blockquote>	"og dette nr fire dette nr fire dette nr fire"	<div align="right"><p >- Gutt 14</p></div></blockquote></div><!--/.SITAT4--></div>



<div class="row">
	<div class="span14">
	<?php query_posts('cat=487'); ?>

  <?php while (have_posts()) : the_post(); ?>

<div class="entry">

   <?php the_content('Les mer...'); ?>
 </div>



  <?php endwhile;?>
	
	

	
	</div>



<div class="span10">
<div class="row">
 <?php rewind_posts(); ?>
	<?php query_posts('cat=489'); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			
		<div class="span10">
<div class="well">
<h4>Kronikk: <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
		<?php the_title(); ?></a></h4>

			<div class="span4" style="float: left; margin: 0px 6px 3px 0px; padding: 0px 3px 3px 0px;">

<?php the_post_thumbnail('medium');?>
			</div>	


			<div>
			
			<p><?php the_excerpt(); ?></p>

			<small>Av <?php the_author_posts_link() ?> <?php the_time('d/m/Y') ?> </small>
			<br>
			<a style="font-weight: bold;" href="<?php the_permalink() ?>#disqus_thread"></a>
			
			
			
			
			</div>
		</div>
	</div>







<?php endwhile; else: ?>
	<p>Fant ingenting her.</p>
<?php endif; ?>

	
	
	
	
	
	
	


	 <?php rewind_posts(); ?>
	<?php query_posts('cat=488'); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	
	<div class="row">
			<hr>
		<div class="span10">

			<div class="span4" style="float: left; margin: 0px 6px 3px 0px; padding: 0px 3px 3px 0px;">

<?php the_post_thumbnail('medium');?>
			</div>	

<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
		<?php the_title(); ?></a></h4>
			<div>
			
			<p><?php the_excerpt(); ?></p>

			<small>Av <?php the_author_posts_link() ?> <?php the_time('d/m/Y') ?> </small>
			<br>
			<a style="font-weight: bold;" href="<?php the_permalink() ?>#disqus_thread">
			<span></span>
			</a>
			<br>
			<br>
			
			
			</div>
		</div>
	</div>	

	
	
	
	
	
	
	
</div>	



</div>
	

	
<?php endwhile; else: ?>
	<p>Fant ingenting her.</p>
<?php endif; ?>


 <?php rewind_posts(); ?>
<?php 

				query_posts( 'tag=romfolk' );

				if ( have_posts() ) while ( have_posts() ) : the_post();

						echo '<li>';

							the_title();

						echo '</li>';

?>

<script type="text/javascript">
    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
    var disqus_shortname = 'radikalportal'; // required: replace example with your forum shortname

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function () {
        var s = document.createElement('script'); s.async = true;
        s.type = 'text/javascript';
        s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());
</script>

<?php get_footer(); ?>