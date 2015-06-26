<?php

class A_NextGen_Pro_Blog_Mapper extends Mixin
{
    function set_defaults($entity)
    {
        $this->call_parent('set_defaults', $entity);
        if ($entity->name == NGG_PRO_BLOG_GALLERY)
        {
            $this->object->_set_default_value($entity, 'settings', 'override_image_settings', 0);
            $this->object->_set_default_value($entity, 'settings', 'image_quality', '100');
            $this->object->_set_default_value($entity, 'settings', 'image_crop', 0);
            $this->object->_set_default_value($entity, 'settings', 'image_watermark', 0);
            $this->object->_set_default_value($entity, 'settings', 'image_display_size', 800);
            $this->object->_set_default_value($entity, 'settings', 'image_max_height', 0);
            $this->object->_set_default_value($entity, 'settings', 'spacing', 5);
            $this->object->_set_default_value($entity, 'settings', 'border_size', 0);
            $this->object->_set_default_value($entity, 'settings', 'border_color', '#FFFFFF');
            $this->object->_set_default_value($entity, 'settings', 'display_captions', 0);
            $this->object->_set_default_value($entity, 'settings', 'caption_location', 'below');
            $this->object->_set_default_value($entity, 'settings', 'ngg_triggers_display', 'always');
        }
    }
}
