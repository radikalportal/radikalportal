<?php

class A_NextGen_Pro_Album_Mapper extends Mixin
{
	function set_defaults($entity)
	{
		$this->call_parent('set_defaults', $entity);

        if (in_array($entity->name, array(NGG_PRO_LIST_ALBUM, NGG_PRO_GRID_ALBUM))) {
			$settings				= C_NextGen_Settings::get_instance();

            // Galleries within the album will be displayed as NextGEN Pro Thumbnails, or
            // if not available, then NextGEN Basic Thumbnails
			$gallery_display_type	= defined('NGG_PRO_THUMBNAIL_GRID') ?
				NGG_PRO_THUMBNAIL_GRID : NGG_BASIC_THUMBNAILS;
			$this->_set_default_value($entity, 'settings', 'gallery_display_type', $gallery_display_type);

            // Basic style settings
            $this->_set_default_value($entity, 'settings', 'enable_breadcrumbs', 1);
			$this->_set_default_value($entity, 'settings', 'caption_color', '#333333');
			$this->_set_default_value($entity, 'settings', 'border_color', '#CCCCCC');
			$this->_set_default_value($entity, 'settings', 'border_size', 1);
			$this->_set_default_value($entity, 'settings', 'background_color', '#FFFFFF');
            $this->_set_default_value($entity, 'settings', 'padding', 20);
            $this->_set_default_value($entity, 'settings', 'spacing', 10);

            // Thumbnail dimensions
			$this->_set_default_value($entity, 'settings', 'override_thumbnail_settings', 0);
			$this->_set_default_value($entity, 'settings', 'thumbnail_width', $settings->thumbwidth);
			$this->_set_default_value($entity, 'settings', 'thumbnail_height', $settings->thumbheight);
			$this->_set_default_value($entity, 'settings', 'thumbnail_quality', $settings->thumbquality);
			$this->_set_default_value($entity, 'settings', 'thumbnail_crop', $settings->thumbfix);
			$this->_set_default_value($entity, 'settings', 'thumbnail_watermark', 0);
		}

        // Grid albums do not share a caption_size
        if ($entity->name == NGG_PRO_GRID_ALBUM)
        {
            $this->_set_default_value($entity, 'settings', 'caption_size', 13);
        }

        if ($entity->name == NGG_PRO_LIST_ALBUM)
        {
            $this->_set_default_value($entity, 'settings', 'description_color', '#33333');
            $this->_set_default_value($entity, 'settings', 'description_size', 13);
            $this->_set_default_value($entity, 'settings', 'caption_size', 18);
        }
	}
}