=== AM Events ===
Contributors: Moisture
Tags: event list, events, upcoming events, event list, custom post type, custom taxonomy, plugin, widget
Requires at least: 3.3.1
Tested up to: 3.9.1
Stable tag: 1.7.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds an event post type with an interface and template tags similar to normal posts. Also includes a customizable widget for upcoming events.

== Description ==

The plugin adds a custom event post type with two taxonomies: event category and venue. 

Allows the user to add new events just like normal posts with added fields for start time, end time, category and venue. You can also easily create weekly or biweekly recurring events.

Displaying the events is done in the theme files using WP_Query and the template tags provided by the plugin. This allows full control over the layout and what elements to show.

The plugin also includes a widget for showing upcoming events. It uses a very simple template system for full control of the layout.

For integrating AM Events to an existing theme, I suggest creating a [child theme](http://codex.wordpress.org/Child_Themes) with custom page templates. You can download a full example of a working Twenty Twelve child theme from my website [(Download link)](http://attemoisio.fi/projects/am-events/twentytwelve-child.zip). It contains three different page templates for events pages.

See Other Notes for detailed information and a small tutorial about the custom post type and the widget.

If you think something critical is missing, feel free to send me a request. I'm using this on some of my client's sites so I'll certainly be improving/fixing it for upcoming WordPress versions.

The plugin is available in the following languages (pot-file included for additional translations): 

* English
* French
* Norwegian
* Finnish

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload folder `am-events` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Can you give me an example of using WP_Query? =

See 'Other Notes' for a simple tutorial.

== Screenshots ==

1. Creating an event
2. Widget administration
3. Example page with events and the widget.

== Changelog ==

= 1.7.1 =
* Added French language

= 1.7.0 =
* Added option to change slug for event posts
* Added [excerpt] shortcode for the widget
* Added customizable "No upcoming events" message to widget
* Added option to change how long passed events are shown in the widget

= 1.6.0 =
* Added option to change time picker minute step
* Fixed featured image and excerpts not copying when creating recurrent events

= 1.5.1 =
* Fixed a few minor bugs

= 1.5.0 =
* Added support for thumbnail and excerpt for event posts

= 1.4.0 =
* Added new improved widget template shortcode system

= 1.3.1 =
* Fixed minor bugs in template tags

= 1.3.0 =
* Added template tags for getting and displaying event data

= 1.2.1 =
* Fixed localization typos
* Added simple WP_Query tutorial in 'Other Notes'

= 1.2.0 =
* Added support for PHP 5.2 (previously needed 5.3)
* Fixed multiple bugs

= 1.1.0 =
* Added localization to date format

= 1.0.1 =
* Fixed bugs in the upcoming events -widget
* Added missing examples.php

= 1.0.0 =
* First released/stable version

== Upgrade Notice ==

= 1.7.0 =

* Adds option to change slug for event posts
* Adds [excerpt] shortcode for the widget
* Adds customizable "No upcoming events" message to widget
* Adds option to change offset for events shown in the widget

= 1.6.0 =

* Adds option to change time picker minute step
* Fixes featured image and excerpts not copying when creating recurrent events

= 1.5.1 =

* Fixes a few minor bugs

= 1.5.0 =

* Adds support for thumbnails and excerpts

= 1.4.0 =

* Adds new improved widget shortcode system

= 1.3.1 =
* Fixes minor bugs in template tags

= 1.3.0 =
* Adds template tags for getting/displaying event data

= 1.2.1 =
* Fixes localization typos
* Adds simple WP_Query tutorial to readme.txt

= 1.2.0 =
* Adds support for php 5.2 (previously needed 5.3)
* Fixes bugs.

= 1.1.0 =
* Adds localization support for date format.

= 1.0.1 =
* Fixes bugs in the upcoming events -widget

== Widget ==

Here are the shortcodes available in the upcoming events widget template.

 * [event-title]
 * [start-date]
 * [end-date]
 * [event-venue]
 * [event-category]
 * [content]
 * [excerpt]
 * [permalink]


The title can be linked to the event post with the 'link' attribute, e.g. [event-title link=true]

The category and venue can also be linked similarly to their respective archive pages using the 'link' attribute, e.g. [event-category link=true]

The number of words displayed in the title, content or excerpt can be limited by the 'limit' attribute, e.g. [content limit=25] or [event-title limit=10].

The dates can be formatted using the 'format' attribute, e.g. [start-date format='d.m.Y H:i'] (see [PHP date](http://php.net/manual/en/function.date.php) for formatting options). If no format is given, the default WordPress date format is used.

You can use any shortcode as many times as needed in a single template. To separate date and time of start date for example you could write:
    
    [start-date format='d.m.Y'] 
    <span>divider</span>
    [start-date format='H:i']

= Template tags =

Template tags were introduced in version 1.3.0 and are listed below. More documentation can be found in the source files.

    // Template tags for getting and displaying event dates
    am_the_startdate($format = 'Y-m-d H:i:s', $before = '', $after = '', $echo = true)
    am_get_the_startdate( $format = 'Y-m-d H:i:s', $post = 0 )
    am_the_enddate($format = 'Y-m-d H:i:s', $before = '', $after = '', $echo = true)
    am_get_the_enddate( $format = 'Y-m-d H:i:s', $post = 0 )
    
    // Template tags for getting and displaying event venues
    am_get_the_venue( $id = false )
    am_in_venue( $venue, $post = null )
    am_get_the_venue_list( $separator = '', $parents='', $post_id = false )
    am_the_venue( $separator = '', $parents='', $post_id = false )

    // Template tags for getting and displaying event categories
    am_get_the_event_category( $id = false )
    am_get_the_event_category_list( $separator = '', $parents='', $post_id = false )
    am_in_event_category( $eventCategory, $post = null )
    am_the_event_category( $separator = '', $parents='', $post_id = false )

Example of displaying the first category of the current event post:
    
    $categoryArray = am_get_the_event_category();
    echo $categoryArray[0]->name;

== Creating a WP_Query ==

The custom post type is named 'am_event'
The taxonomies are named 'am_venues' and 'am_event_categories'.

The event post has metadata named 'am_startdate' and 'am_enddate' that are formatted like 'yyyy-mm-dd hh:mm'

So suppose I wanted to display all events with a category of 'other' and venue 'mcdonalds'. I would then make a WP_Query like this:

    $args = array(
            'post_type' => 'am_event',
            'post_status' => 'publish',
            'tax_query' => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'am_venues',
                        'field' => 'name',
                        'terms' => 'mcdonalds',
                    ),
                    array(
                        'taxonomy' => 'am_event_categories',
                        'field' => 'name',
                        'terms' => 'other'
                    ),
            ),
        );

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();

            $postId = $post->ID;

            // Use template tags to get start and end date
            $startDate = am_get_the_startdate('Y-m-d H:i:s');
            $endDate = am_get_the_enddate('Y-m-d H:i:s');

            // Use template tags to get venues and categories in an array
            $venues = am_get_the_venue( $postId );
            $eventCategories = am_get_the_category( $postId );

            // All the other functions used for posts like
            // the_title() and the_content() work just like with normal posts.

            // ...  DISPLAY POST CONTENT HERE ... //

        }
    }


If you want the events ordered by start date, add the following to $args:

    'orderby' => 'meta_value',
    'meta_key' => 'am_startdate',
    'order' => 'ASC',

If you need to display only upcoming events, add the following meta_query argument to $args:

    'meta_query' => array(
            array(
                'key' => 'am_enddate',
                'value' => date('Y-m-d H:i:s', time()),
                'compare' => ">",
            ),
	),

The plugin folder also contains a file "examples.php", which contains an example function for displaying upcoming events in a table.
