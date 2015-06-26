<?php

class A_NextGen_Pro_Horizontal_Filmstrip_Mapper extends Mixin
{
	function set_defaults($entity)
	{
        $this->call_parent('set_defaults', $entity);

		if ($entity->name == NGG_PRO_HORIZONTAL_FILMSTRIP) {
			$settings = C_NextGen_Settings::get_instance();

            $this->object->_set_default_value($entity, 'settings', 'override_thumbnail_settings', 1);
            $this->object->_set_default_value($entity, 'settings', 'thumbnail_width', 120);
            $this->object->_set_default_value($entity, 'settings', 'thumbnail_height', 90);
            $this->object->_set_default_value($entity, 'settings', 'thumbnail_quality', $settings->thumbquality);
            $this->object->_set_default_value($entity, 'settings', 'thumbnail_crop', $settings->thumbfix);
            $this->object->_set_default_value($entity, 'settings', 'thumbnail_watermark', 0);

            $this->object->_set_default_value($entity, 'settings', 'override_image_settings', 0);
            $this->object->_set_default_value($entity, 'settings', 'image_quality', '100');
            $this->object->_set_default_value($entity, 'settings', 'image_crop', 0);
            $this->object->_set_default_value($entity, 'settings', 'image_watermark', 0);

            // options inherited from the pro-slideshow module
            $this->object->_set_default_value($entity, 'settings', 'image_pan', 1);
            $this->object->_set_default_value($entity, 'settings', 'show_playback_controls', 1);
            $this->object->_set_default_value($entity, 'settings', 'show_captions', 0);
            $this->object->_set_default_value($entity, 'settings', 'caption_class', 'caption_overlay_bottom');
            $this->object->_set_default_value($entity, 'settings', 'aspect_ratio', '1.5');
            $this->object->_set_default_value($entity, 'settings', 'width', 100);
            $this->object->_set_default_value($entity, 'settings', 'width_unit', '%');
            $this->object->_set_default_value($entity, 'settings', 'transition', 'fade');
            $this->object->_set_default_value($entity, 'settings', 'transition_speed', 1);
            $this->object->_set_default_value($entity, 'settings', 'slideshow_speed', 5);
            $this->object->_set_default_value($entity, 'settings', 'border_size', 0);
            $this->object->_set_default_value($entity, 'settings', 'border_color', '#ffffff');

			$this->object->_set_default_value($entity, 'settings', 'ngg_triggers_display', 'always');
		}
	}
}
