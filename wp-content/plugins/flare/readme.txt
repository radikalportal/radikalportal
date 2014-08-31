=== Flare ===
Contributors: kynatro, dtelepathy, moonspired, nielsfogt, dtlabs, guylabs, jamie3d
Donate link: http://www.dtelepathy.com/
Tags: dtelepathy, dtlabs, sharebar, social widget, sidebar, facebook, twitter, sharethis, simple, social bar, pinterest, stumbleupon, reddit, flare, social share, social flare
Requires at least: 3.0
Tested up to: 3.8.1
License: GPL3
Stable tag: trunk

Flare is a simple yet eye-catching social sharing bar that gets you followed and lets your content get shared via posts, pages, and media types.

== Description ==

The Flare plugin isn’t in active development because we’ve created a hosted app version of Flare that works with virtually any website or CMS, including WordPress.

Flare Lite is here, and it's awesome - go [sign up for a free Filament account](http://app.filament.io/users/register) to use it.

For more info on the differences, check out [this Forum post](http://wordpress.org/support/topic/a-few-of-the-common-questions-about-the-plugin).

= &nbsp; =
= About the Flare WordPress Plugin =
Up your website's social score with a little social Flare! Easily configure and share your blog posts across some of the most popular networks.

Flare allows you to:

* Add a Follow Me widget - place widgets on your site with links to your social networks to get more followers.
* Configure multiple share icons for some of the most popular sharing services like Twitter, Facebook, Stumble Upon, Reddit, Google+ and Pinterest! More coming soon!
* Easily order your icons, customize their icons' appearance
* Control which post types your Flare appears on
* Display your Flare at the top, bottom, left or right sides of your post content
* Flare displayed on the left and right of your post follow your visitors down the page as they scroll and conveniently hide when not needed

Flare buttons work in IE7+ as well as current versions of Firefox, Chrome, Safari and Opera; vertical following does not work in IE6. Admin interface requires a modern browser (e.g. anything not IE 6-8 :). Utilizes jQuery for JavaScript processing, although it is setup to work properly with other libraries, your experience may vary. Requires PHP 5.2+.

This plugin is free to use and is not actively supported by the author, but will be monitored for serious bugs that may need correcting.

== Installation ==

The plugin is simple to install:

1. Download `flare.zip`
1. Unzip
1. Upload `flare` directory to your `/wp-content/plugins` directory
1. Go to the plugin management page and enable the plugin

== Screenshots ==

1. Choose where you want your Flare bar to appear
2. You pick what social media you want to share
3. Choose what style of button you want to display
4. Customize the color of your buttons
5. Drag and drop the order you want your buttons to appear in
6. Multiple options available for certain social content
7. You can also paste in your own custom code
8. New Follow Me widget customization

== Frequently Asked Questions ==

= Can you make the share widgets of Flare deployable via a shortcode? = 
Not really unfortunately. The Flare JavaScript to make the floating bar appear requires the presence of multiple elements on the page in different locations, so deploying via a shortcode doesn't really work.

= Can I place the Flare "Follow Me" widget non widget areas of my theme? =
You sure can! Just use the [the_widget()](http://codex.wordpress.org/the_widget "WordPress Codex Documentation: the_widget()") function to deploy the *FlareFollowWidget* widget in your template files. For example:

`<?php the_widget( 'FlareFollowWidget', array( 'title' => "Follow Me", 'iconstyle' => "round", 'iconsize' => 64, 'iconspacing' => 5 ) ); ?>`

The widget takes four "instance" parameters:

* `title` - The title of the widget area
* `iconstyle` - The style of the icon. This can be any of the keys found in the `Flare->iconstyles` array in the main `flare.php` file
* `iconsize` - The size of the icon in pixels as an integer (ex. `64` for 64 pixels)
* `iconspacing` - The spacing between the icons in pixels as an integer (ex. `10` for 10 pixels)

= My Reddit count is wrong =
Reddit fuzzes their count, so every time a count total is requested or displayed in the widget, its going to be a little different (usually +/- 20). This issue is on Reddit, so unfortunately we can't really do anything about it :(

= My Flare widgets are in a wierd position on my page =
When it all boils down to it, Flare is just HTML and CSS. We try to place the Flare widgets in a location that is as un-obtrusive as possible and works in as many layouts as possible. As there are an infinite variety of themes out there though, your website's theme might not work well with where we attempt to place Flare. The good news is, you can fix this by writing some CSS in your theme's style.css file. Use a browser inspector such as Firebug for Firefox or the developer tools for Chrome and Safari to find out what styles need modifying for your Flare layout and write some overrides in your theme's style.css file.

= My content is not in English and the social widgets are flowing outside the bubble Flare gives it =
Unfortunately we cannot check the size of the content inside the social widgets themselves as they are in IFRAME tags. We set the IFRAME tag dimensions to work pretty well for most situations, but sometimes, they need to be wider. As mentioned in the previous FAQ though, you can always write a line or two of CSS in your theme's style.css file to change the width of the bubbles.

== Changelog ==
= 1.2.7 =
* Update compatible-up-to version number

= 1.2.6 =
* Added a fix for Pinterest count

= 1.2.5 =
* Added option to disable Filament icon
* Increased width of horizontal bubbles to account for a sizing bug with the LinkedIn share button
* Added Github and Soundcloud follow icons

= 1.2.4 =
* Updated messaging to better describe the future of Flare (Pro)

= 1.2.3 =
* Load all social code via JavaScript instead of on-page code to improve load time
* Updated some admin links
* Added Powered by Filament badge
* Updated total Flares to only show totals of current sharing button types
* Added check in the_content() filter to restrict output if get_the_excerpt() is being called
* Better handling of Pinterest count retrieval

= 1.2.2 =
* Fix for handling display issue with Pinterest iFrame causing spacing issues below footer

= 1.2.1 =
* Hotfix for placement of Pinterest share button in horizontal view
* Removed the is_front_page() conditional from the valid Flare display check so Flare can still be displayed on static page front pages

= 1.2.0 =
* Added additional check for valid display to make sure Flare doesn't appear on archive pages with only one post
* Added Buffer to the button options
* Fixed a layout issue with the large vertical Pinterest button position

= 1.1.9b =
* Corrected Facebook Open Graph FQL query so that it is working correctly now

= 1.1.9a =
* Updated Facebook Like count method to utilize new Open Graph FQL query since previous Open Graph method was deprecated

= 1.1.9 =
* Fixed top horizonal totals visibility and space issue when the top horizontal bar is not supposed to be displayed.
* Added the ability to toggle a visitor's ability to hide the vertical floating Flare bar
* Changed the floating Flare bar visitor visbility close cookie to be a session cookie instead of a long term cookie
* Changed Oswald font load to use wp_enqueue_style() instead of @import in public Flare stylesheet
* Made accommodation for selecting no post types for sharing (in case you just want to use the follow widget)

= 1.1.8a =
* Hotfix for WebFontLoader compatibility
* Hotfix for total count 

= 1.1.8 =
* Fixed Twitter caching
* Added the ability to place the top and bottom Flare positions on the left or right
* Added additional CSS classes to horizontal flare output to make it easier to style theme level overrides
* Added the ability to display a total Flare Count
* Added the ability to hide your Flare scores until an article has reached a specified aggregate amount of total social shares
* Added the ability for the visitor to close the floating Flare bar (closed state remembered via a browser cookie)
* Made positioning of floating Flare bar more intelligent so that on shorter screens, scrolling to the bottom of the page will push the Flare bar up for Flare bars that are taller than the viewscreen 

= 1.1.7 =
* Fixed minor styling bug with flattened horizontal icon background positioning
* Fixed positioning of flyouts and social widgets within flyouts
* Fixed offset calculation for display of vertical social icons to accommodate for slow loading assets
* Change Pinterest image choice order to use the featured image before the first image in the content
* Fixed LinkedIn total number query to accommodate for new API response
* Fixed Reddit total number query to accommodate for variations in API response. Please note that Reddit Fuzzes the score, so the number displayed on the Flare element may be different than what is displayed in the widget - alas, we can't fix this.

= 1.1.6 =
* Updated Stumbleupon code snippet to use the new format required for their API processing

= 1.1.5 =
* Added email as a sharing method
* Added LinkedIn as a sharing method
* Added the ability to turn off counters
* Improved counter visibility
* Fixed bug with Pinterest counter that was preventing the actual count from being returned
* Restructured basic interaction with flyouts to prevent sharing service flyouts and "pop-ups" (such as Google+ +1 message customization) from disappearing when the visitor hovers away from the sharing icon. 

= 1.1.0 =
* Updated branding iconography
* Updated design of admin
* Added background color option - light, dark, none
* Fixed bug with inverted drop-down that was causing the drop-down to render in the wrong place
* Made numbering for counts intelligently group by thousands/millions
* Fixed bug that was preventing Flare from showing up on pages
* Implemented lazy PHP Class loading to optimize memory usage of the plugin
* Fixed a bug that was preventing the left/right bars from being properly hidden
* Added a new Follow Me widget!  

= 1.0.2 =
* Adjusted image URL retrieval method to return the full size image URL instead of the thumbnail for Pinterest

= 1.0.1 =
* Fixed bug with cache namespacing for total count API lookups

= 1.0.0 =
* Initial commit

== Upgrade Notice ==
= 1.2.2 =
* Update for Pinterest iFrame spacing issue below footer

= 1.1.9 =
* There were a lot of changes to the stylesheets, structure and JavaScript in 1.1.8 and 1.1.9. DO NOT FORGET TO CLEAR YOUR SERVER-SIDE CACHES.