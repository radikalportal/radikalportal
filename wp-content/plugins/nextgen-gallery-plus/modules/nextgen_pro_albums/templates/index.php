<?php $this->start_element('nextgen_gallery.gallery_container', 'container', $displayed_gallery); ?>
<div class="ngg-pro-album <?php esc_attr_e($css_class) ?>" id="<?php esc_attr_e($id) ?>">
    <?php $i = 0; foreach ($entities as $entity) { ?>
        <div class='image_container'>
            <a href="<?php echo esc_attr($entity->link)?>"
               title="<?php echo esc_attr($entity->galdesc)?>"
               class="gallery_link"
                <?php if($hovercaptions) { ?>
                    data-ngg-captions-enabled="true"
                    data-ngg-captions-id='<?php echo $displayed_gallery->id(); ?>'
                    data-title="<?php echo esc_attr($entity->title); ?>"
                    data-description="<?php echo esc_attr($entity->galdesc); ?>"
                <?php } ?>
                >
                <?php M_NextGen_PictureFill::render_picture_element($entity->previewpic, $thumbnail_size_name, array('class'=>'gallery_preview'))?>
            </a>
            <a href="<?php echo esc_attr($entity->link)?>"
               title="<?php echo esc_attr($entity->title); ?>"
               class="caption_link" ><?php echo_safe_html($entity->title) ?></a>
            <div class="image_description"><?php echo_safe_html(nl2br($entity->galdesc)); ?></div>
            <br class="clear"/>
        </div>
        <?php $i++; ?>
    <?php } ?>
</div>
<?php $this->end_element(); ?>