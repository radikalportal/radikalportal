<style>

div.kortnyttcontainer
{
        margin-bottom: 1em;
        position: relative;
}

div.kortnytt
{
	margin-left: 15px;
}

 div.antirasisme
,div.arbeidsliv
,div.feminisme-og-kjonn
,div.antikrig
,div.miljo-og-klima
{
	position: absolute;
	left: 0px;
	top: 0px;
	width: 10px;
	height: 100%;
	padding: 2px 0px;
	color: white;
	font-size: 7pt;
	font-family: courier;
	text-align: center;
	text-transform: uppercase;
	font-weight: bold;
}

/*
.antirasisme:before { content:"a n t i r a s i s m e"; }
.arbeidsliv:before { content:"a r b e i d s l i v"; }
.feminisme-og-kjonn:before { content:"f e m i n i s m e"; }
.antikrig:before { content:"a n t i k r i g"; }
.miljo-og-klima:before { content:"k l i m a"; }
*/

</style>
<div>
  <div>
    <div>
<?php
  $args = array(
	'post_type' => 'kortnytt',
  );
  $query = new WP_Query( $args );

  if ( $query->have_posts()) : 
    while ( $query->have_posts()) : $query->the_post();
?>
      <div class="kortnyttcontainer">
        <div class="<?php 
  $terms = get_the_terms( $post->ID, 'kortnytt_category');
  if ($terms && ! is_wp_error($terms)) :
	$term_slugs_arr = array();
	foreach( $terms as $term) {
	    $term_slugs_arr[] = $term->slug;
	}
	$terms_slug_str = join( " ", $term_slugs_arr);
  endif;
  echo $terms_slug_str;
?>">
        </div>
        <div class="kortnytt">
          <div class="uppercase"><?php 
  $terms = get_the_terms( $post->ID, 'kortnytt_category');
  if( $terms && ! is_wp_error($terms)) :
	$term_slugs_arr = array();
	foreach( $terms as $term) {
	    $term_slugs_arr[] = $term->name;
	}
	$terms_slug_str = join( " ", $term_slugs_arr);
  endif;
  echo $terms_slug_str;
?></div>
	  <h4 class="nomargin" style="margin:0em 0em 0em 0em;">
	    <strong>
              <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
		<?php the_title(); ?>
              </a>
            </strong>
          </h4>
        </div>
      </div>
<?php endwhile; ?> 
<?php wp_reset_postdata(); ?>
<?php else: ?>
<?php endif; ?>
    </div>
<!--
    <br class="clearfloat">
    <br>
    <style type="text/css"> li {list-style: none;} </style>
    <div>
    </div>
-->
  </div>
</div>
