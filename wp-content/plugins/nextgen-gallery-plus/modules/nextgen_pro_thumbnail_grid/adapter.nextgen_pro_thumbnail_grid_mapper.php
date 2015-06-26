<?php

class A_NextGen_Pro_Thumbnail_Grid_Mapper extends Mixin
{
	function set_defaults($entity)
	{
        $this->call_parent('set_defaults', $entity);

		if ($entity->name == NGG_PRO_THUMBNAIL_GRID) {
			$settings = C_NextGen_Settings::get_instance();
			$this->_set_default_value($entity, 'settings', 'override_thumbnail_settings', 0);
			$this->_set_default_value($entity, 'settings', 'thumbnail_width', $settings->thumbwidth);
			$this->_set_default_value($entity, 'settings', 'thumbnail_height', $settings->thumbheight);
			$this->_set_default_value($entity, 'settings', 'thumbnail_quality', $settings->thumbquality);
			$this->_set_default_value($entity, 'settings', 'thumbnail_crop', $settings->thumbfix);
			$this->_set_default_value($entity, 'settings', 'thumbnail_watermark', 0);
            $this->_set_default_value($entity, 'settings', 'images_per_page', $settings->galImages);
            $this->_set_default_value($entity, 'settings', 'disable_pagination', 0);
			$this->_set_default_value($entity, 'settings', 'border_color', '#eeeeee');
			$this->_set_default_value($entity, 'settings', 'border_size', 0);
			$this->_set_default_value($entity, 'settings', 'spacing', 2);
			$this->_set_default_value($entity, 'settings', 'number_of_columns', 0);
			$this->_set_default_value($entity, 'settings', 'ngg_triggers_display', 'always');
		}
	}
}
