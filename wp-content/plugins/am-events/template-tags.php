<?php

/******************************************************************************
 * =COPYRIGHT
 *****************************************************************************/

/*  Copyright 2013  Atte Moisio  (email : atte.moisio@attemoisio.fi)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/******************************************************************************
 * = TEMPLATE TAGS
 **************************************************************************** */

/**
 * Display or retrieve the current event start date.
 *
 * @since 1.3.0
 *
 * @param string $before Optional. Content to prepend to the title.
 * @param string $after Optional. Content to append to the title.
 * @param string $format Optional. See PHP date() for formatting options.
 * @param bool $echo Optional, default to true. Whether to display or return.
 * @return null|string Null on no title. String if $echo parameter is false.
 */
function am_the_startdate($format = 'Y-m-d H:i:s', $before = '', $after = '', $echo = true) {

    $date = am_get_the_startdate($format);

    if ( strlen($date) == 0 )
        return;

    $date = $before . $date . $after;

    if ( $echo )
        echo $date;
    else
        return $date;
}

/**
 * Retrieve event start date.
 *
 * @since 1.3.0
 *
 * @param mixed $post Optional. Post ID or object.
 * @param string $format Optional. See PHP date() for formatting options.
 * @return string
 */
function am_get_the_startdate( $format = 'Y-m-d H:i:s', $post = 0 ) {
    
	$post = get_post( $post );

        $id = isset( $post->ID ) ? $post->ID : 0;
        $date = get_post_meta($id, 'am_startdate', true);
        
        if ( strlen($format) != 0)
            $date = date($format, strtotime($date));
        
        return $date;
        
}


/**
 * Display or retrieve the current event start date.
 *
 * @since 1.3.0
 *
 * @param string $before Optional. Content to prepend to the title.
 * @param string $after Optional. Content to append to the title.
 * @param string $format Optional. See PHP date() for formatting options.
 * @param bool $echo Optional, default to true. Whether to display or return.
 * @return null|string Null on no title. String if $echo parameter is false.
 */
function am_the_enddate($format = 'Y-m-d H:i:s', $before = '', $after = '', $echo = true) {

    $date = am_get_the_enddate($format);

    if ( strlen($date) == 0 )
        return;

    $date = $before . $date . $after;

    if ( $echo )
        echo $date;
    else
        return $date;
}

/**
 * Retrieve event start date.
 *
 * @since 1.3.0
 *
 * @param mixed $post Optional. Post ID or object.
 * @param string $format Optional. See PHP date() for formatting options.
 * @return string
 */
function am_get_the_enddate( $format = 'Y-m-d H:i:s', $post = 0 ) {
    
	$post = get_post( $post );

        $id = isset( $post->ID ) ? $post->ID : 0;
        $date = get_post_meta($id, 'am_enddate', true);
        
        if ( strlen($format) != 0)
            $date = date($format, strtotime($date));
        
        return $date;
        
}

/**
 * Retrieve event venues.
 *
 * @since 1.3.0
 * @uses $post
 *
 * @param int $id Optional, default to current post ID. The post ID.
 * @return array
 */
function am_get_the_venue( $id = false ) {
    
	$venues = get_the_terms( $id, 'am_venues' );
	if ( ! $venues || is_wp_error( $venues ) )
		$venues = array();

	$venues = array_values( $venues );

	foreach ( array_keys( $venues ) as $key ) {
		_make_cat_compat( $venues[$key] );
	}

        // Filter name is plural because we return alot of categories (possibly more than #13237) not just one
	return apply_filters( 'am_get_the_venues', $venues );
        
}

/**
 * Check if the current event in within any of the given venues.
 *
 * The given venues are checked against the event's venues' term_ids, names and slugs.
 * Venues given as integers will only be checked against the post's venues' term_ids.
 *
 * @since 1.3.0
 *
 * @param int|string|array $venue Venue ID, name or slug, or array of said.
 * @param int|object $post Optional. Event post to check instead of the current post.
 * @return bool True if the current post is in any of the given venues.
 */
function am_in_venue( $venue, $post = null ) {
	if ( empty( $venue ) )
		return false;

	return has_term( $venue, 'am_venues', $post );
}

