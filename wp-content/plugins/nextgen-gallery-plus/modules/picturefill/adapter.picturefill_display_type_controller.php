<?php

class A_PictureFill_Display_Type_Controller extends Mixin
{
	function enqueue_frontend_resources($displayed_gallery)
	{
		wp_enqueue_script('picturefill');
		return $this->call_parent('enqueue_frontend_resources', $displayed_gallery);
	}
}