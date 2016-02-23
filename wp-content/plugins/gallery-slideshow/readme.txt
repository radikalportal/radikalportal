=== Gallery <-> Slideshow ===
Contributors: jethin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QWXNS8XBHTDCE
Tags: slideshow, gallery
Requires at least: 3.0
Tested up to: 4.2.2
Stable tag: 1.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Turn any WordPress gallery into a simple, robust, lightweight and fluid slideshow.

== Description ==

This plugin allows authors to turn a [WordPress gallery](http://codex.wordpress.org/User:Esmi/The_WordPress_Gallery) into a slideshow.

To activate slideshow mode replace the word "gallery" with "gss" in the gallery shortcode outputted by the WordPress media manager. Use "Text" mode in the visual editor to change the shortcode. For example, the default WordPress gallery shortcode:

`[gallery columns="3" ids="1,2,3"]`

becomes:

`[gss ids="1,2,3"]`

[See here](http://s89693915.onlinehome.us/wp/?page_id=4) to view an example slideshow.

To make changes to your slideshow change the shortcode back to "gallery" (in "Text" mode) and make edits using the visual editor / media manager.

The plugin supports four optional shortcode attributes:

`[gss ids="1,2,3" name="myslideshow" options="timeout=4000" style="width:50%" carousel="fx=carousel"]`

*name*: Use this attribute to give slideshow(s) unique ids (applied to container `<div>`). Give each slideshow a unique name / id when displaying multiple slideshows on a single page.

*options*: This attribute can be used to override default slideshow options or set custom options. Attribute value uses query string format, e.g.: 'option1=value1&option2=value2' etc. Option names are in standard Cycle2 format *without 'data-cycle-' prefix*. [See the Cycle2 website](http://jquery.malsup.com/cycle2/api/#options) for documentation and supported options.

*style*: Inline CSS styles applied to the slideshow container. Outputted string is prefaced with "style=" and must contain standard "property:value;" syntax.

*carousel*: Set 'fx=carousel' value in this attribute to include a carousel pager navigation (thumbnails) in a slideshow. See the [Cycle2 Carousel](http://jquery.malsup.com/cycle2/demo/carousel.php) for documentation and supported options. Carousel options follow the same string format as the options attribute above.

**Embed Slideshow (Experimental; requires version 1.3+)**

To embed a slideshow on another site:

1. Move the 'embed.js' and 'embed.php' files out of the plugin's /assets/ directory and into the main /gallery-slideshow/ directory.

1. While editing a post/page with a slideshow, locate the slideshow's embed key in the "Custom Fields" meta box . (If the embed key isn't shown make sure the [gss …] shortcode exists in the visual editor and update the page/post.)

1. Replace the all caps text below with 1) your site's URL/domain name and 2) the embed key to produce the embed code:

`<script src="YOUR_SITES_DOMAIN/wp-content/plugins/gallery-slideshow/embed.js" data-embed="GSS_EMBED_KEY" type="text/javascript"></script><div id="gss-embed"></div>`

*'data-target' attribute (optional)*: Add this attribute to the embed code to target a specific div by id (use unique ids if multiple slideshows are embedded on a page):

`<script src="YOUR_SITES_DOMAIN/wp-content/plugins/gallery-slideshow/embed.js" data-embed="GSS_EMBED_KEY" data-target="UNIQUE_TARGET_NAME" type="text/javascript"></script><div id="UNIQUE_TARGET_NAME"></div>`

**Notes**

Slideshow captions are taken from each image's "Caption" field. Upload and use unique versions of any images that are reused elsewhere on your site with different captions.

Slideshow widths should automatically adjust to the smaller of: 1) the width of the largest image in the slideshow or 2) the width of its container.

Default Display: Height / width of slideshow image area is set by the first image; images appear at full size or are scaled down to fit container; smaller images are horizontally centered; images that above 90% of the width of the slideshow are scaled to 100% width; images are bottom aligned to caption area; some white space may appear at the top of slideshows that contain both horizontally and vertically aligned images.

Default CSS ids begin with "gss_", classes with "cycle-". Default slideshow id is "gslideshow". Default CSS styles were created using the Twenty Thirteen theme -- some CSS customization may be necessary for other themes.

Slideshows perform best if images are sized to desired slideshow width / container.

Links aren't supported on images, but can be entered as HTML in image captions.

In addition to the options attribute mentioned above, slideshows can be customized by placing a "gss-custom.js" file inside the Gallery Slideshow plugin directory. An example "gss-custom.js"  can be found in the /assets/ directory.

This plugin uses [jQuery Cycle2](http://jquery.malsup.com/cycle2/). Cycle2 may conflict with previous versions of Cycle if used on the same page.

== Installation ==

1. Download and unzip the plugin file.
1. Upload the unzipped 'gallery-slideshow' directory to your '/wp-content/plugins/' directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Screenshots ==

1. The "Edit Page" admin screen showing the WP editor in text mode and a sample gss shortcode.
2. A screen capture of a GSS slideshow in the Twenty Thirteen theme. [See here](http://s89693915.onlinehome.us/wp/?page_id=4) to view a working slideshow.

== Changelog ==

= 1.3.1 =
* Fixed header bug from version 1.3 uploaded to WordPress.

= 1.3 =
* Added carousel pager (thumbnails) and embed functionalities.

= 1.2 =
* Added 'options' shortcode attribute for customized slideshows; default display changes (css); more robust Javascript functions including recentering of images after window load.

= 1.1 =
* Loads 'gss-custom.js' -- which can be used to alter default slideshow options -- if it is present in the /gallery-slideshow/ plugin directory. Sample 'gss-custom.js' file included inside /assets/ directory.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.3.1 =
Fixed header bug from version 1.3 uploaded to WordPress.

= 1.3 =
Plugin now supports carousel pager (thumbnails) navigation and embed functionality (experimental).

= 1.2 =
Plugin now supports 'options' shortcode attribute for customizing slideshows; default display and functionality improved.

= 1.1 =
Plugin now supports custom options via inclusions of optional 'gas-custom.js' file.