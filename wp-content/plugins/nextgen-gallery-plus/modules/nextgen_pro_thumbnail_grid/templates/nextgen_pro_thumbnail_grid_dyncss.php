<?php if ($border_size): ?>
#<?php echo $id ?> img { border: solid <?php echo $border_size ?>px <?php echo $border_color ?>; }
<?php else: ?>
#<?php echo $id ?> img { border: none; }
<?php endif ?>

#<?php echo $id ?> .image-wrapper {
	margin: <?php echo $spacing ?>px;
}