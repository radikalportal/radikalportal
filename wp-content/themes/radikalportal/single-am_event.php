<?php get_header(); ?>

<div class="row">
<div class="span5 visible-desktop"><?php get_sidebar(left); ?></div>
<div class="span18 shadow">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="post single">
<div class="thumb">
<?php if (has_post_thumbnail()) { the_post_thumbnail('full'); echo get_post(get_post_thumbnail_id())->post_content; echo "<br>"; echo get_post(get_post_thumbnail_id())->post_excerpt; } ?></div>


 

<div class="row">
	<div class="span18">
		<h1><?php the_title(); ?></h1>
	

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php
foreach((get_the_category()) as $category)

 {
if ($category->cat_name != 'Skjult fra forsiden' )
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
</div>
	<div class="span15">

<?php if (!strcmp(get_post_meta($post->ID, 'ingenforfatter', true), "1") == 0) { ?>



		<div class="row">
			<div class="span12">
			
			<div class="row">
			
			<div class="span11">
				
			
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
				<h4><a href="/?author=<?= $value; ?>"><?= $userdata->display_name; ?></a></h4>
				<?= $userdata->user_description; ?>
				
			</div>
			</div>
			<?php } ?>
			<?php } ?>
				
				
			
			</div>

			


			



		</div> <!--/.row -->

<div style="height: 2px; background-color: #EEE; margin: 10px 0;"></div>
<!--/.<div style="float: right; text-align: right;color: #000000;
    font-family: News Cycle;
    font-size: 12px;
    line-height: 16px;
    margin-bottom: 6px;">DEL PÅ:</div>-->



<?php } ?>

<?php get_template_part('sharebar'); ?>

	<div class="span15">
		<div class="entry">
<br>
			<?php the_content(); ?>
<br>


<?php
$faktabokser = get_post_custom_values('faktaboks');
if (isset($faktabokser)) {
	foreach ($faktabokser as $key => $value) {
		echo '<div class="well">' . $value . '</div>';
	}
}
?>

<?php if (!strcmp(get_post_meta($post->ID, 'ingendiskusjon', true), "1") == 0) { ?>





		</div>
	</div>

	
	   
	</div>

<?php } ?>



</div> <!--/.row -->

</div> <!--/.post -->
<?php endwhile; else: ?>
	<p>Fant ingenting her.</p>
<?php endif; ?>

</div>
</div> <!--/.row -->

<?php get_footer(); ?>