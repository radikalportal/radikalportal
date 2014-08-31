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

class AM_Upcoming_Events_Widget extends WP_Widget {
   
    /**
    * Register widget with WordPress.
    */
    function AM_Upcoming_Events_Widget() {
        parent::WP_Widget('am_upcoming_events', 'AM Upcoming Events', array('description' => __( 'Display upcoming events', 'am-events' )), array('width' => 400));
    }

    /**
    * Front-end display of widget.
    *
    * @see WP_Widget::widget()
    *
    * @param array $args     Widget arguments.
    * @param array $instance Saved values from database.
    */
    public function widget( $args, $instance ) {
        extract( $args );
        
        global $post;
        
        /* User-selected settings. */
        $title = apply_filters('widget_title', $instance['title'] );
        $venue = $instance['venue'];
        $category = $instance['category'];
        $postcount = $instance['postcount'];
        $template = $instance['template'];
        $before = $instance['before'];
        $after = $instance['after'];
        $emptyevents = $instance['emptyevents'];
		$offset = $instance['offset'];
        
        /* Before widget (defined by themes). */
        echo $before_widget;
        
        
        /* Event category filter args */
        $taxCategory = NULL;
        if ($category !== "all") {         
            $taxCategory = array(
                'taxonomy' => 'am_event_categories',
                'field' => 'name',
                'terms' => $category,
            );
        }
        
        /* Venue filter args */
        $taxVenue = NULL;
        if ($venue !== "all") {
            $taxVenue = array(
                'taxonomy' => 'am_venues',
                'field' => 'name',
                'terms' => $venue,
            );
        }

        /* WP_Query args */
        $args = array(
            'post_type' => 'am_event', // show only am_event cpt
            'post_status' => 'publish', // show only published
            'posts_per_page' => $postcount, // number of events to show
            'tax_query' => array( // taxonomy and term filter
                    'relation' => 'AND',
                    $taxCategory,
                    $taxVenue,
            ),
            // sort by meta value 'am_startdate' ascending
            'meta_key' => 'am_startdate',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => array( array(
                'key' => 'am_enddate',
                 // display events with an end date greater than 
                 // the current time - 24hrs
                'value' => date(am_get_default_date_format(), time() - intval($offset)),                
                'compare' => ">" // startdate > value
				),
            ),
            
        );
        
        /* Title of widget (before and after defined by themes). */
        if ( ! empty( $title ) )
                echo $before_title . $title . $after_title;
       
        echo $before;
        
        $loop = new WP_Query( $args );
        if (!($loop->have_posts())) {
            echo $emptyevents;
        } else {
            while ($loop->have_posts()) {
                $loop->the_post();

                $post_id = get_the_ID();

                // Old template system (1.3.1 and older)
                $output = $this->parse_event_old($template);

                // new parsing system, 1.4.0 and newer
                $output = $this->parse_event($output);

                echo $output;

             }
        }
        echo $after;

        /* After widget (defined by themes). */
        echo $after_widget;
    }
    
    /**
     * Parses the shortcodes, old method used for backward compatibility
     * @param type $content
     * @return type
     */
    protected function parse_event_old($template) {
        // get post meta
            $meta_venues = am_get_the_venue(); 
            $meta_event_categories = am_get_the_event_category(); 
            $meta_startdate = am_get_the_startdate();
            $meta_enddate = am_get_the_enddate();

            // get timestamps of dates
            $timestamp_start = strtotime($meta_startdate);
            $timestamp_end = strtotime($meta_enddate);
            
            //get all the widget template data
            $template_startdate = date(_x('m/d/Y', 'upcoming events widget', 'am-events'), $timestamp_start);
            $template_enddate = date(_x('m/d/Y', 'upcoming events widget', 'am-events'), $timestamp_end);

            $template_starttime = date( _x('H:i', 'upcoming events widget', 'am-events'), $timestamp_start);
            $template_endtime = date( _x('H:i', 'upcoming events widget', 'am-events'), $timestamp_end);

            $template_startdayname = getWeekDay(date('N', $timestamp_start));
            $template_enddayname = getWeekDay(date('N', $timestamp_end));

            $template_venue = '';
            if (count($meta_venues)  > 0)
                $template_venue = $meta_venues[0]->name;
            
            $template_event_category = '';
            if (count($meta_event_categories) > 0)
                $template_event_category = $meta_event_categories[0]->name;
            
            $template_title = get_the_title();
            
            $template_content = get_the_content();
              
            // Widget template tags
            $search = array(
                '{{start_day_name}}', 
                '{{start_date}}',
                '{{start_time}}',
                '{{end_day_name}}',
                '{{end_date}}',
                '{{end_time}}',
                '{{title}}',
                '{{event_category}}',
                '{{venue}}',
                '{{content}}',
            );
            
            $replace = array(
                $template_startdayname,
                $template_startdate,
                $template_starttime,
                $template_enddayname,
                $template_enddate,
                $template_endtime,
                $template_title,
                $template_event_category,
                $template_venue,
                $template_content,
            );
            
            return str_replace($search, $replace, $template);
    }
    
