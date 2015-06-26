<?php

class A_NextGen_Pro_Film_Controller extends Mixin
{
    function initialize()
    {
        parent::initialize();
        $this->object->add_mixin('Mixin_NextGen_Basic_Pagination');
    }

    function is_cachable()
    {
        return FALSE;
    }

	function enqueue_frontend_resources($displayed_gallery)
	{
        $this->call_parent('enqueue_frontend_resources', $displayed_gallery);
        wp_enqueue_style('nextgen_pro_film', $this->get_static_url('photocrati-nextgen_pro_film#nextgen_pro_film.css'));
        wp_enqueue_style('nextgen_pagination_style', $this->get_static_url('photocrati-nextgen_pagination#style.css'));
		$this->enqueue_ngg_styles();
	}

	function index_action($displayed_gallery, $return=FALSE)
	{
		$id               = $displayed_gallery->id();
        $display_settings = $displayed_gallery->display_settings;

        $current_page = (int)$this->param('nggpage', $displayed_gallery->id(), 1);
        if (!isset($display_settings['images_per_page']))
            $display_settings['images_per_page'] = C_NextGen_Settings::get_instance()->images_per_page;
        $offset = $display_settings['images_per_page'] * ($current_page - 1);
        $total  = $displayed_gallery->get_entity_count();
        $images = $displayed_gallery->get_included_entities($display_settings['images_per_page'], $offset);
        if (in_array($displayed_gallery->source, array('random', 'recent')))
            $display_settings['disable_pagination'] = TRUE;
        if ($images)
        {
            if ($display_settings['images_per_page'] && !$display_settings['disable_pagination'])
            {
                $pagination_result = $this->object->create_pagination(
                    $current_page, $total, $display_settings['images_per_page']
                );
            }
        }
        $pagination = !empty($pagination_result['output']) ? $pagination_result['output'] : NULL;

		// Get named size of thumbnail images
		$thumbnail_size_name = 'thumbnail';
		if ($display_settings['override_thumbnail_settings'])
        {
			$dynthumbs = C_Dynamic_Thumbnails_Manager::get_instance();
			$dyn_params = array(
				'width' => $display_settings['thumbnail_width'],
				'height' => $display_settings['thumbnail_height'],
			);
			if ($display_settings['thumbnail_quality'])
				$dyn_params['quality'] = $display_settings['thumbnail_quality'];
			if ($display_settings['thumbnail_crop'])
				$dyn_params['crop'] = true;
			if ($display_settings['thumbnail_watermark'])
				$dyn_params['watermark'] = true;
			$thumbnail_size_name = $dynthumbs->get_size_name($dyn_params);
		}

        // Calculate image statistics
        $stats = $this->object->get_entity_statistics($images, $thumbnail_size_name, TRUE);
        $images                      = $stats['entities'];
        $display_settings['longest'] = $stats['longest'];
        $display_settings['widest']  = $stats['widest'];

        // Enqueue dynamic stylesheet
        $dyn_styles = C_Dynamic_Stylesheet_Controller::get_instance('all');
		$dyn_styles->enqueue('nextgen_pro_film', $this->array_merge_assoc(
			$display_settings,
			array('id' => $id)
		));

		$params = array(
			'images'				=>	$images,
			'storage'				=>	C_Gallery_Storage::get_instance(),
			'thumbnail_size_name'	=>	$thumbnail_size_name,
			'effect_code'			=>	$this->object->get_effect_code($displayed_gallery),
			'id'					=>	$id,
            'pagination'            => $pagination
		);
                
        $params = $this->object->prepare_display_parameters($displayed_gallery, $params);

		// Render view/template
        return preg_replace(
            '~>\s*\n\s*<~', '><',
		    $this->render_view('photocrati-nextgen_pro_film#nextgen_pro_film', $params, $return)
        );
	}
}