<?php

class A_PictureFill_Gallery_Storage extends Mixin
{
	function generate_image_size($image, $named_size, $params=NULL, $skip_defaults=FALSE)
	{
		$retval = $this->call_parent('generate_image_size', $image, $named_size, $params, $skip_defaults);

		// TODO: NGG should flush the displayed gallery cache after a user regenerates thumbnails
		// As of 2.0.78.5, it doesn't do this
		if (version_compare(NGG_PLUGIN_VERSION, '2.0.78.5', '<=')) {
			C_Photocrati_Cache::flush('displayed_gallery_rendering');
		}

		// TODO: This will generate a Retina-version of any other image created,
		// some of which don't need retina versions such as the IGW preview image
		// used in a post.
		//
		// We need to modify NGG so that parameters for dynamic images are stored in the database.
		// That way we could do a call like this:

		/*
		if ($retval) {
			$params = $this->object->get_image_size_params($image, $named_size);
			if (!isset($params['no_retina'])) $this->call_parent('generate_image_size', $image, M_NextGen_PictureFill::get_retina_named_size($image, $named_size));
		}
		*/

		if ($retval) {
			$this->call_parent('generate_image_size', $image, M_NextGen_PictureFill::get_retina_named_size($image, $named_size));
		}
		return $retval;
	}
}