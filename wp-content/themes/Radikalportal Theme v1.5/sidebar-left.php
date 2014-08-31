<?php
	/* Wed Feb 26 11:17:45 CET 2014, Erling Westenvik
	 * Removed p-tags surrounding:
	 * <p><*php the_excerpt();*></p>
	 * Removed two div-tags that surrounded the whole content.
	 * Removed class="nomargin" from h4-tag.
	 */

	/* Tue Apr 1 00:45:00 CET 2014, Tore Norderud
	 * La til skrivekonkurranse-lenke.
	 */

	function ew_strip_tag( $tag, $str) {
		return preg_replace(
			 "/^<".$tag.">(.*)<\/".$tag.">$/"
			,"\\1"
			,$str);
	}
?>

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

.tagcloud
{
	font-family: sans-serif;
}

/*
.antirasisme:before { content:"a n t i r a s i s m e"; }
.arbeidsliv:before { content:"a r b e i d s l i v"; }
.feminisme-og-kjonn:before { content:"f e m i n i s m e"; }
.antikrig:before { content:"a n t i k r i g"; }
.miljo-og-klima:before { content:"k l i m a"; }
*/

</style>

<div style="margin: 0 0 5px 0; background-color: #fff; border: 2px solid #ccc; border-radius: 2px; padding: 5px 10px;">
  <a href="/stott-radikal-portal/">
    <img src="/wp-content/themes/Radikalportal%20Theme%20v1.5/img/fundrasing.jpg" width="100%"/>
    <h3>Støtt Radikal Portal</h3>
  </a>
</div>

<div style="margin: 0 0 5px 0; background-color: #fff; border: 2px solid #ccc; border-radius: 2px; padding: 5px 10px;">
  <a href="/kategori/skrivekonkurranse/">
    <div style="text-align: center;">
      <img src="/wp-content/themes/Radikalportal%20Theme%20v1.5/img/feminist.jpg" width="45%"/>
    </div>
    <b>Skrivekonkurransen:</b>
    <h3>Kvinne i Norge i dag</h3>
  </a>
</div>

<h3>Kortnytt</h3>
  <div>
   <?php
  $args = array(
	'post_type' => 'kortnytt',
	'posts_per_page' => 5
	
  );
  $query = new WP_Query( $args );

  if ( $query->have_posts() ) : 
    while ( $query->have_posts() ) : $query->the_post();
   ?>
<?php
  global $more;
  $more = 0;
?>
    <div class="kortnyttcontainer">
      <div class="<?php 
  $terms = get_the_terms($post->ID, 'kortnytt_category' );
  if ($terms && ! is_wp_error($terms)) :
	$term_slugs_arr = array();
	foreach ($terms as $term) {
	    $term_slugs_arr[] = $term->slug;
	}
	$terms_slug_str = join( " ", $term_slugs_arr);
  endif;
  echo $terms_slug_str;
?>">
      </div>
      <div class="kortnytt">
        <div class="uppercase">
<?php 
$terms = get_the_terms($post->ID, 'kortnytt_category' );
if ($terms && ! is_wp_error($terms)) :
	$term_slugs_arr = array();
	foreach ($terms as $term) {
	    $term_slugs_arr[] = $term->name;
	}
	$terms_slug_str = join( " ", $term_slugs_arr);
endif;
echo $terms_slug_str;
?>
        </div>
	<h4>
	  <strong>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
	     <?php the_title(); ?>
	    </a>
          </strong>
        </h4>
        <p>
          <!--<small>-->
          <span style="color:gray;">
           <?php the_time('d/m/Y') ?>
          </span>
          <!--</small>-->
          &nbsp;&ndash;&nbsp;
         <?php
          ob_start();
          the_excerpt();
          $c = ob_get_contents();
          ob_end_clean();
          echo ew_strip_tag( "p", $c);
         ?>
        </p>
<!--
      <small><?php the_time('d/m/Y') ?></small>
      <br>
      <a style="font-weight: bold;" href="<?php the_permalink() ?>#disqus_thread"></a>
-->
      </div>
    </div>
<?php endwhile; ?> 
<?php wp_reset_postdata(); ?>
<?php else: ?>
<?php endif; ?>
</div>
<br class="clearfloat">
<br>
<br>
<style type="text/css">
li {list-style: none;}
</style>
<div>
 <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Left Sidebar')) : ?>
 <?php endif; ?>
</div>
