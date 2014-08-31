<?php 
/*
Plugin Name: Infobox
Version: 1.0
Plugin URI: http://gulbrandsoy.com/
Description: Makes nice infoboxes. Usage: [infobox]Your text[/infobox] or [infobox wideinfo]Your text[/infobox]. &lt;h3&gt; tag inside a box gives top heading and &lt;h4&gt; gives sub heading. Images auto resizes to 80x80px.
Author: Rune Gulbrandsøy and Kurt George Gjerde
Author URI: http://rune.iblogger.no
*/


function infobox($content) {
	/*
echo '<style type="text/css">
.infobox {
 border: 1px solid #ccc;
 background-color: #f8f8f8;
 color: #000;
 padding: 0 0px 10px 0px;
 font-size: 84%;
 font-family: verdana, sans-serif;
 line-height: 150%;
 clear:right;
 float: right;
 width: 300px;
 margin: 0 0 9px 20px;
}
.wideinfo{
 border: 1px solid #ccc;
 background-color: #f8f8f8;
 color: #000;
 padding: 0 0px 10px 0px;
 font-size: 84%;
 font-family: verdana, sans-serif;
 line-height: 150%;
 clear:right;
 float: left;
 width: 100%;
 margin: 0 0 9px 20px;
}
.infobox hr { display: none; }
.infobox p { margin: 10px 0 0 0;}
.infobox img { margin: 10px 0 0 10px; padding: 10px; 0 0 20px; width:80px; height:80px; }

.infobox h3,
.infobox h4 {
 display: block;
 padding: 3px 10px 5px 10px;
 margin: 0px -10px 10px -10px;
 font-size: 100%;
 font-weight: bold;
 line-height: 100%;
 background-color: #ddd;
 color: #000;
}
.infobox h4 {
 margin: 10px -10px 10px -10px;
 background-color: #eee;
 color: #000;
}
.infobox br { display: none }
</style>';
	 */

	$content = preg_replace(
'/(<p>)?\[infobox\s*?([\w\s]+?)?\s*\](<\/p>)?(.*?)(<p>)?\[\/infobox\](<\/p>)?/s',
     '<div class="infobox $2"><hr/>$4<hr/></div>',
     $content);
   return $content;
}

add_filter( 'the_content', 'infobox' );

?>
