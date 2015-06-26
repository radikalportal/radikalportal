<?php $this->start_element('nextgen_gallery.gallery_container', 'container', $displayed_gallery); ?>
<div class="nextgen_pro_film" id="gallery_<?php esc_attr_e($id) ?>">
    <?php
    $this->start_element('nextgen_gallery.image_list_container', 'container', $images);
    $i = 0;
    foreach ($images as $image) {
        $template_params = array(
            'index' => $i,
            'class' => 'image-wrapper',
            'image' => $image,
        );
        $this->start_element('nextgen_gallery.image_panel', 'item', $image);
        ?>
        <div id="<?php esc_attr_e('ngg-image-' . $i) ?>" class="image-wrapper">
            <?php $this->start_element('nextgen_gallery.image', 'item', $image); ?>
            <a href="<?php echo esc_attr($storage->get_image_url($image)); ?>"
               title="<?php echo esc_attr($image->description); ?>"
               data-src="<?php echo esc_attr($storage->get_image_url($image)); ?>"
               data-thumbnail="<?php echo esc_attr($storage->get_image_url($image, 'thumb')); ?>"
               data-image-id="<?php echo esc_attr($image->{$image->id_field}); ?>"
               data-title="<?php echo esc_attr($image->alttext); ?>"
               data-description="<?php echo esc_attr(stripslashes($image->description)); ?>"
               <?php echo $effect_code ?>>
                <?php M_NextGen_PictureFill::render_picture_element($image, $thumbnail_size_name, array('class' => 'nextgen_pro_film_image')) ?>
            </a>
            <?php $this->end_element(); ?>
        </div>
        <?php
        $this->end_element();
        $i++;
    }
    $this->end_element();
    if ($pagination)
        echo $pagination;
    else { ?>
        <div class="ngg-clear"></div>
    <?php } ?>
</div>
<?php
$this->end_element();
