<?php $this->start_element('nextgen_gallery.gallery_container', 'container', $displayed_gallery); ?>
<style type='text/css'>
    #ngg-gallery-<?php esc_attr_e($displayed_gallery_id); ?> .ngg-pro-masonry-item {
        margin-bottom: <?php print $padding; ?>px;
    }
</style>
<div class="ngg-pro-masonry" id="ngg-gallery-<?php esc_attr_e($displayed_gallery_id); ?>">
    <div class='ngg-pro-masonry-gutter' style='width: <?php print $padding; ?>px'></div>
    <div class='ngg-pro-masonry-sizer' style='width: <?php print $size; ?>px'></div>
    <?php
    $this->start_element('nextgen_gallery.image_list_container', 'container', $images);
    for ($i = 0; $i < count($images); $i++) {
        $image = $images[$i];
        $thumb_size = $storage->get_image_dimensions($image, $thumbnail_size_name);
	    $thumb_url  = $storage->get_image_url($image, M_NextGen_PictureFill::get_retina_named_size($image,$thumbnail_size_name));
        $this->start_element('nextgen_gallery.image_panel', 'item', $image);
            $this->start_element('nextgen_gallery.image', 'item', $image); ?>
            <div class='ngg-pro-masonry-item' style='height: <?php echo $thumb_size['height']; ?>px; max-width: <?php echo $thumb_size['width']; ?>px;'>
                <a href="<?php echo esc_attr($storage->get_image_url($image)); ?>"
                   title="<?php echo esc_attr($image->description); ?>"
                   data-src="<?php echo esc_attr($storage->get_image_url($image)); ?>"
                   data-thumbnail="<?php echo esc_attr($thumb_url) ?>"
                   data-image-id="<?php echo esc_attr($image->{$image->id_field}); ?>"
                   data-title="<?php echo esc_attr($image->alttext); ?>"
                   data-description="<?php echo esc_attr(stripslashes($image->description)); ?>"
                   <?php echo $effect_code ?>>
	                <?php M_NextGen_PictureFill::render_picture_element($image, $thumbnail_size_name)?>
                </a>
            </div>
            <?php $this->end_element(); ?>
        <?php $this->end_element(); ?>
    <?php } ?>
    <?php $this->end_element(); ?>
</div><?php $this->end_element(); ?>