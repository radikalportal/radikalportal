<?php $this->start_element('nextgen_gallery.gallery_container', 'container', $displayed_gallery); ?>
<div class="nextgen_pro_blog_gallery" id="<?php esc_attr_e($id) ?>">
    <?php
    $this->start_element('nextgen_gallery.image_list_container', 'container', $images);
    $image_display_size = $image_display_size - ($border_size * 2);
    $i = 0;
    foreach ($images as $image) {
        $image_size = $storage->get_image_dimensions($image, $image_size_name);

        // We scale each image in such that it's longest side equals the gallery's "Image Display Size" setting/property
        $aspect_ratio = $image_size['width'] / $image_size['height'];
        $image_size['width']  = ($image_size['width'] < $image_display_size ? $image_size['width'] : $image_display_size);
        $image_size['height'] = $image_size['width'] / $aspect_ratio;

        $ds = $displayed_gallery->display_settings;
        if (isset($ds['image_max_height'])
        &&  $ds['image_max_height'] > 0
        &&  $image_size['height'] > $ds['image_max_height'])
        {
            $image_size['height'] = $ds['image_max_height'];
            $image_size['width'] = $image_size['height'] * $aspect_ratio;
        }

        $style = 'width: ' . $image_display_size . 'px';

        $this->start_element('nextgen_gallery.image_panel', 'item', $image);
        ?>
        <div id="<?php esc_attr_e('ngg-image-' . $id . '-' . $i) ?>" class="image-wrapper" style="<?php esc_attr_e($style); ?>">
            <?php if (!empty($displayed_gallery->display_settings['display_captions']) && $displayed_gallery->display_settings['caption_location'] == 'above') { ?>
                <p class="ngg_pro_blog_gallery_caption_above"><?php echo $image->description; ?></p>
            <?php } ?>
            <?php $this->start_element('nextgen_gallery.image', 'item', $image); ?>
            <a href="<?php echo esc_attr($storage->get_image_url($image))?>"
               title="<?php echo esc_attr($image->description)?>"
               data-src="<?php echo esc_attr($storage->get_image_url($image)); ?>"
               data-thumbnail="<?php echo esc_attr($storage->get_image_url($image, 'thumb')); ?>"
               data-image-id="<?php echo esc_attr($image->{$image->id_field}); ?>"
               data-title="<?php echo esc_attr($image->alttext); ?>"
               data-description="<?php echo esc_attr(stripslashes($image->description)); ?>"
               <?php echo $effect_code ?>>
                <?php // NOTE: we don't specify height as the "width" property might actually not reflect the final image width, because images are responsive and adapt to container size when needed
                M_NextGen_PictureFill::render_picture_element($image, $image_size_name, array('width' => $image_size['width'], 'height' => NULL))
                ?>
            </a>
            <?php $this->end_element(); ?>
            <?php if (!empty($displayed_gallery->display_settings['display_captions']) && $displayed_gallery->display_settings['caption_location'] == 'below') { ?>
                <p class="ngg_pro_blog_gallery_caption_below"><?php echo $image->description; ?></p>
            <?php } ?>
        </div>
        <div class="ngg-clear"></div>
        <?php
        $this->end_element();
        $i++;
    }
    $this->end_element(); ?>
</div>
<?php $this->end_element(); ?>
