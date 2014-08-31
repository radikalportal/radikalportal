<ul class="postList">
    
    <?php foreach( $rss_items as $key => $value ): ?>
        <?php 
            if( preg_match( '/~r\/Slidedeck/', $value->get_permalink() ) ){
                $icon = 'slidedeck-icon';
            }else{
                $icon = 'dtelepathy-icon';
            }
        ?>
        <li>
            <div class="icon <?php echo $icon; ?>"></div>
            <a href="<?php echo $value->get_permalink(); ?>" target="_blank">
                <?php echo $value->get_title(); ?>
            </a>
        </li>
    <?php endforeach; ?>

</ul>