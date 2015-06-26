<?php

class A_NextGen_Pro_Masonry_Forms extends Mixin
{
    function get_forms($type, $instantiate=FALSE)
    {
        $this->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_MASONRY);

        return $this->call_parent('get_forms', $type, $instantiate);
    }
}
