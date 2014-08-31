<?php
/*
 * EXAMPLE FUNCTIONS FOR SHOWING EVENTS ON A PAGE.
 */

/**
 * Echo all upcoming events in a paged table
 * 
 * @global type $paged
 * @global type $post
 * 
 * @param type $posts_per_page How many posts are shown per page
 * @param type $event_category Filter events by event category.
 * @param type $venue Filter events by venue.
 * 
 */
function am_upcoming_events_list($posts_per_page, $event_category = 'all', $venue = 'all') {
    global $paged;
    global $post;
    $curpage = $paged ? $paged : 1;
    
    /* Event category filter args */
    $taxCategory = NULL;
    if ($event_category !== 'all') {         
        $taxCategory = array(
            'taxonomy' => 'am_event_categories',
            'field' => 'slug',
            'terms' => $event_category,
        );
    }

    /* Venue filter args */
    $taxVenue = NULL;
    if ($venue !== 'all') {
        $taxVenue = array(
            'taxonomy' => 'am_venues',
            'field' => 'name',
            'terms' => $venue,
        );
    }

    
    $args = array(
        'post_type' => 'am_event',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,  
        'paged' => $curpage,
        'orderby' => 'meta_value',
        'meta_key' => 'am_startdate',
        'order' => 'ASC',
        'meta_query' => array(
            array(
            'key' => 'am_enddate',
             // display events with a start time greater than 
             // current time - 24hrs
            'value' => date('Y-m-d H:i:s', time() - (60 * 60 * 24)),                
            'compare' => ">"
            ),
        ),
        'tax_query' => array( // taxonomy and term filter
                'relation' => 'AND',
                $taxCategory,
                $taxVenue,
        ),
    );
    
    $the_query = new WP_Query($args);

    echo '<table class="event-list">';
    echo '<thead class="event-list-header">';
    echo '<tr>
            <th>Date</th>
            <th>Title</th>
            <th>Venue</th>
            <th>Category</th>
        </tr>';

    echo '</thead>';

    echo '<tbody class="event-list-body">';

    $oddEven = 'even';
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();

            $postId = $post->ID;

            $startTimestamp = strtotime(get_post_meta($postId, 'am_startdate', true));
            $endTimestamp = strtotime(get_post_meta($postId, 'am_enddate', true));

            $startDate = date('d.m.Y', $startTimestamp);
            $endDate = date('d.m.Y', $endTimestamp);
            $startTime = date('H:i', $startTimestamp);
            $endTime = date('H:i', $endTimestamp);

            $venues = wp_get_post_terms($postId, 'am_venues');
            $eventCategories = wp_get_post_terms($postId, 'am_event_categories');

            $venueStr = '';
            foreach ($venues as $v) {
                $venueStr .= $v->name . ' ';
            }
            $categoryStr = '';
            foreach ($eventCategories as $c) {
                $categoryStr .= $c->name . ' ';
            }

            echo '<tr class="' . $oddEven . '">';
            echo '<td class="date"><span class="date">';

            if ($startDate !== $endDate) {
                echo '<strong>' . $startDate . ' </strong>' . $startTime;
                echo '<span class="date-divider">&hellip;</span><strong>' . $endDate . '</strong> ' . $endTime . ' ';
            } else if ($startDate === $endDate && $startTime !== $endTime) {
                echo '<strong>' . $startDate . '</strong><br/>' . $startTime . ' - ' . $endTime;
                ;
            } else if ($startDate === $endDate && $startTime === $endTime) {
                echo '<strong>' . $startDate . '</strong><br/>' . $startTime;
            }
            echo '</span></td>';
            echo '<td class="title"><a href="' . get_permalink() . '"> ' . get_the_title() . '</a></td> ';
            echo '<td class="venue">' . $venueStr . '</td>';
            echo '<td class="category">' . $categoryStr . '</td>';
            echo '</tr>';

            if ($oddEven === 'even')
                $oddEven = 'odd';
            else
                $oddEven = 'even';

        }
    } else { //no upcoming events
        echo '<tr><td colspan="4">No upcoming events</td></tr>';
        
    }
        
    echo '</tbody>';
    echo '</table>';
    
    am_print_event_list_pagination($the_query);
    
    wp_reset_postdata();

}

function am_print_event_list_pagination($the_query) {
    global $paged;
    $curpage = $paged ? $paged : 1;
    
    echo '<div class="am_pagination">';
    if ($curpage !== 1) {
        echo '<a class="am_first_page" title="first" href="' . get_pagenum_link(1) . '">&laquo;</a>
            <a class="am_previous" title="previous" href="' . get_pagenum_link(($curpage-1 > 0 ? $curpage-1 : 1)) . '">&lsaquo;</a>';
    }
    if ($curpage !== $the_query->max_num_pages && $the_query->max_num_pages != 1) {
    for($i=1;$i<=$the_query->max_num_pages;$i++) {
        echo '<a class="'.($i == $curpage ? 'active ' : '').'page button" href="'.get_pagenum_link($i).'">'.$i.'</a>';
    }
        echo '<a class="am_next_page" title="next" href="'.get_pagenum_link(($curpage+1 <= $the_query->max_num_pages ? $curpage+1 : $the_query->max_num_pages)).'">&rsaquo;</a>
        <a class="am_last_page" title="last" href="'.get_pagenum_link($the_query->max_num_pages).'">&raquo;</a>';
    }
        
    echo '</div>';
}

?>