    /**
     * Parses the shortcodes
     * @param type $content
     * @return type
     * @since 1.4.0
     */
    protected function parse_event($content) {
        
        //Array of valid shortcodes
        $shortcodes = array(
            'event-title',    //The event title
            'start-date',     //The start date of the event (uses the date format from the feed options, if it is set. Otherwise uses the default WordPress date format)
            'end-date',       //The end date of the event (uses the date format from the feed options, if it is set. Otherwise uses the default WordPress date format)
            'event-venue',    //The event venue
            'event-category', //The event category
            'content',        //The event content (number of words can be limited by the 'limit' attribute)
            'permalink',      //The event post permalink
            'excerpt',      //The event excerpt
        );
        
        $regex = 
            '/\\[(\\[?)(' 
            . implode( '|', $shortcodes ) 
            . ')(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)/s';
    
        return preg_replace_callback( $regex, array( $this, 'process_shortcode' ), $content );
        
    }

    /**
     * Parses a shortcode, returning the appropriate event information
     * Much of this code is 'borrowed' from WordPress' own shortcode handling stuff!
     */
    protected function process_shortcode( $m ) {
        
        if ( '[' == $m[1] && ']' == $m[6] )
                return substr( $m[0], 1, -1 );
     
        //Extract any attributes contained in the shortcode
        extract( shortcode_atts( array(
                'format'    => '',
                'limit'     => '0',
                'link'      => 'false',
        ), shortcode_parse_atts( $m[3] ) ) );
        
        //Sanitize the attributes
        $format    = esc_attr( $format );
        $limit     = absint( $limit );
        $link  = ( 'true' === $link );
        
        // Do the appropriate stuff depending on which shortcode we're looking at.
        // See valid shortcode list (above) for explanation of each shortcode
	switch ( $m[2] ) {
            case 'event-title':
                $title = esc_html( trim( get_the_title()));
                //If a word limit has been set, trim the title to the required length
                if ( 0 != $limit ) {
                        preg_match( '/([\S]+\s*){0,' . $limit . '}/', $title , $title );
                        $title = trim( $title[0] );
                }
                if ($link) {  
                    return $m[1] . '<a href="'. get_permalink() .'">' .$title. '</a>' . $m[6];
                } else {
                    return $m[1] . $title . $m[6];
                }
                
            case 'content':
                $content = get_the_content();
                //If a word limit has been set, trim the title to the required length
                if ( 0 != $limit ) {
                        preg_match( '/([\S]+\s*){0,' . $limit . '}/', $content, $content );
                        $content = trim( $content[0] );
                }
                return $m[1] . $content . $m[6];
            case 'permalink':
                return $m[1] . get_permalink() . $m[6];
            case 'excerpt':
                $excerpt = get_the_excerpt();
                if ( 0 != $limit ) {
                        preg_match( '/([\S]+\s*){0,' . $limit . '}/', $excerpt, $excerpt );
                        $excerpt = trim( $excerpt[0] );
                }
                return $m[1] . get_the_excerpt() . $m[6];
            case 'event-category':
                $categoryArray = am_get_the_event_category();
                if (count($categoryArray) > 0) {
                    if ($link)
                        return $m[1] . '<a href="'. get_term_link($categoryArray[0]) . '">' . $categoryArray[0]->name . '</a>' . $m[6];
                    else
                        return $m[1] . $categoryArray[0]->name . $m[6];
                } else {
                    return '-';
                }
            case 'event-venue':
                $venueArray = am_get_the_venue();
                if (count($venueArray) > 0) {
                    if ($link)
                        return $m[1] . '<a href="'. get_term_link($venueArray[0]) . '">' . $venueArray[0]->name . '</a>' . $m[6];
                    else
                        return $m[1] . $venueArray[0]->name . $m[6];
                }
                else {
                    return '-';
                }
            case 'start-date':
                $startdate = am_get_the_startdate();
                $format = $format === '' ? "m/d/Y H:i" : $format;
                return $m[1] . date_i18n( $format, strtotime($startdate) ) . $m[6];
            case 'end-date':
                $enddate = am_get_the_enddate();
                $format = $format === '' ? "m/d/Y H:i" : $format;
                return $m[1] . date_i18n( $format, strtotime($enddate) ) . $m[6];
                
        }
        
    }
    
    
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        
        $default_template = "<h3>[event-title link=true]</h3>

<p>
    [start-date format='D d.m.Y H:s'] - 
    [end-date format='D d.m.Y H:s']
</p>

<p>[event-category], [event-venue]</p>

<p> [content limit=25]... <a href=\"[permalink]\">read more...</a> </p>";
                
