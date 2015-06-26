<?php $this->start_element('nextgen_gallery.gallery_container', 'container', $displayed_gallery); ?>
<div class="nextgen_pro_thumbnail_grid" id="<?php esc_attr_e($id) ?>">
    <?php
    $this->start_element('nextgen_gallery.image_list_container', 'container', $images);
    $i = 0;
    foreach ($images as $image):
        $thumb_size = $storage->get_image_dimensions($image, $thumbnail_size_name);
        $template_params = array(
            'index' => $i,
            'class' => 'image-wrapper',
            'image' => $image,
        );
        $this->start_element('nextgen_gallery.image_panel', 'item', $image);
        ?>
        <div id="<?php esc_attr_e('ngg-image-' . $i) ?>" class="image-wrapper">
            <?php $this->start_element('nextgen_gallery.image', 'item', $image); ?>
            <a href="<?php echo esc_attr($storage->get_image_url($image))?>"
               title="<?php echo esc_attr($image->description)?>"
               data-src="<?php echo esc_attr($storage->get_image_url($image)); ?>"
               data-thumbnail="<?php echo esc_attr($storage->get_image_url($image, 'thumb')); ?>"
               data-image-id="<?php echo esc_attr($image->{$image->id_field}); ?>"
               data-title="<?php echo esc_attr($image->alttext); ?>"
               data-description="<?php echo esc_attr(stripslashes($image->description)); ?>"
               <?php echo $effect_code; ?>>
                <?php M_NextGen_PictureFill::render_picture_element($image, $thumbnail_size_name) ?>
                <noscript>
                    <img src="<?php echo esc_attr($storage->get_image_url($image, $thumbnail_size_name, TRUE))?>"
                         title="<?php echo esc_attr($image->alttext)?>"
                         alt="<?php echo esc_attr($image->alttext)?>"
                         width="<?php echo esc_attr($thumb_size['width'])?>"
                         height="<?php echo esc_attr($thumb_size['height'])?>"
                         style="max-width:none"/>
                </noscript>
            </a>
            <?php $this->end_element(); ?>
        </div>
        <?php
        $this->end_element();
        $number_of_columns = $displayed_gallery->display_settings['number_of_columns'];
        if ($number_of_columns > 0) {
            if ((($i + 1) % $number_of_columns) == 0) { ?>
                <br style='clear: both'/>
            <?php }
        }
        $i++;
    endforeach;
    $this->end_element();

    if ($pagination) {
        echo $pagination;
    } else { ?>
        <div class="ngg-clear"></div>
    <?php } ?>
</div>
<?php
$this->end_element();
