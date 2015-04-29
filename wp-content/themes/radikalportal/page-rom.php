<?php get_header(); ?>

<img style="width: 100%;" src="<?= get_template_directory_uri(); ?>/img/romfolket.png" alt="Romfolket ser oss">

<?php 
global $more;    // Declare global $more (before the loop).
$more = 0;       // Set (inside the loop) to display content above the more tag.
the_content();
?>

<div class="row">

<div class="col-md-4 sitater"><blockquote><p>Jeg er stolt av å være en sigøyner fra Romania.</p><br></blockquote><div align="right"><p>- Dinu</p></div></div><!--/.SITAT1-->
<div class="col-md-4 sitater"><blockquote><p>De har så fine klær! Jentene er så pene!</p><br></blockquote><div align="right"><p>- Claudia</p></div></div><!--/.SITAT2-->
<div class="col-md-4 sitater"><blockquote><p>Jeg trenger ikke noe stort i livet, bare to rom til min kone og mine to barn.</p></blockquote><div align="right"><p>- Dinu</p></div></div><!--/.SITAT3-->

</div>

<hr class="hrclass">
<div class="row">
	<div class="col-md-7">
	
	<?php query_posts('cat=487'); ?>

  <?php while (have_posts()) : the_post(); ?>

<?php the_post_thumbnail('medium');?>		
<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
		<?php the_title(); ?></a></h3>
<div class="filmingress"><p><?php the_content(' '); ?></p></div><div>
				<small>Publisert <?php the_time('d/m/Y') ?> </small>
				<br>
<br>
				<a style="font-weight: bold;" href="<?php the_permalink() ?>#disqus_thread"></a>
				</div>
			

  <?php endwhile;?>
	
	
	
	
	
	</div><!--/.col-md-7-->
	
	<div class="col-md-5">
	
	
	
	
	
	
	
		<div class="well">
		<?php rewind_posts(); ?>
		<?php query_posts('cat=489'); ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<h4>Kronikk: <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
		<?php the_title(); ?></a></h4>
			
				<div class="span4" style="float: left; margin: 0px 6px 3px 0px; padding: 0px 3px 3px 0px;">
				<?php the_post_thumbnail('medium');?>
				</div>	<!--/.thumbnailspan4-->

				<div>
				<p><?php the_excerpt(); ?></p>
				<small>Av <?php the_author_posts_link() ?> <?php the_time('d/m/Y') ?> </small>
				<br>
				<a style="font-weight: bold;" href="<?php the_permalink() ?>#disqus_thread"></a>
				</div>
			
		<?php endwhile; else: ?>
		<p>Fant ingenting her.</p>
		<?php endif; ?>

		</div><!--/.well-->



		


		<div>
	 	<?php rewind_posts(); ?>
		<?php query_posts('cat=488'); ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	

		
		
			<hr>
			<div class="col-md-12" style="float: left; margin: 0px 6px 3px 0px; padding: 0px 3px 3px 0px;">
			<?php the_post_thumbnail('medium');?>
			</div>	

			<div class="romtekst">
			<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
			<?php the_title(); ?></a></h4>
			
			
			<p><?php the_excerpt(); ?></p>

			<small>Av <?php the_author_posts_link() ?> <?php the_time('d/m/Y') ?> </small>
			<br>
			<a style="font-weight: bold;" href="<?php the_permalink() ?>#disqus_thread">
			<span></span>
			</a>
			<br class="clearfloat">
				
	
	
		</div>
	
	
	
	
	
	
	
	<?php endwhile; else: ?>
	<p>Fant ingenting her.</p>
	<?php endif; ?>
	<br>
	<br>


	</div><!--/.col-md-5-->


<div class="row">
<div class="span24">
<br>
<br>
<!--/.rel<h4>Relaterte saker fra Radikal Portal</h4>

<div class="span4">
<h4><a href="http://radikalportal.no/2013/02/04/bokanmeldelse/">Romfolkets kamp og kultur</a></h4>

</div>

<div class="span4">
<h4><a href="http://radikalportal.no/2013/02/09/sigoynere-roma-gypsies-sinti-manoush-romanichal-kale-men-ikke-romfolk/">Sigøynere og Róma, men ikke Romfolk</a></h4>
</div>


<div class="span4">
<h4><a href="http://radikalportal.no/2013/05/14/gjentagelse-og-propaganda-i-media/‎">Gjentakelse og propaganda</a></h4>


</div>

<div class="span4">
<h4><a href="http://radikalportal.no/2013/05/18/rom-for-rom/‎">Rom for Rom</a></h4>
‎



</div>-->

<div class="span4">
</div>

<div class="span4">
</div>



</div>	
</div>	

</div><!--/.row-->




 

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