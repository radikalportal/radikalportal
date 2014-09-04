<?php get_header(); ?>

<div class="row">
<div class="span5 visible-desktop"><?php get_sidebar(left); ?></div>
<div class="span19 side">


<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<div class="page-header">
  
</div>

<?php

// The Query
$args = array(
	"post_type" => "rp_event",
	"orderby" => "meta_value",
	"sf_meta_key" => "date",
	"order" => "asc",
	
);

$query = new WP_Query($args);


$field_values = simple_fields_value("date, hosts");

// The Loop
if ( $the_query->have_posts() ) {
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		echo '<li>' . $field_values["date"] . '</li>';
		echo '<li><a href="'. the_permalink() . '">' . get_the_title() . '</a></li>';
		echo '<li>' . $fields_values["hosts"];'</li>'
	}
} else {
	// no posts found
}
/* Restore original Post Data */
wp_reset_postdata();

?>

</div>
</div>

<?php get_footer(); ?>