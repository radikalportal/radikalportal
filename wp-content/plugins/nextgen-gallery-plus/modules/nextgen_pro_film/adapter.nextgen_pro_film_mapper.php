<?php

class A_NextGen_Pro_Film_Mapper extends Mixin
{
	function set_defaults($entity)
	{
        $this->call_parent('set_defaults', $entity);

		if ($entity->name == NGG_PRO_FILM) {
			$settings = C_NextGen_Settings::get_instance();
			$this->_set_default_value($entity, 'settings', 'override_thumbnail_settings', 0);
			$this->_set_default_value($entity, 'settings', 'thumbnail_width', $settings->thumbwidth);
			$this->_set_default_value($entity, 'settings', 'thumbnail_height', $settings->thumbheight);
			$this->_set_default_value($entity, 'settings', 'thumbnail_quality', $settings->thumbquality);
			$this->_set_default_value($entity, 'settings', 'thumbnail_crop', 0);
			$this->_set_default_value($entity, 'settings', 'thumbnail_watermark', 0);
            $this->_set_default_value($entity, 'settings', 'images_per_page', $settings->galImages);
            $this->_set_default_value($entity, 'settings', 'disable_pagination', 0);
			$this->_set_default_value($entity, 'settings', 'border_color', '#CCCCCC');
			$this->_set_default_value($entity, 'settings', 'border_size', 1);
			$this->_set_default_value($entity, 'settings', 'frame_color', '#FFFFFF');
			$this->_set_default_value($entity, 'settings', 'frame_size', 20);
			$this->_set_default_value($entity, 'settings', 'image_spacing', 5);
			$this->object->_set_default_value($entity, 'settings', 'ngg_triggers_display', 'always');
		}
	}
}
