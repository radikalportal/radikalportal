<?php echo $before_widget; ?>

<?php if( !empty( $instance['title'] ) ): ?>
    <?php echo $before_title . $instance['title'] . $after_title; ?>
<?php endif; ?>

<div class="<?php echo "{$namespace}-follow"; ?>">
    
    <?php foreach( $buttons as $button ): ?>
        <?php
            $_button_styles = array(
                'background-color' => $button['color']
            );
            // Button Spacing
            if( $button != reset( $buttons ) ) $_button_styles['margin-left'] = $instance["iconspacing"] . 'px';
            
            $button_styles = "";
            foreach( $_button_styles as $rule => $value ) {
                $button_styles.= "{$rule}:{$value};";
            }
            
            $button_classes = array(
                "{$namespace}-button",
                "button-type-{$button['type']}",
                "{$namespace}-iconstyle-{$instance['iconstyle']}",
                "{$namespace}-iconsize-{$instance['iconsize']}"
            );
        ?>
        
        <a href="<?php echo $button['url']; ?>" class="<?php echo implode( " ", $button_classes ); ?>" style="<?php echo $button_styles; ?>">
            <span class="<?php echo $namespace; ?>-button-wrap">
                <span class="<?php echo $namespace; ?>-button-icon"><?php echo $button['label']; ?></span>
            </span>
        </a>
    
    <?php endforeach; ?>
    
</div>

<?php echo $after_widget; ?>