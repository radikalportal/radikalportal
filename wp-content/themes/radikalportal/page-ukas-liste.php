<?php get_header(); ?>

<div class="row">
<div class="span5 visible-desktop"><?php get_sidebar(left); ?></div>
<div class="span19">

<div class="page-header">
  <h1>
  	<?php the_title(); ?>
  	
  </h1>
</div>

<?php query_posts('cat=412'); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>




<div class="row page-ukasliste-content">
	
	<div class="span5" style="float: left;">
<a href="<?php the_permalink() ?>"><?php the_post_thumbnail('medium'); ?></a>

	</div>


	<div class="span14" style="float: right;">
	

		<!--/.kategorimerkelapp<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
		foreach (get_the_category() as $category){

		echo ' ' . $category->cat_name . ' ';

		}
		?>

		</div>	-->


		<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
		<?php the_title(); ?></a></h4>



		<div class="entry">
			<?php the_excerpt(); ?>
			<small><?php the_author_posts_link() ?> <?php the_time('d/m/Y') ?> </small>
			<br>
			<a style="font-weight: bold;" href="<?php the_permalink() ?>#disqus_thread">
			<span></span>
			</a>
			<br>
			<br>
			
			
		</div>
	
	
	</div>
	
</div>

<?php endwhile; else: ?>
	<p>Fant ingenting her.</p>
<?php endif; ?>

</div>
</div>

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