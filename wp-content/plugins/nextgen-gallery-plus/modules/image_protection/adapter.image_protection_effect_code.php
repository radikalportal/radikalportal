<?php

class A_Image_Protection_Effect_Code extends Mixin
{
    function get_effect_code($displayed_gallery)
    {
        $retval = $this->call_parent('get_effect_code', $displayed_gallery);

        if (C_NextGen_Settings::get_instance()->protect_images)
            $retval .= ' data-ngg-protect="1"';

        return $retval;
    }
}