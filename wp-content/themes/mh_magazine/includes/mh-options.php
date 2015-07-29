<?php

function mh_customize_register($wp_customize) {

	/***** Register Custom Controls *****/

	class MH_Customize_Header_Control extends WP_Customize_Control {
        public function render_content() { ?>
			<span class="customize-control-title"><?php echo esc_html($this->label); ?></span> <?php
        }
    }

	class MH_Customize_Textarea_Control extends WP_Customize_Control {
    	public $type = 'textarea';
    	public function render_content() { ?>
            <label>
                <span class="customize-textarea"><?php echo esc_html($this->label); ?></span>
                <textarea rows="5" style="width: 100%;" <?php $this->link(); ?>><?php echo esc_textarea($this->value()); ?></textarea>
            </label> <?php
	    }
	}

	class MH_Customize_Image_Control extends WP_Customize_Image_Control {
    	public $extensions = array('jpg', 'jpeg', 'gif', 'png', 'ico');
	}

	/***** Add Sections *****/

	$wp_customize->add_section('mh_general', array('title' => __('General Options', 'mh'), 'priority' => 1));
	$wp_customize->add_section('mh_layout', array('title' => __('Layout Options', 'mh'), 'priority' => 2));
	$wp_customize->add_section('mh_typo', array('title' => __('Typography Options', 'mh'), 'priority' => 3));
	$wp_customize->add_section('mh_ticker', array('title' => __('News Ticker Options', 'mh'), 'priority' => 4));
	$wp_customize->add_section('mh_content', array('title' => __('Posts/Pages Options', 'mh'), 'priority' => 5));
	$wp_customize->add_section('mh_ads', array('title' => __('Advertising', 'mh'), 'priority' => 6));
    $wp_customize->add_section('mh_css', array('title' => __('Custom CSS', 'mh'), 'priority' => 7));
    $wp_customize->add_section('mh_tracking', array('title' => __('Tracking Code', 'mh'), 'priority' => 8));

    /***** Add Settings *****/

    $wp_customize->add_setting('mh_options[mh_favicon]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_setting('mh_options[excerpt_length]', array('default' => '175', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_integer'));
    $wp_customize->add_setting('mh_options[excerpt_more]', array('default' => '[...]', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_text'));
    $wp_customize->add_setting('mh_options[copyright]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_text'));

    $wp_customize->add_setting('mh_options[wt_layout]', array('default' => 'layout1', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[page_title_layout]', array('default' => 'layout1', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[authorbox_layout]', array('default' => 'layout1', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[author_contact]', array('default' => 'enable', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[related_layout]', array('default' => 'layout1', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[loop_layout]', array('default' => 'layout1', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[sidebars]', array('default' => 'one', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[sb_position]', array('default' => 'right', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));

	$wp_customize->add_setting('mh_options[font_size]', array('default' => '14', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_integer'));
	$wp_customize->add_setting('mh_options[google_webfonts]', array('default' => 'enable', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
	$wp_customize->add_setting('mh_options[google_webfonts_subsets]', array('default' => 'latin', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
	$wp_customize->add_setting('mh_options[font_heading]', array('default' => 'open_sans', 'type' => 'option', 'sanitize_callback' => 'esc_attr'));
	$wp_customize->add_setting('mh_options[font_body]', array('default' => 'open_sans', 'type' => 'option', 'sanitize_callback' => 'esc_attr'));
	$wp_customize->add_setting('mh_options[font_styles]', array('default' => '300,400,400italic,600,700', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_text'));

    $wp_customize->add_setting('mh_options[show_ticker]', array('default' => 1, 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_checkbox'));
    $wp_customize->add_setting('mh_options[ticker_title]', array('default' => __('News Ticker', 'mh'), 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_text'));
    $wp_customize->add_setting('mh_options[ticker_posts]', array('default' => '5', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_integer'));
    $wp_customize->add_setting('mh_options[ticker_cats]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_text'));
    $wp_customize->add_setting('mh_options[ticker_tags]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_text'));
    $wp_customize->add_setting('mh_options[ticker_offset]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_integer'));
    $wp_customize->add_setting('mh_options[ticker_sticky]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_checkbox'));

    $wp_customize->add_setting('mh_options[breadcrumbs]', array('default' => 'enable', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[teaser_text]', array('default' => 'enable', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[featured_image]', array('default' => 'enable', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[link_featured_image]', array('default' => 'disable', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[comments_pages]', array('default' => 'disable', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[post_nav]', array('default' => 'enable', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[social_buttons]', array('default' => 'both_social', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
	$wp_customize->add_setting('mh_options[post_meta_header]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'esc_attr'));
    $wp_customize->add_setting('mh_options[post_meta_date]', array('default' => 0, 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_checkbox'));
    $wp_customize->add_setting('mh_options[post_meta_author]', array('default' => 0, 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_checkbox'));
    $wp_customize->add_setting('mh_options[post_meta_cat]', array('default' => 0, 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_checkbox'));
    $wp_customize->add_setting('mh_options[post_meta_comments]', array('default' => 0, 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_checkbox'));

	$wp_customize->add_setting('mh_options[content_ad]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_textarea'));
	$wp_customize->add_setting('mh_options[loop_ad]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_textarea'));
	$wp_customize->add_setting('mh_options[loop_ad_no]', array('default' => '3', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_integer'));

    $wp_customize->add_setting('mh_options[custom_css]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_textarea'));
    $wp_customize->add_setting('mh_options[tracking_code]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_textarea'));

    $wp_customize->add_setting('mh_options[color_bg_header]', array('default' => '#ffffff', 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color'));
    $wp_customize->add_setting('mh_options[color_bg_inner]', array('default' => '#ffffff', 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color'));
    $wp_customize->add_setting('mh_options[color_1]', array('default' => '#2a2a2a', 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color'));
    $wp_customize->add_setting('mh_options[color_2]', array('default' => '#e64946', 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color'));
    $wp_customize->add_setting('mh_options[color_text_general]', array('default' => '#000000', 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color'));
    $wp_customize->add_setting('mh_options[color_text_1]', array('default' => '#ffffff', 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color'));
    $wp_customize->add_setting('mh_options[color_text_2]', array('default' => '#ffffff', 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color'));
    $wp_customize->add_setting('mh_options[color_text_meta]', array('default' => '#979797', 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color'));
    $wp_customize->add_setting('mh_options[color_links', array('default' => '#000000', 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color'));
	$wp_customize->add_setting('mh_options[color_links_hover', array('default' => '#e64946', 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color'));

    $wp_customize->add_setting('mh_options[full_bg]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_checkbox'));

    /***** Add Controls *****/

    $wp_customize->add_control(new MH_Customize_Image_Control($wp_customize, 'mh_favicon', array('label' => esc_html__('Favicon Upload', 'mh'), 'section' => 'mh_general', 'settings' => 'mh_options[mh_favicon]', 'priority' => 1)));
    $wp_customize->add_control('excerpt_length', array('label' => esc_html__('Custom Excerpt Length in Characters', 'mh'), 'section' => 'mh_general', 'settings' => 'mh_options[excerpt_length]', 'priority' => 2, 'type' => 'text'));
    $wp_customize->add_control('excerpt_more', array('label' => esc_html__('Custom Excerpt More-Text', 'mh'), 'section' => 'mh_general', 'settings' => 'mh_options[excerpt_more]', 'priority' => 3, 'type' => 'text'));
    $wp_customize->add_control('copyright', array('label' => esc_html__('Copyright Text', 'mh'), 'section' => 'mh_general', 'settings' => 'mh_options[copyright]', 'priority' => 4, 'type' => 'text'));

    $wp_customize->add_control('wt_layout', array('label' => esc_html__('Widget Titles', 'mh'), 'section' => 'mh_layout', 'settings' => 'mh_options[wt_layout]', 'priority' => 1, 'type' => 'select', 'choices' => array('layout1' => sprintf(_x('Layout %d', 'options panel', 'mh'), 1), 'layout2' => sprintf(_x('Layout %d', 'options panel', 'mh'), 2), 'layout3' => sprintf(_x('Layout %d', 'options panel', 'mh'), 3))));
    $wp_customize->add_control('page_title_layout', array('label' => esc_html__('Page Titles', 'mh'), 'section' => 'mh_layout', 'settings' => 'mh_options[page_title_layout]', 'priority' => 2, 'type' => 'select', 'choices' => array('layout1' => sprintf(_x('Layout %d', 'options panel', 'mh'), 1), 'layout2' => sprintf(_x('Layout %d', 'options panel', 'mh'), 2))));
    $wp_customize->add_control('authorbox_layout', array('label' => esc_html__('Author Box', 'mh'), 'section' => 'mh_layout', 'settings' => 'mh_options[authorbox_layout]', 'priority' => 3, 'type' => 'select', 'choices' => array('layout1' => sprintf(_x('Layout %d', 'options panel', 'mh'), 1), 'layout2' => sprintf(_x('Layout %d', 'options panel', 'mh'), 2), 'disable' => __('Disable', 'mh'))));
    $wp_customize->add_control('author_contact', array('label' => esc_html__('Author Box Contact', 'mh'), 'section' => 'mh_layout', 'settings' => 'mh_options[author_contact]', 'priority' => 4, 'type' => 'select', 'choices' => array('enable' => __('Enable', 'mh'), 'disable' => __('Disable', 'mh'))));
    $wp_customize->add_control('related_layout', array('label' => esc_html__('Related Articles', 'mh'), 'section' => 'mh_layout', 'settings' => 'mh_options[related_layout]', 'priority' => 5, 'type' => 'select', 'choices' => array('layout1' => sprintf(_x('Layout %d', 'options panel', 'mh'), 1), 'layout2' => sprintf(_x('Layout %d', 'options panel', 'mh'), 2), 'disable' => __('Disable', 'mh'))));
    $wp_customize->add_control('loop_layout', array('label' => esc_html__('Archives', 'mh'), 'section' => 'mh_layout', 'settings' => 'mh_options[loop_layout]', 'priority' => 6, 'type' => 'select', 'choices' => array('layout1' => sprintf(_x('Layout %d', 'options panel', 'mh'), 1), 'layout2' => sprintf(_x('Layout %d', 'options panel', 'mh'), 2), 'layout3' => sprintf(_x('Layout %d', 'options panel', 'mh'), 3))));
    $wp_customize->add_control('sidebars', array('label' => esc_html__('Sidebars', 'mh'), 'section' => 'mh_layout', 'settings' => 'mh_options[sidebars]', 'priority' => 7, 'type' => 'select', 'choices' => array('one' => __('One Sidebar', 'mh'), 'two' => __('Two Sidebars', 'mh'), 'no' => __('No Sidebars', 'mh'))));
    $wp_customize->add_control('sb_position', array('label' => esc_html__('Position of default Sidebar', 'mh'), 'section' => 'mh_layout', 'settings' => 'mh_options[sb_position]', 'priority' => 8, 'type' => 'select', 'choices' => array('left' => __('Left', 'mh'), 'right' => __('Right', 'mh'))));

	$wp_customize->add_control('font_size', array('label' => esc_html__('Change default Font Size (px)', 'mh'), 'section' => 'mh_typo', 'settings' => 'mh_options[font_size]', 'priority' => 1, 'type' => 'text'));
	$google_fonts = array('armata' => 'Armata', 'arvo' => 'Arvo', 'asap' => 'Asap', 'bree_serif' => 'Bree Serif', 'droid_sans' => 'Droid Sans', 'droid_sans_mono' => 'Droid Sans Mono', 'droid_serif' => 'Droid Serif', 'fjalla_one' => 'Fjalla One', 'lato' => 'Lato', 'lora' => 'Lora', 'merriweather' => 'Merriweather', 'merriweather_sans' => 'Merriweather Sans', 'monda' => 'Monda', 'nobile' => 'Nobile', 'noto_sans' => 'Noto Sans', 'noto_serif' => 'Noto Serif', 'open_sans' => 'Open Sans', 'oswald' => 'Oswald', 'pt_sans' => 'PT Sans', 'pt_serif' => 'PT Serif', 'raleway' => 'Raleway', 'roboto' => 'Roboto', 'roboto_condensed' => 'Roboto Condensed', 'ubuntu' => 'Ubuntu', 'yanone_kaffeesatz' => 'Yanone Kaffeesatz');
    $wp_customize->add_control('google_webfonts', array('label' => esc_html__('Google Webfonts', 'mh'), 'section' => 'mh_typo', 'settings' => 'mh_options[google_webfonts]', 'priority' => 2, 'type' => 'select', 'choices' => array('enable' => __('Enable', 'mh'), 'disable' => __('Disable', 'mh'))));
	$wp_customize->add_control('google_webfonts_subsets', array('label' => esc_html__('Google Webfonts Characters', 'mh'), 'section' => 'mh_typo', 'settings' => 'mh_options[google_webfonts_subsets]', 'priority' => 3, 'type' => 'select', 'choices' => array('latin' => __('Latin', 'mh'), 'latin_ext' => __('Latin Extended', 'mh'), 'greek' => __('Greek', 'mh'), 'greek_ext' => __('Greek Extended', 'mh'), 'cyrillic' => __('Cyrillic', 'mh'), 'cyrillic_ext' => __('Cyrillic Extended', 'mh'))));
	$wp_customize->add_control('font_heading', array('label' => esc_html__('Google Webfont for Headings', 'mh'), 'section' => 'mh_typo', 'settings' => 'mh_options[font_heading]', 'priority' => 4, 'type' => 'select', 'choices' => $google_fonts));
	$wp_customize->add_control('font_body', array('label' => esc_html__('Google Webfont for Body Text', 'mh'), 'section' => 'mh_typo', 'settings' => 'mh_options[font_body]', 'priority' => 5, 'type' => 'select', 'choices' => $google_fonts));
	$wp_customize->add_control('font_styles', array('label' => esc_html__('Imported Google Font Styles', 'mh'), 'section' => 'mh_typo', 'settings' => 'mh_options[font_styles]', 'priority' => 6, 'type' => 'text'));

	$wp_customize->add_control('show_ticker', array('label' => esc_html__('Enable Ticker', 'mh'), 'section' => 'mh_ticker', 'settings' => 'mh_options[show_ticker]', 'priority' => 1, 'type' => 'checkbox'));
    $wp_customize->add_control('ticker_title', array('label' => esc_html__('Ticker Title', 'mh'), 'section' => 'mh_ticker', 'settings' => 'mh_options[ticker_title]', 'priority' => 2, 'type' => 'text'));
    $wp_customize->add_control('ticker_posts', array('label' => esc_html__('Limit Post Number', 'mh'), 'section' => 'mh_ticker', 'settings' => 'mh_options[ticker_posts]', 'priority' => 3, 'type' => 'text'));
    $wp_customize->add_control('ticker_cats', array('label'=> esc_html__('Custom Categories (use ID - e.g. 3,5,9):', 'mh'), 'section' => 'mh_ticker', 'settings' => 'mh_options[ticker_cats]', 'priority' => 4, 'type' => 'text'));
    $wp_customize->add_control('ticker_tags', array('label' => esc_html__('Custom Tags (use slug - e.g. lifestyle):', 'mh'), 'section' => 'mh_ticker', 'settings' => 'mh_options[ticker_tags]', 'priority' => 5, 'type' => 'text'));
    $wp_customize->add_control('ticker_offset', array('label' => esc_html__('Skip Posts (Offset):', 'mh'), 'section' => 'mh_ticker', 'settings' => 'mh_options[ticker_offset]', 'priority' => 6, 'type' => 'text'));
	$wp_customize->add_control('ticker_sticky', array('label' => esc_html__('Ignore Sticky Posts', 'mh'), 'section' => 'mh_ticker', 'settings' => 'mh_options[ticker_sticky]', 'priority' => 7, 'type' => 'checkbox'));

	$wp_customize->add_control('breadcrumbs', array('label' => esc_html__('Breadcrumb Navigation', 'mh'), 'section' => 'mh_content', 'settings' => 'mh_options[breadcrumbs]', 'priority' => 1, 'type' => 'select', 'choices' => array('enable' => __('Enable', 'mh'), 'disable' => __('Disable', 'mh'))));
    $wp_customize->add_control('teaser_text', array('label' => esc_html__('Teaser Text on Posts', 'mh'), 'section' => 'mh_content', 'settings' => 'mh_options[teaser_text]', 'priority' => 2, 'type' => 'select', 'choices' => array('enable' => __('Enable', 'mh'), 'disable' => __('Disable', 'mh'))));
    $wp_customize->add_control('featured_image', array('label' => esc_html__('Featured Image on Posts', 'mh'), 'section' => 'mh_content', 'settings' => 'mh_options[featured_image]', 'priority' => 3, 'type' => 'select', 'choices' => array('enable' => __('Enable', 'mh'), 'disable' => __('Disable', 'mh'))));
    $wp_customize->add_control('link_featured_image', array('label' => esc_html__('Link Featured Image to Attachment', 'mh'), 'section' => 'mh_content', 'settings' => 'mh_options[link_featured_image]', 'priority' => 4, 'type' => 'select', 'choices' => array('enable' => __('Enable', 'mh'), 'disable' => __('Disable', 'mh'))));
    $wp_customize->add_control('comments_pages', array('label' => esc_html__('Comments on Pages', 'mh'), 'section' => 'mh_content', 'settings' => 'mh_options[comments_pages]', 'priority' => 5, 'type' => 'select', 'choices' => array('enable' => __('Enable', 'mh'), 'disable' => __('Disable', 'mh'))));
    $wp_customize->add_control('post_nav', array('label' => esc_html__('Post/Attachment Navigation', 'mh'), 'section' => 'mh_content', 'settings' => 'mh_options[post_nav]', 'priority' => 6, 'type' => 'select', 'choices' => array('enable' => __('Enable', 'mh'), 'disable' => __('Disable', 'mh'))));
    $wp_customize->add_control('social_buttons', array('label' => esc_html__('Social Buttons on Posts', 'mh'), 'section' => 'mh_content', 'settings' => 'mh_options[social_buttons]', 'priority' => 7, 'type' => 'select', 'choices' => array('both_social' => __('Top and bottom', 'mh'), 'top_social' => __('Top of posts', 'mh'), 'bottom_social' => __('Bottom of posts', 'mh'), 'disable' => __('Disable', 'mh'))));
	$wp_customize->add_control(new MH_Customize_Header_Control($wp_customize, 'post_meta_header', array('label' => esc_html__('Disable Post Meta Data', 'mh'), 'section' => 'mh_content', 'settings' => 'mh_options[post_meta_header]', 'priority' => 8)));
    $wp_customize->add_control('post_meta_date', array('label' => esc_html__('Disable Date', 'mh'), 'section' => 'mh_content', 'settings' => 'mh_options[post_meta_date]', 'priority' => 9, 'type' => 'checkbox'));
    $wp_customize->add_control('post_meta_author', array('label' => esc_html__('Disable Author', 'mh'), 'section' => 'mh_content', 'settings' => 'mh_options[post_meta_author]', 'priority' => 10, 'type' => 'checkbox'));
    $wp_customize->add_control('post_meta_cat', array('label' => esc_html__('Disable Categories', 'mh'), 'section' => 'mh_content', 'settings' => 'mh_options[post_meta_cat]', 'priority' => 11, 'type' => 'checkbox'));
    $wp_customize->add_control('post_meta_comments', array('label' => esc_html__('Disable Comments', 'mh'), 'section' => 'mh_content', 'settings' => 'mh_options[post_meta_comments]', 'priority' => 12, 'type' => 'checkbox'));

	$wp_customize->add_control(new MH_Customize_Textarea_Control($wp_customize, 'content_ad', array('label' => esc_html__('Ad Code for Content Ad on Posts', 'mh'), 'section' => 'mh_ads', 'settings' => 'mh_options[content_ad]', 'priority' => 1)));
	$wp_customize->add_control(new MH_Customize_Textarea_Control($wp_customize, 'loop_ad', array('label' => esc_html__('Ad Code for Ads on Archives', 'mh'), 'section' => 'mh_ads', 'settings' => 'mh_options[loop_ad]', 'priority' => 2)));
	$wp_customize->add_control('loop_ad_no', array('label'=> esc_html__('Display Ad every x Posts on Archives:', 'mh'), 'section' => 'mh_ads', 'settings' => 'mh_options[loop_ad_no]', 'priority' => 3, 'type' => 'text'));

    $wp_customize->add_control(new MH_Customize_Textarea_Control($wp_customize, 'custom_css', array('label' => esc_html__('Custom CSS', 'mh'), 'section' => 'mh_css', 'settings' => 'mh_options[custom_css]', 'priority' => 1)));
    $wp_customize->add_control(new MH_Customize_Textarea_Control($wp_customize, 'tracking_code', array('label' => esc_html__('Tracking Code (e.g. Google Analytics)', 'mh'), 'section' => 'mh_tracking', 'settings' => 'mh_options[tracking_code]', 'priority' => 1)));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_bg_header', array('label' => esc_html__('Background Header', 'mh'), 'section' => 'colors', 'settings' => 'mh_options[color_bg_header]', 'priority' => 50)));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_bg_inner', array('label' => esc_html__('Background Inner', 'mh'), 'section' => 'colors', 'settings' => 'mh_options[color_bg_inner]', 'priority' => 51)));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_1', array('label' => sprintf(esc_html_x('Theme Color %d', 'options panel', 'mh'), 1), 'section' => 'colors', 'settings' => 'mh_options[color_1]', 'priority' => 52)));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_2', array('label' => sprintf(esc_html_x('Theme Color %d', 'options panel', 'mh'), 2), 'section' => 'colors', 'settings' => 'mh_options[color_2]', 'priority' => 53)));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_text_general', array('label' => esc_html__('Text: General', 'mh'), 'section' => 'colors', 'settings' => 'mh_options[color_text_general]', 'priority' => 54)));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_text_1', array('label' => sprintf(esc_html_x('Text: Colored Sections (Color %d)', 'options panel', 'mh'), 1), 'section' => 'colors', 'settings' => 'mh_options[color_text_1]', 'priority' => 55)));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_text_2', array('label' => sprintf(esc_html_x('Text: Colored Sections (Color %d)', 'options panel', 'mh'), 2), 'section' => 'colors', 'settings' => 'mh_options[color_text_2]', 'priority' => 56)));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_text_meta', array('label' => esc_html__('Text: Post Meta', 'mh'), 'section' => 'colors', 'settings' => 'mh_options[color_text_meta]', 'priority' => 57)));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_links', array('label' => esc_html__('Links: General Color', 'mh'), 'section' => 'colors', 'settings' => 'mh_options[color_links]', 'priority' => 58)));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_links_hover', array('label' => esc_html__('Links: Hover Color', 'mh'), 'section' => 'colors', 'settings' => 'mh_options[color_links_hover]', 'priority' => 59)));

	$wp_customize->add_control('full_bg', array('label' => esc_html__('Scale Background Image to Full Size', 'mh'), 'section' => 'background_image', 'settings' => 'mh_options[full_bg]', 'priority' => 99, 'type' => 'checkbox'));
}
add_action('customize_register', 'mh_customize_register');

/***** Data Sanitization *****/

function mh_sanitize_text($input) {
    return wp_kses_post(force_balance_tags($input));
}
function mh_sanitize_textarea($input) {
    if (current_user_can('unfiltered_html')) {
		return $input;
    } else {
		return stripslashes(wp_filter_post_kses(addslashes($input)));
    }
}
function mh_sanitize_integer($input) {
    return strip_tags(intval($input));
}
function mh_sanitize_checkbox($input) {
    if ($input == 1) {
        return 1;
    } else {
        return '';
    }
}
function mh_sanitize_select($input) {
    $valid = array(
        'one' => __('One Sidebar', 'mh'),
        'two' => __('Two Sidebars', 'mh'),
        'no' => __('No Sidebars', 'mh'),
        'enable' => __('Enable', 'mh'),
        'disable' => __('Disable', 'mh'),
        'left' => __('Left', 'mh'),
        'right' => __('Right', 'mh'),
        'latin' => __('Latin', 'mh'),
        'latin_ext' => __('Latin Extended', 'mh'),
        'greek' => __('Greek', 'mh'),
        'greek_ext' => __('Greek Extended', 'mh'),
        'cyrillic' => __('Cyrillic', 'mh'),
        'cyrillic_ext' => __('Cyrillic Extended', 'mh'),
        'layout1' => sprintf(_x('Layout %d', 'options panel', 'mh'), 1),
        'layout2' => sprintf(_x('Layout %d', 'options panel', 'mh'), 2),
        'layout3' => sprintf(_x('Layout %d', 'options panel', 'mh'), 3),
        'both_social' => __('Top and Bottom', 'mh'),
        'top_social' => __('Top of Posts', 'mh'),
        'bottom_social' => __('Bottom of Posts', 'mh')
    );
    if (array_key_exists($input, $valid)) {
        return $input;
    } else {
        return '';
    }
}

/***** Return Theme Options / Set Default Options *****/

if (!function_exists('mh_theme_options')) {
	function mh_theme_options() {
		$theme_options = wp_parse_args(
			get_option('mh_options', array()),
			mh_magazine_default_options()
		);
		return $theme_options;
	}
}

if (!function_exists('mh_magazine_default_options')) {
	function mh_magazine_default_options() {
		$default_options = array(
			'mh_favicon' => '',
			'excerpt_length' => '175',
			'excerpt_more' => '[...]',
			'wt_layout' => 'layout1',
			'page_title_layout' => 'layout1',
			'authorbox_layout' => 'layout1',
			'author_contact' => 'enable',
			'related_layout' => 'layout1',
			'loop_layout' => 'layout1',
			'sidebars' => 'one',
			'sb_position' => 'right',
			'font_size' => '14',
			'google_webfonts' => 'enable',
			'google_webfonts_subsets' => 'latin',
			'font_heading' => 'open_sans',
			'font_body' => 'open_sans',
			'font_styles' => '300,400,400italic,600,700',
			'show_ticker' => 1,
			'ticker_title' => __('News Ticker', 'mh'),
			'ticker_posts' => '5',
			'ticker_cats' => '',
			'ticker_tags' => '',
			'ticker_offset' => '',
			'ticker_sticky' => 0,
			'breadcrumbs' => 'enable',
			'teaser_text' => 'enable',
			'featured_image' => 'enable',
			'link_featured_image' => 'disable',
			'comments_pages' => 'disable',
			'post_nav' => 'enable',
			'social_buttons' => 'both_social',
			'post_meta_date' => 0,
			'post_meta_author' => 0,
			'post_meta_cat' => 0,
			'post_meta_comments' => 0,
			'content_ad' => '',
			'loop_ad' => '',
			'loop_ad_no' => '3',
			'custom_css' => '',
			'tracking_code' => '',
			'color_bg_inner' => '#ffffff',
			'color_bg_header' => '#ffffff',
			'color_1' => '#2a2a2a',
			'color_2' => '#e64946',
			'color_text_general' => '#000000',
			'color_text_1' => '#ffffff',
			'color_text_2' => '#ffffff',
			'color_text_meta' => '#979797',
			'color_links' => '#000000',
			'color_links_hover' => '#e64946',
			'full_bg' => ''
		);
		return $default_options;
	}
}

/***** Enqueue Customizer CSS *****/

function mh_customizer_css() {
	wp_enqueue_style('mh-customizer-css', get_template_directory_uri() . '/admin/customizer.css', array());
}
add_action('customize_controls_print_styles', 'mh_customizer_css');

/***** CSS Output *****/

function mh_custom_css() {
	$options = mh_theme_options();
	if ($options['color_bg_header'] != '#ffffff' || $options['color_bg_inner'] != '#ffffff' || $options['color_1'] != '#2a2a2a' || $options['color_2'] != '#e64946' || $options['color_text_general'] != '#000000' || $options['color_text_1'] != '#ffffff' || $options['color_text_2'] != '#ffffff' || $options['color_text_meta'] != '#979797' || $options['color_links'] != '#000000' || $options['color_links_hover'] != '#e64946' || $options['custom_css']) : ?>
    <style type="text/css">
    	<?php if ($options['color_bg_header'] != '#ffffff') { ?>
    		.header-wrap { background: <?php echo $options['color_bg_header']; ?> }
    	<?php } ?>
    	<?php if ($options['color_bg_inner'] != '#ffffff') { ?>
    		.mh-wrapper { background: <?php echo $options['color_bg_inner']; ?> }
    	<?php } ?>
    	<?php if ($options['color_1'] != '#2a2a2a') { ?>
    		.main-nav, .header-nav .menu .menu-item:hover > .sub-menu, .main-nav .menu .menu-item:hover > .sub-menu, .slide-caption, .spotlight, .carousel-layout1, footer, .loop-layout2 .loop-wrap .meta,
    		.loop-layout3 .loop-wrap .meta, input[type=submit]:hover, #cancel-comment-reply-link:hover, .copyright, #infinite-handle span:hover { background: <?php echo $options['color_1']; ?>; }
    		.slicknav_menu, .slicknav_nav ul { border-color: <?php echo $options['color_1']; ?>; }
    		.copyright, .copyright a { color: #fff; }
    	<?php } ?>
    	<?php if ($options['color_2'] != '#e64946') { ?>
    		.ticker-title, .header-nav .menu-item:hover, .main-nav li:hover, .footer-nav, .footer-nav ul li:hover > ul, .slicknav_menu, .slicknav_btn, .slicknav_nav .slicknav_item:hover,
    		.slicknav_nav a:hover, .slider-layout2 .flex-control-paging li a.flex-active, .flex-control-paging li a.flex-active, .sl-caption, .subheading, .pt-layout1 .page-title, .wt-layout2 .widget-title, .wt-layout2 .footer-widget-title,
    		.carousel-layout1 .caption, .page-numbers:hover, .current, .pagelink, a:hover .pagelink, input[type=submit], #cancel-comment-reply-link, .post-tags li:hover, .tagcloud a:hover, .sb-widget .tagcloud a:hover, .footer-widget .tagcloud a:hover, #infinite-handle span { background: <?php echo $options['color_2']; ?>; }
    		.slide-caption, .mh-mobile .slide-caption, [id*='carousel-'], .wt-layout1 .widget-title, .wt-layout1 .footer-widget-title, .wt-layout3 .widget-title, .wt-layout3 .footer-widget-title,
    		.ab-layout1 .author-box, .cat-desc, textarea:hover, input[type=text]:hover, input[type=email]:hover, input[type=tel]:hover, input[type=url]:hover, blockquote { border-color: <?php echo $options['color_2']; ?>; }
    		.dropcap, .carousel-layout2 .caption { color: <?php echo $options['color_2']; ?>; }
    	<?php } ?>
    	<?php if ($options['color_text_general'] != '#000000') { ?>
    		body, .mh-content h1, .pt-layout2 .mh-content .page-title, .entry h1, .entry h2, .entry h3, .entry h4, .entry h5 .entry h6, .wp-caption .wp-caption-text, .post-thumbnail .wp-caption-text { color: <?php echo $options['color_text_general']; ?>; }
    	<?php } ?>
    	<?php if ($options['color_text_1'] != '#ffffff') { ?>
    		.main-nav li a, footer, .footer-widget-title, .spotlight, .sl-title, .spotlight .mh-excerpt a, .slide-title, .slide-caption, .slide-caption .mh-excerpt a, .caption, .copyright, .copyright a, #infinite-handle span:hover { color: <?php echo $options['color_text_1']; ?>; }
    	<?php } ?>
    	<?php if ($options['color_text_2'] != '#ffffff') { ?>
    		.header-nav a:hover, .header-nav li:hover > a, .main-nav a:hover, .main-nav li:hover > a, .ticker-title, .subheading, .pt-layout1 .mh-content .page-title, .caption, .carousel-layout1 .caption, .sl-caption, input[type=submit], .footer-nav li a, .slicknav_nav a,
    		.slicknav_nav a:hover, .slicknav_nav .slicknav_item:hover, .slicknav_menu .slicknav_menutxt, .tagcloud a:hover, .sb-widget .tagcloud a:hover, .post-tags a:hover, .page-numbers:hover, .mh-content .current, .pagelink, a:hover .pagelink, #infinite-handle span { color: <?php echo $options['color_text_2']; ?>; }
			.slicknav_menu .slicknav_icon-bar { background: <?php echo $options['color_text_2']; ?>; }
    	<?php } ?>
    	<?php if ($options['color_text_meta'] != '#979797') { ?>
    		.meta, .meta a, .breadcrumb, .breadcrumb a { color: <?php echo $options['color_text_meta']; ?>; }
    	<?php } ?>
    	<?php if ($options['color_links'] != '#000000') { ?>
    		a, .entry a, .related-title, .carousel-layout2 .carousel-item-title, a .pagelink, .page-numbers { color: <?php echo $options['color_links']; ?>; }
    	<?php } ?>
    	<?php if ($options['color_links_hover'] != '#e64946') { ?>
    		a:hover, .meta a:hover, .breadcrumb a:hover, .related-title:hover, #ticker a:hover .meta, .slide-title:hover, .sl-title:hover, .carousel-layout2 .carousel-item-title:hover { color: <?php echo $options['color_links_hover']; ?>; }
    	<?php } ?>
    	<?php if ($options['custom_css']) {	echo $options['custom_css']; } ?>
	</style>
    <?php
	endif;
}
add_action('wp_head', 'mh_custom_css');

?>