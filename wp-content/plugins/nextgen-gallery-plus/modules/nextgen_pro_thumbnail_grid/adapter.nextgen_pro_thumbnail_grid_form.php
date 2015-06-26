<?php

class A_NextGen_Pro_Thumbnail_Grid_Form extends Mixin_Display_Type_Form
{
    function get_display_type_name()
	{
		return NGG_PRO_THUMBNAIL_GRID;
	}

    function enqueue_static_resources()
    {
        wp_enqueue_script(
            $this->object->get_display_type_name() . '-js',
            $this->object->get_static_url('photocrati-nextgen_pro_thumbnail_grid#settings.js')
        );
	
        $atp = C_Attach_Controller::get_instance();
        if ($atp != null && $atp->has_method('mark_script'))
            $atp->mark_script($this->object->get_display_type_name() . '-js');
    }

    /**
     * Returns a list of fields to render on the settings page
     */
    function _get_field_names()
    {
        return array(
            'thumbnail_override_settings',
            'nextgen_pro_thumbnail_grid_images_per_page',
            'nextgen_pro_thumbnail_grid_border_size',
            'nextgen_pro_thumbnail_grid_border_color',
			'nextgen_pro_thumbnail_grid_spacing',
			'nextgen_pro_thumbnail_grid_number_of_columns',
        );
    }

    /**
     * Renders the images_per_page settings field
     *
     * @param C_Display_Type $display_type
     * @return string
     */
    function _render_nextgen_pro_thumbnail_grid_images_per_page_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'images_per_page',
            __('Images per page', 'nextgen-gallery-pro'),
            $display_type->settings['images_per_page'],
            __('"0" will display all images at once', 'nextgen-gallery-pro'),
            FALSE,
            __('# of images', 'nextgen-gallery-pro'),
            0
        );
    }

    function _render_nextgen_pro_thumbnail_grid_border_size_field($display_type)
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

	function _render_nextgen_pro_thumbnail_grid_spacing_field($display_type)
	{
		return $this->_render_number_field(
			$display_type,
			'spacing',
			__('Spacing', 'nextgen-gallery-pro'),
			$display_type->settings['spacing']
		);
	}

	function _render_nextgen_pro_thumbnail_grid_number_of_columns_field($display_type)
	{
		return $this->_render_number_field(
			$display_type,
			'number_of_columns',
			__('Number of columns to display', 'nextgen-gallery-pro'),
			$display_type->settings['number_of_columns'],
            __('An empty or zero in this field will use a responsive layout', 'nextgen-gallery-pro')
		);
	}

    function _render_nextgen_pro_thumbnail_grid_border_color_field($display_type)
    {
        return $this->_render_color_field(
            $display_type,
            'border_color',
            __('Border color', 'nextgen-gallery-pro'),
            $display_type->settings['border_color']
        );
    }
}