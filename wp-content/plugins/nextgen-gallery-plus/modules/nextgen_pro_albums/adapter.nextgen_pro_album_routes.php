<?php

class A_NextGen_Pro_Album_Routes extends Mixin
{
	function render($displayed_gallery, $return=FALSE, $mode=NULL)
	{
        $do_rewrites = FALSE;

        $album_types = array(
			NGG_PRO_ALBUMS,
			NGG_PRO_LIST_ALBUM,
			NGG_PRO_GRID_ALBUM
		);

        // Get the original display type
        $original_display_type = isset($displayed_gallery->display_settings['original_display_type']) ?
            $displayed_gallery->display_settings['original_display_type'] : '';

        if (in_array($displayed_gallery->display_type, $album_types)) {
            $do_rewrites = TRUE;

            $router = C_Router::get_instance();
            $app = $router->get_routed_app();
            $slug = '/'.C_NextGen_Settings::get_instance()->router_param_slug;

            // ensure to pass $stop=TRUE to $app->rewrite() on parameters that may be shared with other display types
            $app->rewrite('{*}'.$slug.'/page/{\d}{*}',      '{1}'.$slug.'/nggpage--{2}{3}', FALSE, TRUE);
            $app->rewrite('{*}'.$slug.'/page--{*}',         '{1}'.$slug.'/nggpage--{2}', FALSE, TRUE);
            $app->rewrite('{*}'.$slug.'/{\w}',              '{1}'.$slug.'/album--{2}');
            $app->rewrite('{*}'.$slug.'/{\w}/{\w}',         '{1}'.$slug.'/album--{2}/gallery--{3}');
            $app->rewrite('{*}'.$slug.'/{\w}/{\w}/{\w}{*}',	'{1}'.$slug.'/album--{2}/gallery--{3}/{4}{5}');
        }
        elseif(in_array($original_display_type, $album_types)) {
            $do_rewrites = TRUE;

            $router = C_Router::get_instance();
            $app = $router->get_routed_app();
            $slug = '/'.C_NextGen_Settings::get_instance()->router_param_slug;

            $app->rewrite("{*}{$slug}/album--{\\w}",                    "{1}{$slug}/{2}");
            $app->rewrite("{*}{$slug}/album--{\\w}/gallery--{\\w}",     "{1}{$slug}/{2}/{3}");
            $app->rewrite("{*}{$slug}/album--{\\w}/gallery--{\\w}/{*}", "{1}{$slug}/{2}/{3}/{4}");
        }

        if ($do_rewrites) $app->do_rewrites();

        // Continue rendering
        return $this->call_parent('render', $displayed_gallery, $return, $mode);
	}
}
