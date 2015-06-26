<?php

class C_NextGen_Plus_Installer
{
    function install_display_types()
    {
        foreach (P_Photocrati_NextGen_Plus::$modules as $module_name) {
            if (($handler = C_Photocrati_Installer::get_handler_instance($module_name))) {
                if (method_exists($handler, 'install_display_types')) $handler->install_display_types();
            }
        }
    }

	function uninstall($hard=FALSE)
	{
		foreach (P_Photocrati_NextGen_Plus::$modules as $module_name) {
			if (($handler = C_Photocrati_Installer::get_handler_instance($module_name))) {
				if (method_exists($handler, 'uninstall')) $handler->uninstall($hard);
			}
		}
    }
}
