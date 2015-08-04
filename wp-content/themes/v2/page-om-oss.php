<?php get_header(); ?>
<div class="mh-wrapper clearfix">
	<div class="mh-main">
    	<div id="main-content" class="mh-content"><?php
    		if (have_posts()) :
    			while (have_posts()) : the_post();
					mh_before_page_content();
					get_template_part('content', 'page');
				endwhile;
				get_template_part('comments', 'pages');
            endif; ?>

<?php
echo '<h1>Redaksjonen</h1>';
echo '<br>';

$redaksjonsmedlemmer = get_post_custom_values('redaksjonsmedlemmer');

if (isset($redaksjonsmedlemmer[0])) {
    $redaksjonsmedlemmer = unserialize($redaksjonsmedlemmer[0]);

    foreach (get_users(array('include' => $redaksjonsmedlemmer,
                             'orderby' => 'display_name',
                             'order'   => 'ASC')) as $key => $redaksjonsmedlem) {

        $userdata = get_userdata($redaksjonsmedlem->ID);

        echo '<div class="redaksjonsmedlem">';
        echo '<div class="redaksjonsmedlem-portrett">';

        if (userphoto_exists($redaksjonsmedlem->ID)) {
            userphoto(
                $redaksjonsmedlem->ID,
                '',
                '',
                array(),
                get_template_directory_uri() . '/img/anon.gif'
            );
        } else {
            echo '<img class="img-rounded" src="' . get_template_directory_uri() . '/img/anon.gif">';
        }

        echo '</div>';
        echo '<div class="redaksjonsmedlem-biografi">';

        echo "<h2>" . $userdata->display_name . "</h2>";
        echo "<p>" . $userdata->user_description . "</p>";

        echo '</div>';
        echo '</div>';
    }
}
?>

        </div>
		<?php get_sidebar(); ?>
    </div>
    <?php mh_second_sb(); ?>
</div>
<?php get_footer(); ?>
