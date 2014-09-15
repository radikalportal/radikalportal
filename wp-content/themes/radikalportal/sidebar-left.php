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

.tagcloud
{
	font-family: sans-serif;
}

</style>

<div style="margin: 0 0 5px 0; background-color: #fff; border: 2px solid #ccc; border-radius: 2px; padding: 5px 10px;">
  <a href="/stott-radikal-portal/">
    <img src="/wp-content/themes/radikalportal/img/fundrasing.jpg" alt="Støtt Radikal Portal">
    <h3>Støtt Radikal Portal</h3>
  </a>
</div>

<div style="margin: 0 0 5px 0; background-color: #fff; border: 2px solid #ccc; border-radius: 2px; padding: 5px 10px;">
  <a href="/kategori/skrivekonkurranse/">
    <div style="text-align: center;">
      <img style="width: 45%;" src="/wp-content/themes/radikalportal/img/feminist.jpg" alt="Skrivekonkurransen">
    </div>
    <b>Skrivekonkurransen:</b>
    <h3>Kvinne i Norge i dag</h3>
  </a>
</div>

<style type="text/css">
li {list-style: none;}
</style>
<div>
 <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Left Sidebar')) : ?>
 <?php endif; ?>
</div>
