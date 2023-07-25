<?php

/**
 *  Facebook vil ha en app_id som metatagg.
 */
function v2_opengraph_metadata($metadata) {
    $metadata["fb:app_id"] = "446811972038512";
    return $metadata;
}
add_filter("opengraph_metadata", "v2_opengraph_metadata");

function v2_login_logo() { ?>
    <style type="text/css">
     .login h1 a {
         background-image: url(//radikalportal.no/apple-touch-icon-180x180.png);
     }
    </style>
<?php }
add_action('login_enqueue_scripts', 'v2_login_logo');

function mh_share_buttons_content() {
    $sb_output = '<section class="share-buttons-container clearfix">' . "\n";
    $sb_output .= '<div class="share-button"><div class="fb-like" data-layout="button_count" '.((get_the_date('Y-m-d')<'2016-01-10')?'data-href="'.str_replace('https://','http://',get_permalink()).'"':'').'" data-action="like" data-show-faces="true" data-share="false"></div></div>' . "\n";
    $sb_output .= '<div class="share-button"><a href="' . esc_url('https://twitter.com/share') . '" class="twitter-share-button" data-text="' . get_the_title() . '" data-via="radikalportal">Tweet</a></div>' . "\n";
    $sb_output .= '<div class="share-button"><div class="g-plusone" data-size="medium"></div></div>' . "\n";
    $sb_output .= '</section>' . "\n";
    echo $sb_output;
}

/***** Load Stylesheets *****/

function mh_magazine_child_styles() {
    wp_enqueue_style('mh-magazine-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('mh-magazine-child-style', get_stylesheet_directory_uri() . '/style.css', array('mh-magazine-parent-style'), '160309');
}
add_action('wp_enqueue_scripts', 'mh_magazine_child_styles');

function mh_scripts() {
    wp_enqueue_script('scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'));
    if (!is_admin()) {
	if (is_singular() && comments_open() && (get_option('thread_comments') == 1))
	    wp_enqueue_script('comment-reply');
    }
}

require_once('includes/v2-widgets.php');
require_once('includes/v2-custom-functions.php');

function v2_apple_touch_icons() {
    $output = "";

    $output .= "<link rel=\"shortcut icon\" href=\"/favicon.ico\" type=\"image/x-icon\">\n";
    $output .= "<link rel=\"apple-touch-icon\" href=\"/apple-touch-icon.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"57x57\" href=\"/apple-touch-icon-57x57.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"/apple-touch-icon-72x72.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"76x76\" href=\"/apple-touch-icon-76x76.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"/apple-touch-icon-114x114.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"120x120\" href=\"/apple-touch-icon-120x120.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"/apple-touch-icon-144x144.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"152x152\" href=\"/apple-touch-icon-152x152.png\">\n";
    $output .= "<link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"/apple-touch-icon-180x180.png\">\n";

    echo $output;
}
add_action('wp_head', 'v2_apple_touch_icons');

function v2_rules_to_comment_template($comment_template) {
    global $post;
    if ('open' == $post->comment_status) {
        echo '<section class="comments">';
        echo '<h3 class="section-title">Diskusjon</h3>';
        echo '<div class="section-title">';
        echo '<p>';
        echo '<strong>DEBATTREGLER:</strong>';
        echo '<ul>';
        echo '<li>- Respekter dine meddebattanter og utøv normal folkeskikk</li>';
        echo '<li>- Vær saklig og hold deg til tema</li>';
        echo '<li>- Ta ballen – ikke spilleren!</li>';
        echo '</ul>';
        echo '</p>';
        echo '<p>Vi fjerner innlegg som er diskriminerende, hetsende og usaklige, spam og identiske kommentarer.</p>';
        echo '</div>';
        echo '</section>';
    }

    return $comment_template;
}
add_filter("comments_template", "v2_rules_to_comment_template");

/**
 *  Overstyrer mh_featured_image definert i
 *  mh_magazine/includes/mh-post-content.php for å bruke
 *  get_the_post_thumbnail for å få featured videos til å fungere.
 */
function mh_featured_image() {
    global $page, $post;
    $options = mh_theme_options();
    if (has_post_thumbnail() && $page == '1' && $options['featured_image'] == 'enable' && !get_post_meta($post->ID, 'mh-no-image', true)) {
        if ($options['sidebars'] == 'no') {
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'slider');
        } else {
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'content');
        }
        if ($options['link_featured_image'] == 'enable') {
            $att_url_begin = '<a href="' . esc_url(get_attachment_link(get_post_thumbnail_id())) . '">';
            $att_url_end = '</a>';
        } else {
            $att_url_begin = '';
            $att_url_end = '';
        }
        $caption_text = get_post(get_post_thumbnail_id())->post_excerpt;
        echo "\n" . '<div class="post-thumbnail">' . "\n";
        echo $att_url_begin . get_the_post_thumbnail() . $att_url_end . "\n";
        if ($caption_text) {
            echo '<span class="wp-caption-text">' . wp_kses_post($caption_text) . '</span>' . "\n";
        }
        echo '</div>' . "\n";
    }
}
add_action('mh_post_content_top', 'mh_featured_image');

