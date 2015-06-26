<?php
/*
 * This form is meant to be extended by each album type, it provides defaults for common settings
 */
class A_NextGen_Pro_Album_Form extends Mixin_Display_Type_Form
{
    /**
     * Enqueues static resources required by this form
     */
    function enqueue_static_resources()
    {
        wp_enqueue_script(
            'nextgen_pro_albums_settings_script',
            $this->object->get_static_url('photocrati-nextgen_pro_albums#settings.js'),
            array('jquery.nextgen_radio_toggle')
        );
	
	$atp = C_Attach_Controller::get_instance();
	
	if ($atp != null && $atp->has_method('mark_script')) {
		$atp->mark_script('nextgen_pro_albums_settings_script');
	}
    }

    /**
     * Returns a list of fields to render on the settings page
     */
    function _get_field_names()
    {
        return array(
            'thumbnail_override_settings',
            'nextgen_pro_albums_display_type',
            'nextgen_pro_albums_enable_breadcrumbs',
            'nextgen_pro_albums_caption_color',
            'nextgen_pro_albums_caption_size',
            'nextgen_pro_albums_border_color',
            'nextgen_pro_albums_border_size',
            'nextgen_pro_albums_background_color',
            'nextgen_pro_albums_padding',
            'nextgen_pro_albums_spacing'
        );
    }

    /*
     * Let users choose which display type galleries inside albums use
     */
    function _render_nextgen_pro_albums_display_type_field($display_type)
    {
        $mapper = C_Display_Type_Mapper::get_instance();
        $types = array();
        foreach ($mapper->find_by_entity_type('image') as $dt) {
            $types[$dt->name] = $dt->title;
        }

        return $this->_render_select_field(
            $display_type,
            'gallery_display_type',
            __('Display galleries as', 'nextgen-gallery-pro'),
            $types,
            $display_type->settings['gallery_display_type'],
            __('How would you like galleries to be displayed?', 'nextgen-gallery-pro')
        );
    }

    function _render_nextgen_pro_albums_enable_breadcrumbs_field($display_type)
    {
        if (version_compare(NGG_PLUGIN_VERSION, '2.0.80') <= 0)
            return '';

        return $this->_render_radio_field(
            $display_type,
            'enable_breadcrumbs',
            __('Enable breadcrumbs', 'nggallery'),
            isset($display_type->settings['enable_breadcrumbs']) ? $display_type->settings['enable_breadcrumbs'] : FALSE
        );
    }

    function _render_nextgen_pro_albums_caption_color_field($display_type)
    {
        return $this->_render_color_field(
            $display_type,
            'caption_color',
            __('Caption color', 'nextgen-gallery-pro'),
            $display_type->settings['caption_color']
        );
    }

    function _render_nextgen_pro_albums_caption_size_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'caption_size',
            __('Caption size', 'nextgen-gallery-pro'),
            $display_type->settings['caption_size'],
            '',
            FALSE,
            '',
            0
        );
    }

    function _render_nextgen_pro_albums_border_color_field($display_type)
    {
        return $this->_render_color_field(
            $display_type,
            'border_color',
            __('Border color', 'nextgen-gallery-pro'),
            $display_type->settings['border_color']
        );
    }

    function _render_nextgen_pro_albums_border_size_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'border_size',
            __('Border size', 'nextgen-gallery-pro'),
            $display_type->settings['border_size'],
            '',
            FALSE,
            '',
            0
        );
    }

    function _render_nextgen_pro_albums_background_color_field($display_type)
    {
        return $this->_render_color_field(
            $display_type,
            'background_color',
            __('Background color', 'nextgen-gallery-pro'),
            $display_type->settings['background_color']
        );
    }

    function _render_nextgen_pro_albums_padding_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'padding',
            __('Padding', 'nextgen-gallery-pro'),
            $display_type->settings['padding'],
            '',
            FALSE,
            '',
            0
        );
    }

    function _render_nextgen_pro_albums_spacing_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'spacing',
            __('Spacing', 'nextgen-gallery-pro'),
            $display_type->settings['spacing'],
            '',
            FALSE,
            '',
            0
        );
    }
}