=== Open Graph ===
Contributors: willnorris, pfefferle
Tags: social, opengraph, ogp, facebook
Requires at least: 2.3
Tested up to: 6.2
Stable tag: 1.11.1
License: Apache License, Version 2.0
License URI: https://www.apache.org/licenses/LICENSE-2.0.html


Adds Open Graph metadata to your posts and pages so that they look great when shared on sites like Facebook and Twitter.

== Description ==

The [Open Graph protocol][] enables any web page to become a rich object in a social graph.  Most notably, this allows for these pages to be used with Facebook's [Like Button][] and [Graph API][] as well as within Twitter posts.

The Open Graph plugin inserts the Open Graph metadata into WordPress posts and pages, and provides a simple extension mechanism for other plugins and themes to override this data, or to provide additional Open Graph data.

This plugin does not directly add social plugins like the Facebook Like Button to your pages (though they're pretty simple to add).  It will however make your pages look great when shared using those kinds of tools.

[Open Graph Protocol]: https://ogp.me/
[Like Button]: https://developers.facebook.com/docs/reference/plugins/like
[Graph API]: https://developers.facebook.com/docs/reference/api/


== Frequently Asked Questions ==

= How do I configure the Open Graph plugin? =

You don't; there's nothing to configure and there is no admin page.  By default, it will use whatever standard WordPress data it can to populate the Open Graph data.  There are very simple yet powerful filters you can use to modify or extend the metadata returned by the plugin, described below.

= How do I extend the Open Graph plugin? =

There are two main ways to provide Open Graph metadata from your plugin or theme.  First, you can implement the filter for a specific property.  These filters are of the form `opengraph_{name}` where {name} is the unqualified Open Graph property name.  For example, if you have a plugin that defines a custom post type named "movie", you could override the Open Graph 'type' property for those posts using a function like:

    function my_og_type( $type ) {
        if ( get_post_type() == "movie" ) {
            $type = "movie";
        }
        return $type;
    }
    add_filter( 'opengraph_type', 'my_og_type' );

This will work for all of the core Open Graph properties.  However, if you want to add a custom property, such as 'fb:admin', then you would need to hook into the `opengraph_metadata` filter.  This filter is passed an associative array, whose keys are the qualified Open Graph property names.  For example:

    function my_og_metadata( $metadata ) {
        $metadata['fb:admin'] = '12345,67890';
        return $metadata;
    }
    add_filter( 'opengraph_metadata', 'my_og_metadata' );

Note that you may need to define the RDFa prefix for your properties.  Do this using the `opengraph_prefixes` filter.

= How to enable/disable "strict mode" =

The plugin populates the meta 'name' attribute alongside the 'property' attribute by default. Because both, the `og:*` and `twitter:*` names, are actually registered at https://wiki.whatwg.org/wiki/MetaExtensions, this stays compliant with the HTML5 spec. If you want to use a more strict way anyways, you can enable the scrict mode by adding the following line to your `config.php`

    define( 'OPENGRAPH_STRICT_MODE', true );


== Changelog ==

Project maintained on github at [willnorris/wordpress-opengraph](https://github.com/willnorris/wordpress-opengraph).

= version 1.11.1 (April 03, 2023) =
 - fixed a typo

= version 1.11.0 (October 21, 2021) =
 - fixed attachment issue
 - fixed PHP 7.4 issue

= version 1.10.0 (April 20, 2020) =
 - basic video support
 - basic audio support

= version 1.9.0 (Mai 14, 2019) =
 - show only featured image if available
 - prefer header images over site-icon
 - use avatar for profile pages
 - fallback to description if title is empty
 - better twitter `card` handling

= version 1.8.3 (Jan 27, 2019) =
 - added escaping for the missing attributes

= version 1.8.2 (Nov 21, 2018) =
 - fixed PHP warning issue: <https://wordpress.org/support/topic/php-warning-count-parameter-must-be-an-array-or-an-object-that-implements-c/>

= version 1.8.1 (Nov 19, 2016) =
 - change `og:image` to use the full size of image (props @torenord)

= version 1.8.0 (Jan 29, 2016) =
 - fixed `article:author` property
 - added `article:modified_time`
 - added first category as `article:section`

= version 1.7.0 (Jan 18, 2016) =
 - added "strict mode" setting
 - better twitter:card handling
 - basic twitter:creator support
 - WordPress coding standard

= version 1.6 (Dec 30, 2014) =
 - implemented `get_the_archive_title` and `get_the_archive_description` (new in WordPress 4.1)
 - basic twitter cards support (thanks to elroyjetson)
 - replace `$post->post_title` with `get_the_title()` (see #[17][] for details)

[17]: https://github.com/willnorris/wordpress-opengraph/issues/17

= version 1.5.1 (Nov 13, 2012) =
 - fix duplicate opengraph markup when used with jetpack plugin (for real)

= version 1.5 (Nov 13, 2012) =
 - include descriptions on tag and category pages
 - include profile metadata on author pages
 - fix bug with 404 pages include extra og:image values
 - general code cleanup (including removal of dependency on global vars)
 - fix duplicate opengraph markup when used with jetpack plugin

= version 1.4 (Aug 24, 2012) =
 - better default description
 - include all images that are attached to a post, so that users can choose
   which to use when sharing the page.  If the post has a post thumbnail, that
   is still used as the primary image.

= version 1.3 (May 21, 2012) =
 - add 'opengraph_prefixes' filter for defining additional prefixes
 - add new basic properties, and remove some old ones.  This is a breaking
   change for anyone that was using the old properties, but they can always be
   added using the 'opengraph_metadata' filter. (see [f476552][] for details)
 - updates to many default values, particularly for individual posts and pages
   (thanks pfefferle)
 - add basic support for array values (see [d987eb7][])

[f476552]: https://github.com/willnorris/wordpress-opengraph/commit/f47655202d59c0e5b5032b4b86764f7a87813640
[d987eb7]: https://github.com/willnorris/wordpress-opengraph/commit/d987eb76e2da1431e5df3311fde3d9c2407b06f5

= version 1.2 (Feb 21, 2012) =
 - switch to newer RDFa prefix syntax rather than XML namespaces (props
   pfefferle)

= version 1.1 (Nov 7, 2011) =
 - fix function undefined error when theme doesn't support post thumbnails

= version 1.0 (Apr 24, 2010) =
 - initial public release
