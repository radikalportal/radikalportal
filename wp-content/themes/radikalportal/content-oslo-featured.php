
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php if ( has_post_thumbnail() ) : ?>
<a class="front-page-img" href="<?php the_permalink() ?>">
<?php the_post_thumbnail('large'); ?>
</a>
<?php endif; ?>

<?php
$forfatterids = get_post_custom_values('forfatterid');
?>

<div class="pull-left author-photo-frontpage">
<?php if (isset($forfatterids)) { ?>
	<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent lenke til <?php the_title_attribute(); ?>">
	  <img alt="" src="http://radikalportal.no/wp-content/uploads/userphoto/51.jpg" class="img-rounded">
        </a>
<?php } else { ?>
        <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent lenke til <?php the_title_attribute(); ?>">
         <?php
           userphoto_the_author_photo(
	           '',
	           '',
	           array(),
	           get_template_directory_uri() . '/img/anon.gif'
           );
         ?>
        </a>
<?php } ?>
</div>

<div class="media-body">

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

|
<div class="entry-comment-count">
  <span class="glyphicon glyphicon-comment"></span>
  <a href="<?php the_permalink() ?>#disqus_thread" data-disqus-identifier="<?php echo dsq_identifier_for_post($post); ?>">
    <?= get_comments_number(); ?>
  </a>
</div>

		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			endif;
		?>

  <?php if ( is_search() ) : ?>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div><!-- .entry-summary -->
  <?php else : ?>
  <div class="entry-summary">
    <?php
      the_excerpt();
    ?>
  </div><!-- .entry-summary -->
  <?php endif; ?>

<?php if (strcmp(get_the_author(), "Nyhet") != 0) : ?>
  <div class="entry-author">
Av <?php the_author(); ?>
<?php
if (isset($forfatterids)) {
  $cnt = count($forfatterids);
  foreach ($forfatterids as $key => $value) {
    $userdata = get_userdata($value);
    echo ($cnt-- > 1 ? ', ' : ' og ') . $userdata->display_name;
  }
}
?>
  </div>
<?php endif; ?>

	</div>
  <div style="clear: both;"></div>
</div>