/**
 * Retrieve venue list in either HTML list or custom format.
 *
 * @since 1.3.0
 *
 * @param string $separator Optional, default is empty string. Separator for between the venues.
 * @param string $parents Optional. How to display the parents.
 * @param int $post_id Optional. Post ID to retrieve venues.
 * @return string
 */
function am_get_the_venue_list( $separator = '', $parents='', $post_id = false ) {
	
        global $wp_rewrite;
	if ( ! is_object_in_taxonomy( get_post_type( $post_id ), 'am_venues' ) )
		return apply_filters( 'am_the_venue', '', $separator, $parents );

	$venues = am_get_the_venue( $post_id );
	if ( empty( $venues ) )
		return apply_filters( 'am_the_venue', _x( 'Unspecified', 'Venue', 'am-events' ), $separator, $parents );

	$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="venue tag"' : 'rel="venue"';

	$thelist = '';
	if ( '' == $separator ) {
		$thelist .= '<ul class="post-venues">';
		foreach ( $venues as $venue ) {
			$thelist .= "\n\t<li>";
			switch ( strtolower( $parents ) ) {
				case 'multiple':
					if ( $venue->parent )
						$thelist .= get_category_parents( $venue->parent, true, $separator );
					$thelist .= '<a href="' . esc_url( get_category_link( $venue->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all events in %s", 'Venue', 'am-events' ), $venue->name ) ) . '" ' . $rel . '>' . $venue->name.'</a></li>';
					break;
				case 'single':
					$thelist .= '<a href="' . esc_url( get_category_link( $venue->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all events in %s", 'Venue', 'am-events' ), $venue->name ) ) . '" ' . $rel . '>';
					if ( $venue->parent )
						$thelist .= get_category_parents( $venue->parent, false, $separator );
					$thelist .= $venue->name.'</a></li>';
					break;
				case '':
				default:
					$thelist .= '<a href="' . esc_url( get_category_link( $venue->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all events in %s", 'Venue', 'am-events' ), $venue->name ) ) . '" ' . $rel . '>' . $venue->name.'</a></li>';
			}
		}
		$thelist .= '</ul>';
	} else {
		$i = 0;
		foreach ( $venues as $venue ) {
			if ( 0 < $i )
				$thelist .= $separator;
			switch ( strtolower( $parents ) ) {
				case 'multiple':
					if ( $venue->parent )
						$thelist .= get_category_parents( $venue->parent, true, $separator );
					$thelist .= '<a href="' . esc_url( get_category_link( $venue->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all events in %s", 'Venue', 'am-events' ), $venue->name ) ) . '" ' . $rel . '>' . $venue->name.'</a>';
					break;
				case 'single':
					$thelist .= '<a href="' . esc_url( get_category_link( $venue->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all events in %s", 'Venue', 'am-events' ), $venue->name ) ) . '" ' . $rel . '>';
					if ( $venue->parent )
						$thelist .= get_category_parents( $venue->parent, false, $separator );
					$thelist .= "$venue->name</a>";
					break;
				case '':
				default:
					$thelist .= '<a href="' . esc_url( get_category_link( $venue->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all events in %s", 'Venue', 'am-events' ), $venue->name ) ) . '" ' . $rel . '>' . $venue->name.'</a>';
			}
			++$i;
		}
	}
	return apply_filters( 'am_the_venue', $thelist, $separator, $parents );
}

/**
 * Display the venue list for the event post.
 *
 * @since 1.3.0
 *
 * @param string $separator Optional, default is empty string. Separator for between the venues.
 * @param string $parents Optional. How to display the parents.
 * @param int $post_id Optional. Post ID to retrieve categories.
 */
function am_the_venue( $separator = '', $parents='', $post_id = false ) {
	echo am_get_the_venue_list( $separator, $parents, $post_id );
}






/**
 * Retrieve event categories.
 *
 * @since 1.3.0
 * @uses $post
 *
 * @param int $id Optional, default to current event post ID. The event post ID.
 * @return array
 */
function am_get_the_event_category( $id = false ) {
	$categories = get_the_terms( $id, 'am_event_categories' );
	if ( ! $categories || is_wp_error( $categories ) )
		$categories = array();

	$categories = array_values( $categories );

	foreach ( array_keys( $categories ) as $key ) {
		_make_cat_compat( $categories[$key] );
	}

	// Filter name is plural because we return alot of categories (possibly more than #13237) not just one
	return apply_filters( 'am_get_the_event_categories', $categories );
}


/**
 * Retrieve event category list in either HTML list or custom format.
 *
 * @since 1.3.0
 *
 * @param string $separator Optional, default is empty string. Separator for between the categories.
 * @param string $parents Optional. How to display the parents.
 * @param int $post_id Optional. Event post ID to retrieve categories.
 * @return string
 */
function am_get_the_event_category_list( $separator = '', $parents='', $post_id = false ) {
	global $wp_rewrite;
	if ( ! is_object_in_taxonomy( get_post_type( $post_id ), 'am_event_categories' ) )
		return apply_filters( 'am_the_event_category', '', $separator, $parents );

	$categories = am_get_the_event_category( $post_id );
	if ( empty( $categories ) )
		return apply_filters( 'am_the_event_category', __( 'Uncategorized' ), $separator, $parents );

	$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';

	$thelist = '';
	if ( '' == $separator ) {
		$thelist .= '<ul class="event-categories">';
		foreach ( $categories as $category ) {
			$thelist .= "\n\t<li>";
			switch ( strtolower( $parents ) ) {
				case 'multiple':
					if ( $category->parent )
						$thelist .= get_category_parents( $category->parent, true, $separator );
					$thelist .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all events in %s", "Category", "am-events" ), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a></li>';
					break;
				case 'single':
					$thelist .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all events in %s", "Category", "am-events" ), $category->name ) ) . '" ' . $rel . '>';
					if ( $category->parent )
						$thelist .= get_category_parents( $category->parent, false, $separator );
					$thelist .= $category->name.'</a></li>';
					break;
				case '':
				default:
					$thelist .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all events in %s", "Category", "am-events" ), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a></li>';
			}
		}
		$thelist .= '</ul>';
	} else {
		$i = 0;
		foreach ( $categories as $category ) {
			if ( 0 < $i )
				$thelist .= $separator;
			switch ( strtolower( $parents ) ) {
				case 'multiple':
					if ( $category->parent )
						$thelist .= get_category_parents( $category->parent, true, $separator );
					$thelist .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all events in %s", "Category", "am-events" ), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a>';
					break;
				case 'single':
					$thelist .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all events in %s", "Category", "am-events" ), $category->name ) ) . '" ' . $rel . '>';
					if ( $category->parent )
						$thelist .= get_category_parents( $category->parent, false, $separator );
					$thelist .= "$category->name</a>";
					break;
				case '':
				default:
					$thelist .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all events in %s", "Category", "am-events" ), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a>';
			}
			++$i;
		}
	}
	return apply_filters( 'am_the_event_category', $thelist, $separator, $parents );
}


/**
 * Check if the current event in within any of the given event categories.
 *
 * The given categories are checked against the post's categories' term_ids, names and slugs.
 * Categories given as integers will only be checked against the post's categories' term_ids.
 *
 *
 * @since 1.3.0
 *
 * @param int|string|array $eventCategory Event category ID, name or slug, or array of said.
 * @param int|object $post Optional. Post to check instead of the current post. (since 2.7.0)
 * @return bool True if the current post is in any of the given categories.
 */
function am_in_event_category( $eventCategory, $post = null ) {
	if ( empty( $eventCategory ) )
		return false;

	return has_term( $category, 'am_event_categories', $post );
}

/**
 * Display the event category list for the event.
 *
 * @since 1.3.0
 *
 * @param string $separator Optional, default is empty string. Separator for between the event categories.
 * @param string $parents Optional. How to display the parents.
 * @param int $post_id Optional. Post ID to retrieve event categories.
 */
function am_the_event_category( $separator = '', $parents='', $post_id = false ) {
	echo am_get_the_event_category_list( $separator, $parents, $post_id );
}

?>
