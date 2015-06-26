.galleria-stage .galleria-image img {
	<?php if ($border_size && !$image_crop): ?>
	border: solid <?php echo intval($border_size)?>px black;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
	<?php endif ?>

	<?php if ($border_color): ?>
	border-color: <?php echo $border_color?>;
	<?php endif ?>
}
.galleria-thumbnails-container {
	height: <?php echo $thumbnail_height ?>px;
}
.galleria-thumbnails .galleria-image {
	width: <?php echo $thumbnail_width ?>px;
	height: <?php echo $thumbnail_height ?>px;
}
.galleria-stage {
	bottom: <?php echo $stage_bottom ?>px;
}