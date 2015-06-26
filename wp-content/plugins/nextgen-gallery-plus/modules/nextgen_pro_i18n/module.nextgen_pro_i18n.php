<?php
/***
{
Module: photocrati-nextgen_pro_i18n,
Depends: {photocrati-fs}
}
 ***/
class M_NextGen_Pro_I18N extends C_Base_Module
{
    function define()
    {
        parent::define(
            'photocrati-nextgen_pro_i18n',
            'Pro Internationalization',
            "Adds I18N resources and methods",
            '0.1',
            'http://www.nextgen-gallery.com/languages/',
            'Photocrati Media',
            'http://www.photocrati.com'
        );
    }

    function _register_hooks()
    {
        add_action('init', array(&$this, 'register_translation_hooks'), 2);
    }

    function register_translation_hooks()
    {
        $fs = C_Fs::get_instance();
        $dir = str_replace(
            $fs->get_document_root('plugins'),
            '',
            $fs->get_abspath('lang', 'photocrati-nextgen_pro_i18n')
        );

        // Load text domain
        load_plugin_textdomain('nextgen-gallery-pro', false, $dir);
    }
}

new M_NextGen_Pro_I18N();
