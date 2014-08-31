<div class="<?php echo implode( " ", $classes ); ?>" data-humbleflarecount="<?php echo $humbleflarecount; ?>">
    <span class="loading"><span></span></span>
    <span class="<?php echo "{$namespace}-total"; ?> first"><strong><?php echo flare_formatted_count( $total_count ); ?></strong> Flares</span>
    
    <?php $counter = 0; foreach( $buttons as $button ): ?>
        <?php
            $button_classes = array(
                "{$namespace}-button",
                "button-type-{$button['type']}",
                "{$namespace}-iconstyle-{$iconstyle}"
            );
            if( $counter == 0 ) $button_classes[] = "first";
            if( !$filamenticon ) {
                if( $button == end( $buttons ) ) $button_classes[] = "last";
            }
        ?>
        <span data-type="<?php echo $button['type']; ?>" class="<?php echo implode( " ", $button_classes ); ?>" style="background-color:<?php echo $button['color']; ?>;z-index:<?php echo count( $buttons ) - $counter; ?>">
            <span class="<?php echo $namespace; ?>-button-wrap">
                <span class="<?php echo $namespace; ?>-button-icon"><?php echo $available_buttons[$button['type']]['label']; ?></span>
            </span>
        </span>
        <span class="<?php echo $namespace; ?>-button-count"><?php echo isset( $counts[$button['type']] ) ? flare_formatted_count( $counts[$button['type']] ) : 0; ?></span>
            
        <span class="<?php echo $namespace; ?>-flyout <?php echo $namespace; ?>-flyout-<?php echo $button['type']; ?><?php if( $counter == 0 ) echo ' first'; $counter++; ?>">
            <span class="<?php echo $namespace; ?>-flyout-inner">
                <span class="<?php echo $namespace; ?>-arrow"></span>
            </span>
            <span class="<?php echo $namespace; ?>-iframe-wrapper" data-code-snippet="<?php echo esc_attr( flare_code_snippet( $button['type'], $direction, false ) ); ?>"></span>
        </span>
        
    <?php endforeach; ?>
    
    <?php
     /**
      * Powered By Filament Icon
      */
    ?>
    <?php if( $filamenticon ): ?>
    <span data-type="filament" class="<?php echo $namespace; ?>-button button-type-filament <?php echo $namespace; ?>-iconstyle-<?php echo $iconstyle; ?> last" style="background-color:#9848b5;z-index:<?php echo count( $buttons ) - $counter; ?>">
        <span class="<?php echo $namespace; ?>-button-wrap">
            <span class="<?php echo $namespace; ?>-button-icon">Filament.io</span>
        </span>
    </span>
        
    <span class="<?php echo $namespace; ?>-flyout <?php echo $namespace; ?>-flyout-filament">
        <span class="<?php echo $namespace; ?>-flyout-inner">
            <span class="<?php echo $namespace; ?>-arrow"></span>
        </span>
        <span class="<?php echo $namespace; ?>-iframe-wrapper" data-code-snippet='<a href="https://filament.io/applications/flare?utm_source=flare_wp&utm_medium=deployment&utm_content=flarebar&utm_campaign=filament" target="_blank">Made with <strong>Flare</strong> <span>More Info</span></a>'></span>
    </span>
    <?php endif; ?>

    <span class="<?php echo "{$namespace}-total"; ?> last"><strong><?php echo flare_formatted_count( $total_count ); ?></strong> Flares</span>

    <span class="close">
        <a href="#close">&#215;</a>
    </span>
</div>