        $defaults = array( 
            'title' => __('Upcoming Events', 'am-events'),
            'category' => 'all', 
            'venue' => 'all', 
            'postcount' => '3', 
			'offset' => 86400, // 24 hours
            'emptyevents' => '<p>No upcoming events</p>',
            'template' => $default_template, 
            'after' => '<p><a href="#">' . __('See More Events ->', 'am-events') . '</a></p>', 
            'before' => '',  
            );
        $instance = wp_parse_args( (array) $instance, $defaults );


        $title      = $instance[ 'title' ];
        $category   = $instance[ 'category' ];
        $venue      = $instance[ 'venue' ];
        $emptyevents= $instance[ 'emptyevents' ];
        $template   = $instance[ 'template' ];
        $before     = $instance[ 'before' ];
        $after      = $instance[ 'after' ];
		$offset     = $instance[ 'offset' ];
        
        $args = array( 'hide_empty' => false );
        
        $types = get_terms('am_event_categories', $args);
        $venues = get_terms('am_venues', $args);


          
        ?>
            <!-- Title -->
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'am-events')?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
            </p>
           
            
            <!-- Select event category -->
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e('Select Event category:', 'am-events')?></label><br />
            <select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>">
                <option value="all" <?php if ( $category === "all" ){ echo 'selected="selected"'; }?>><?php _e('All', 'am-events') ?></option>
                <?php foreach ($types as $t) { 
                    $typeName = $t -> name; ?>
                    <option value="<?php echo $typeName ?>" <?php if ( $category === $typeName ){ echo 'selected="selected"'; }?>><?php echo $typeName ?></option>
                <?php } ?>
            </select>
            <br />
            <br />
            
            <!-- Select event venue -->
            <label for="<?php echo $this->get_field_id( 'venue' ); ?>"><?php _e('Select Venue:', 'am-events')?></label><br />
            <select id="<?php echo $this->get_field_id( 'venue' ); ?>" name="<?php echo $this->get_field_name( 'venue' ); ?>">
                <option value="all" <?php if ( $venue === "all" ){ echo 'selected="selected"'; }?>><?php _e('All', 'am-events') ?></option>
                <?php foreach ($venues as $v) { 
                    $venueName = $v -> name; ?>
                    <option value="<?php echo $venueName ?>" <?php if ( $venue === $venueName ){ echo 'selected="selected"'; }?>><?php echo $venueName ?></option>
                <?php } ?>
            </select>
            <br />
            <br />
            
            <label for="<?php echo $this->get_field_id( 'postcount' ); ?>"><?php _e('Number of events:', 'am-events')?></label><br />
            <input type="number" id="<?php echo $this->get_field_id('postcount') ?>" name="<?php echo $this->get_field_name('postcount') ?>" type="number" min="1" value="<?php echo $instance['postcount']; ?>" />      
            <br />
            <br />
			
			<label for="<?php echo $this->get_field_id( 'offset' ); ?>"><?php _e('Keep passed events visible for:', 'am-events')?></label><br />
			<select id="<?php echo $this->get_field_id( 'offset' ); ?>" name="<?php echo $this->get_field_name( 'offset' ); ?>">
				<option value="0" <?php if ( $offset === 0 ){ echo 'selected="selected"'; }?>><?php _e("Don't keep visible", 'am-events') ?></option>
				<option value="3600" <?php if ( $offset === 3600 ){ echo 'selected="selected"'; }?>><?php _e("1 Hour", 'am-events') ?></option>
				<option value="86400" <?php if ( $offset === 86400){ echo 'selected="selected"'; }?>><?php _e("24 Hours", 'am-events') ?></option>
				<option value="604800" <?php if ( $offset === 604800){ echo 'selected="selected"'; }?>><?php _e("1 Week", 'am-events') ?></option>
			</select>
			<br />
            <br />
            
            <label for="<?php echo $this->get_field_id( 'before' ); ?>"><?php _e('Display before events:', 'am-events')?></label><br />
            <textarea class="widefat" rows="2" id="<?php echo $this->get_field_id('before') ?>" name="<?php echo $this->get_field_name( 'before' ) ?>"><?php echo $before ?></textarea>
            <br/>
            <br />
            
            <label for="<?php echo $this->get_field_id( 'template' ); ?>"><?php _e('Template for single event:', 'am-events')?></label><br />
            <textarea class="widefat" rows="10" id="<?php echo $this->get_field_id('template') ?>" name="<?php echo $this->get_field_name( 'template' ) ?>"><?php echo $template ?></textarea>
            <br />
            <br />
            
            <label for="<?php echo $this->get_field_id( 'emptyevents' ); ?>"><?php _e('Display when no events are found:', 'am-events')?></label><br />
            <textarea class="widefat" rows="2" id="<?php echo $this->get_field_id('emptyevents') ?>" name="<?php echo $this->get_field_name( 'emptyevents' ) ?>"><?php echo $emptyevents ?></textarea>
            <br />
            <br />
            
            <label for="<?php echo $this->get_field_id( 'after' ); ?>"><?php _e('Display after events:', 'am-events')?></label><br />
            <textarea class="widefat" rows="2" id="<?php echo $this->get_field_id('after') ?>" name="<?php echo $this->get_field_name( 'after' ) ?>"><?php echo $after ?></textarea>

        <?php 
    }

    /**
    * Sanitize widget form values as they are saved.
    *
    * @see WP_Widget::update()
    *
    * @param array $new_instance Values just sent to be saved.
    * @param array $old_instance Previously saved values from database.
    *
    * @return array Updated safe values to be saved.
    */
   public function update( $new_instance, $old_instance ) {
           $instance = $old_instance;
        
           $instance['title'] = strip_tags( $new_instance['title'] );
           $instance['category'] = $new_instance['category'] ;
           $instance['venue'] = $new_instance['venue'];
           $instance['postcount'] = strip_tags( $new_instance['postcount'] );
           $instance['template'] = $new_instance['template'];
           $instance['before'] = $new_instance['before'];
           $instance['after'] = $new_instance['after'];
		   $instance['offset'] = intval(strip_tags($new_instance['offset']));
           $instance['emptyevents'] = $new_instance['emptyevents'];
           
           return $instance;
   }

}

/*
 * Returns the name of the weekday based on given number (1-7)
 */
function getWeekDay($dayNumber) {
    switch($dayNumber) {
        case 1: return __('Mon', 'am-events');
        case 2: return __('Tue', 'am-events');
        case 3: return __('Wed', 'am-events');
        case 4: return __('Thu', 'am-events');
        case 5: return __('Fri', 'am-events');
        case 6: return __('Sat', 'am-events');
        case 7: return __('Sun', 'am-events');
        default: return '';
    }
}
?>