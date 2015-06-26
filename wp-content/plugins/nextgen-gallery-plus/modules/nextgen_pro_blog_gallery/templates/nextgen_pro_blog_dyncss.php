#<?php echo $id ?> .image-wrapper {
    margin-bottom: <?php echo $spacing ?>px;
}
<?php if ($border_size): ?>
    #<?php echo $id ?> img {
        border: solid <?php echo $border_size ?>px <?php echo $border_color ?>;
    }
<?php endif ?>