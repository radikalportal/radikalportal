<?php

add_theme_support('post-thumbnails');

function custom_excerpt_length( $length ) {
	return 30;
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);

function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

register_nav_menu( 'primary', 'Primary Menu' );

register_sidebar();

add_action( 'init', 'create_post_type' );
function create_post_type() {
	register_post_type( 'rp_event',
		array(
			'labels' => array(
				'name' => __( 'Arrangementer' ),
				'singular_name' => __( 'Arrangement' )
			),
		'public' => false,
		'has_archive' => false,
		)
	);
}

function my_custom_post_kortnytt() {
	$labels = array(
		'name'               => _x( 'Kortnyheter', 'post type general name' ),
		'singular_name'      => _x( 'Kortnytt', 'post type singular name' ),
		'add_new'            => _x( 'Legg til ny sak', 'kortsak' ),
		'add_new_item'       => __( 'Legg til ny kortsak' ),
		'edit_item'          => __( 'Rediger kortsak' ),
		'new_item'           => __( 'Ny kortsak' ),
		'all_items'          => __( 'Alle kortsaker' ),
		'view_item'          => __( 'Vis kortsak' ),
		'search_items'       => __( 'Søk i kortsaker' ),
		'not_found'          => __( 'Ingen kortsaker ble funnet' ),
		'not_found_in_trash' => __( 'Ingen kortsaker funnet i papirkurven' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Kortnytt'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Tilpasset meny for kortnyttredaksjonen',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'categories' ),
		'has_archive'   => true,
	);
	register_post_type( 'kortnytt', $args );	
}
add_action( 'init', 'my_custom_post_kortnytt' );


function my_taxonomies_kortnytt() {
	$labels = array(
		'name'              => _x( 'Kortnytt kategorier', 'taxonomy general name' ),
		'singular_name'     => _x( 'Kortnytt kategori', 'taxonomy singular name' ),
		'search_items'      => __( 'Søk i kortnytt kategorier' ),
		'all_items'         => __( 'Alle kortnytt kategorier' ),
		'parent_item'       => __( 'Hovedkategori' ),
		'parent_item_colon' => __( 'Hoved kortnyttkategori' ),
		'edit_item'         => __( 'Rediger kortnytt kategori' ), 
		'update_item'       => __( 'Oppdater kortnytt category' ),
		'add_new_item'      => __( 'Legg til ny kortnytt kategori' ),
		'new_item_name'     => __( 'Ny kortnytt category' ),
		'menu_name'         => __( 'Kortnytt kategorier' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'query_var' => true,
	);
	register_taxonomy( 'kortnytt_category', 'kortnytt', $args );
}
add_action( 'init', 'my_taxonomies_kortnytt', 0 );





function get_first_custom_field($postid, $field) {
	$custom_fields = get_post_custom($postid);
	$my_custom_field = $custom_fields['Undertittel'];
	$value = $my_custom_field[0];
	return $value;
}

if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name'=> 'Left Sidebar',
		'id' => 'left_sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name'=> 'Right Sidebar',
		'id' => 'right_sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
}

$edit_editor = get_role('editor'); 
$edit_editor->add_cap('list_users'); 
$edit_editor->add_cap('create_users');
$edit_editor->add_cap('delete_users');


class JPB_User_Caps {

  // Add our filters
  function JPB_User_Caps(){
    add_filter( 'editable_roles', array(&$this, 'editable_roles'));
    add_filter( 'map_meta_cap', array(&$this, 'map_meta_cap'),10,4);
  }

  // Remove 'Administrator' from the list of roles if the current user is not an admin
  function editable_roles( $roles ){
    if( isset( $roles['administrator'] ) && !current_user_can('administrator') ){
      unset( $roles['administrator']);
    }
    return $roles;
  }

  // If someone is trying to edit or delete and admin and that user isn't an admin, don't allow it
  function map_meta_cap( $caps, $cap, $user_id, $args ){

    switch( $cap ){
        case 'edit_user':
        case 'remove_user':
        case 'promote_user':
            if( isset($args[0]) && $args[0] == $user_id )
                break;
            elseif( !isset($args[0]) )
                $caps[] = 'do_not_allow';
            $other = new WP_User( absint($args[0]) );
            if( $other->has_cap( 'administrator' ) ){
                if(!current_user_can('administrator')){
                    $caps[] = 'do_not_allow';
                }
            }
            break;
        case 'delete_user':
        case 'delete_users':
            if( !isset($args[0]) )
                break;
            $other = new WP_User( absint($args[0]) );
            if( $other->has_cap( 'administrator' ) ){
                if(!current_user_can('administrator')){
                    $caps[] = 'do_not_allow';
                }
            }
            break;
        default:
            break;
    }
    return $caps;
  }

}

$jpb_user_caps = new JPB_User_Caps();


remove_role( 'kortnyttredaktr' ); 

$result = add_role(
    'kortnyttredaktr',
    __( 'Kortnyttredaktør' ),
    array(
        'read'         => true,  // true allows this capability
        'edit_posts'   => true,
        'publish_posts' => true,
        'delete_posts' => false, // Use false to explicitly deny
	'edit_published_posts' => true,    
)
);
if ( null !== $result ) {
    echo '';
}
else {
    echo 'Oh... the basic_contributor role already exists.';
}


add_filter('wp_title', 'baw_hack_wp_title_for_home');
function baw_hack_wp_title_for_home($title) {
    if( empty( $title ) && ( is_home() || is_front_page() ) ) {
        return __( 'Radikal Portal - Folk, miljø og demokrati, ikke rasisme, krig og ulikhet', 'theme_domain' );
    }
    return $title;
}

function jetpackme_remove_rp() {
    $jprp = Jetpack_RelatedPosts::init();
    $callback = array( $jprp, 'filter_add_target_to_dom' );
    remove_filter( 'the_content', $callback, 40 );
}
add_filter( 'wp', 'jetpackme_remove_rp', 20 );

function jetpackme_related_posts_headline( $headline ) {
    $headline = sprintf(
        '<h3 class="page-header jp-relatedposts-headline">%s</h3>',
        esc_html( 'Relaterte' )
    );
    return $headline;
}
add_filter( 'jetpack_relatedposts_filter_headline', 'jetpackme_related_posts_headline' );
