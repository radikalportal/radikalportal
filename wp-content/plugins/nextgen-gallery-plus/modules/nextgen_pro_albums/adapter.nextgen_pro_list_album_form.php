<?php

class A_NextGen_Pro_List_Album_Form extends A_NextGen_Pro_Album_Form
{
	function get_display_type_name()
	{
		return NGG_PRO_LIST_ALBUM;
	}

    /**
     * Adds pro-list-album specific fields to the defaults provided in A_NextGen_Pro_ALbums_Form
     */
    function _get_field_names()
    {
        $fields = parent::_get_field_names();
        $fields[] = 'nextgen_pro_list_album_description_color';
        $fields[] = 'nextgen_pro_list_album_description_size';
        return $fields;
    }

    function _render_nextgen_pro_list_album_description_color_field($display_type)
    {
        return $this->_render_color_field(
            $display_type,
            'description_color',
            'Description color',
            $display_type->settings['description_color']
        );
    }

    function _render_nextgen_pro_list_album_description_size_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'description_size',
            'Description size',
            $display_type->settings['description_size'],
            '',
            FALSE,
            '',
            0
        );
    }
}