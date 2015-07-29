<?php

/***** Load CSS & JavaScript *****/

if (!function_exists('mh_google_webfonts')) {
	function mh_google_webfonts() {
		$options = mh_theme_options();
		if ($options['google_webfonts'] == 'enable') {
			$font_body = $options['font_body'];
			$font_heading = $options['font_heading'];
			$font_location = array('armata' => 'Armata', 'arvo' => 'Arvo', 'asap' => 'Asap', 'bree_serif' => 'Bree+Serif', 'droid_sans' => 'Droid+Sans', 'droid_sans_mono' => 'Droid+Sans+Mono', 'droid_serif' => 'Droid+Serif', 'fjalla_one' => 'Fjalla+One', 'lato' => 'Lato', 'lora' => 'Lora', 'merriweather' => 'Merriweather', 'merriweather_sans' => 'Merriweather+Sans', 'monda' => 'Monda', 'nobile' => 'Nobile', 'noto_sans' => 'Noto+Sans', 'noto_serif' => 'Noto+Serif', 'open_sans' => 'Open+Sans', 'oswald' => 'Oswald', 'pt_sans' => 'PT+Sans', 'pt_serif' => 'PT+Serif', 'raleway' => 'Raleway', 'roboto' => 'Roboto', 'roboto_condensed' => 'Roboto+Condensed', 'ubuntu' => 'Ubuntu', 'yanone_kaffeesatz' => 'Yanone+Kaffeesatz');
			$font_styles_body = ':' . $options['font_styles'];
			$font_subset = '';
			if ($options['google_webfonts_subsets'] == 'latin_ext') {
				$font_subset = '&subset=latin,latin-ext';
			} elseif ($options['google_webfonts_subsets'] == 'greek') {
				$font_subset = '&subset=latin,greek';
			} elseif ($options['google_webfonts_subsets'] == 'greek_ext') {
				$font_subset = '&subset=latin,greek,greek-ext';
			} elseif ($options['google_webfonts_subsets'] == 'cyrillic') {
				$font_subset = '&subset=latin,cyrillic';
			} elseif ($options['google_webfonts_subsets'] == 'cyrillic_ext') {
				$font_subset = '&subset=latin,cyrillic,cyrillic-ext';
			}
			if ($font_location[$font_heading] != $font_location[$font_body]) {
				$font_heading = '|' . $font_location[$font_heading];
				$font_styles_heading = ':' . $options['font_styles'];
			} else {
				$font_heading = '';
				$font_styles_heading = '';
			}
			if (empty($options['font_styles'])) {
				$font_styles_body = '';
				$font_styles_heading = '';
			}
			wp_enqueue_style('mh-google-fonts', '//fonts.googleapis.com/css?family=' . $font_location[$font_body] . esc_attr($font_styles_body) . $font_heading . esc_attr($font_styles_heading) . $font_subset, array(), null);
		}
	}
}
add_action('wp_enqueue_scripts', 'mh_google_webfonts');

/***** Include Typography Custom CSS *****/

if (!function_exists('mh_fonts_css')) {
	function mh_fonts_css() {
		$options = mh_theme_options();
		if ($options['google_webfonts'] == 'enable') {
			$font_css = array('armata' => '"Armata", sans-serif', 'arvo' => '"Arvo", serif', 'asap' => '"Asap", sans-serif', 'bree_serif' => '"Bree Serif", serif', 'droid_sans' => '"Droid Sans", sans-serif', 'droid_sans_mono' => '"Droid Sans Mono", sans-serif', 'droid_serif' => '"Droid Serif", serif', 'fjalla_one' => '"Fjalla One", sans-serif', 'lato' => '"Lato", sans-serif', 'lora' => '"Lora", serif', 'merriweather' => '"Merriweather", serif', 'merriweather_sans' => '"Merriweather Sans", sans-serif', 'monda' => '"Monda", sans-serif', 'nobile' => '"Nobile", sans-serif', 'noto_sans' => '"Noto Sans", sans-serif', 'noto_serif' => '"Noto Serif", serif', 'open_sans' => '"Open Sans", sans-serif', 'oswald' => '"Oswald", sans-serif', 'pt_sans' => '"PT Sans", sans-serif', 'pt_serif' => '"PT Serif", serif', 'raleway' => '"Raleway", sans-serif', 'roboto' => 'Roboto', 'roboto_condensed' => '"Roboto Condensed", sans-serif', 'ubuntu' => '"Ubuntu", sans-serif', 'yanone_kaffeesatz' => '"Yanone Kaffeesatz", sans-serif');
			if (!empty($options['font_size']) && $options['font_size'] != '14' || $options['font_heading'] != 'open_sans' || $options['font_body'] != 'open_sans') {
				echo '<style type="text/css">' . "\n";
					if (!empty($options['font_size']) && $options['font_size'] != '14') {
						echo '.entry { font-size: ' . $options['font_size'] . 'px; font-size: ' . $options['font_size'] / 16 . 'rem; }' . "\n";
					}
					if ($options['font_heading'] != 'open_sans') {
						echo 'h1, h2, h3, h4, h5, h6, .cp-widget-title { font-family: ' . $font_css[$options['font_heading']] .'; }' . "\n";
					}
					if ($options['font_body'] != 'open_sans') {
						echo 'body { font-family: ' . $font_css[$options['font_body']] . '; }' . "\n";
					}
				echo '</style>' . "\n";
			}
		}
	}
}
add_action('wp_head', 'mh_fonts_css');

?>