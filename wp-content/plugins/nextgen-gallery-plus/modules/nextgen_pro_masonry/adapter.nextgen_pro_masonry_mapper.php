<?php

class A_NextGen_Pro_Masonry_Mapper extends Mixin
{
    function set_defaults($entity)
    {
        $this->call_parent('set_defaults', $entity);

        if ($entity->name == NGG_PRO_MASONRY)
        {
            $this->object->_set_default_value($entity, 'settings', 'size', 180);
            $this->object->_set_default_value($entity, 'settings', 'padding', 10);
	        $this->object->_set_default_value($entity, 'settings', 'ngg_triggers_display', 'always');
        }
    }
}
