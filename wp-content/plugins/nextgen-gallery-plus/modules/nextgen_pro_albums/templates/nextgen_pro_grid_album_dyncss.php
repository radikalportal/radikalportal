/* do not remove or the next id is printed raw <?php echo $id; ?> */

#<?php echo $id ?> .image_container {
    margin-left: <?php echo $spacing ?>px;
    margin-bottom: <?php echo $spacing ?>px;
    <?php if (floatval($border_size)>0): ?>
    border: solid <?php echo $border_size ?>px <?php echo $border_color ?>;
    background-color: <?php echo $background_color ?>;
    <?php endif ?>
}

#<?php echo $id ?> .caption_link,
#<?php echo $id ?> .caption_link:visited,
#<?php echo $id ?> .caption_link:hover {
    color: <?php echo $caption_color ?>;
    font-size: <?php echo $caption_size ?>px;
    margin-top: <?php echo intval(round($padding / 2)); ?>px;
}
