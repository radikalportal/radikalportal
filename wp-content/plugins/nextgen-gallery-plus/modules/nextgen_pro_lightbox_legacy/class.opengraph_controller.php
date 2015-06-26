<?php

class C_OpenGraph_Controller extends C_MVC_Controller
{
    static $_instances = array();

    /**
     * Returns an instance of the controller in a particular context
     * @param bool $context
     * @return mixed
     */
    static function get_instance($context=FALSE)
    {
        if (!isset(self::$_instances[$context])) {
            $klass = get_class();
            self::$_instances[$context] = new $klass($context);
        }
        return self::$_instances[$context];
    }

    // /nextgen-share/{url}/{slug}
    function index_action()
    {
        wp_dequeue_script('photocrati_ajax');
        wp_dequeue_script('frame_event_publisher');
        wp_dequeue_script('jquery');
        wp_dequeue_style('nextgen_gallery_related_images');

        $img_mapper     = C_Image_Mapper::get_instance();
        $image_id       = $this->param('image_id');
        if (($image     = $img_mapper->find($image_id))) {

            $displayed_gallery_id = $this->param('displayed_gallery_id');

            // Template parameters
            $params = array('img' => $image);

            // Get the url & dimensions
			$named_size = $this->param('named_size');
            $storage    = C_Gallery_Storage::get_instance();
            $dimensions = $storage->get_image_dimensions($image, $named_size);
            $image->url = $storage->get_image_url($image, $named_size, TRUE);

            $image->width   = $dimensions['width'];
            $image->height  = $dimensions['height'];

            // Generate the lightbox url
            $router         = $this->get_router();
            $mapper         = $this->get_registry()->get_utility('I_Lightbox_Library_Mapper');
            $lightbox       = $mapper->find_by_name(NGG_PRO_LIGHTBOX);
            $uri            = urldecode($this->param('uri'));
            $lightbox_slug  = $lightbox->display_settings['router_slug'];

            $qs = $this->get_querystring();
            if ($qs)
            {
                $lightbox_url = $router->get_url('/', FALSE, 'root');
                $lightbox_url .= "?" . $qs;
            }
            else {
                $lightbox_url = $router->get_url($uri, FALSE, 'root');
                $lightbox_url .= '/';
            }

            // widget galleries shouldn't have a url specific to one image
            if (FALSE !== strpos($displayed_gallery_id, 'widget-ngg-images-'))
                $image_id = '!';

            $params['lightbox_url'] = "{$lightbox_url}#{$lightbox_slug}/{$displayed_gallery_id}/{$image_id}";

            // Add the blog name
            $params['blog_name'] = get_bloginfo('name');

            // Add routed url
            $protocol = $router->is_https() ? 'https://' : 'http://';
            $params['routed_url'] = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            // Render the opengraph metadata
            $this->expires('+1 day');
            $this->render_view("photocrati-nextgen_pro_lightbox_legacy#opengraph", $params);
        }
        else {
            header(__('Status: 404 Image not found', 'nextgen-gallery-pro'));
            echo __('Image not found', 'nextgen-gallery-pro');
        }
    }

    /**
     * The querystring contains the URI segment to return to, but possibly other querystring data that should be included
     * in the lightbox url. This function returns the querystring without the return data
     */
    function get_querystring()
    {
        return preg_replace("/uri=[^&]+&?/", '', $this->get_router()->get_querystring());
    }
}
