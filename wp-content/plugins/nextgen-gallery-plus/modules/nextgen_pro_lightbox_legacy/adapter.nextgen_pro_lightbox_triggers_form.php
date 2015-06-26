<?php

class A_NextGen_Pro_Lightbox_Triggers_Form extends Mixin
{
    function _get_field_names()
    {
        $ret = $this->call_parent('_get_field_names');
        $ret[] = 'nextgen_pro_lightbox_triggers_display';
        return $ret;
    }
    
    function _render_nextgen_pro_lightbox_triggers_display_field($display_type)
    {
        return $this->_render_select_field(
            $display_type,
            'ngg_triggers_display',
            __('Display Triggers', 'nextgen-gallery-pro'),
            array(
                'always'         => __('Always', 'nextgen-gallery-pro'),
                'exclude_mobile' => __('Exclude Small Screens', 'nextgen-gallery-pro'),
                'never'          => __('Never', 'nextgen-gallery-pro')
            ),
            isset($display_type->settings['ngg_triggers_display']) ? $display_type->settings['ngg_triggers_display'] : 'always'
        );
    }
    
    function _render_nextgen_pro_lightbox_triggers_style_field($display_type)
    {
        return $this->_render_select_field(
            $display_type,
            'ngg_triggers_style',
            __('Triggers Style', 'nextgen-gallery-pro'),
            array('plain' => __('Plain', 'nextgen-gallery-pro'), 'fancy' => __('Fancy', 'nextgen-gallery-pro')),
            isset($display_type->settings['ngg_triggers_style']) ? $display_type->settings['ngg_triggers_style'] : 'plain'
        );
    }
}
