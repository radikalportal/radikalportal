/* <?php echo $id ?> */

<?php
echo '/*';
echo $widest . ' - ' . $longest;
echo '*/';
$width =  $widest;
$width += intval($frame_size)  * 2;
$width += intval($border_size) * 2;

$height =  $longest;
$height += intval($frame_size)  * 2;
$height += intval($border_size) * 2;
?>

#gallery_<?php echo $id ?> .image-wrapper {
    margin-left: <?php echo intval($image_spacing)?>px;
    margin-bottom: <?php echo intval($image_spacing)?>px;
    padding: <?php echo intval($frame_size) ?>px;
    border: solid <?php echo intval($border_size) ?>px <?php echo $border_color ?>;
    background-color: <?php echo $frame_color?>;
}

#gallery_<?php echo $id ?> .image-wrapper a {
    width: <?php echo  $widest ?>px;
    height: <?php echo $longest ?>px;
}
