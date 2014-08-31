<li id="follow-button-<?php echo $button_type; ?>" class="button-row" data-button-type="<?php echo $button_type; ?>">
    <a href="#delete" class="button-delete"><span>Delete</span></a>
    
    <div class="inner-wrapper">
        
        <div class="button-preview-wrapper">
            <a href="#" class="<?php echo $namespace; ?>-button button-type-<?php echo $button_type; ?>" style="background-color:<?php echo $button_params['color']; ?>">
                <span class="<?php echo $namespace; ?>-button-wrap">
                    <span class="<?php echo $namespace; ?>-button-icon"><?php echo $button_model['label']; ?></span>
                </span>
            </a>
        </div>
        
        <div class="inner">
            <h5><?php echo $button_model['label']; ?></h5>
            
            <div class="button-mode-config button-mode-easy clearfix">
                <?php if( !empty( $button_model['description'] ) ): ?>
                    <p><?php _e( $button_model['description'], $namespace ); ?></p>
                <?php endif; ?>
                
                <?php if( isset( $button_model['options'] ) ): ?>
                    <?php foreach( $button_model['options'] as $name => $props ): ?>
                        
                        <p>
                            <?php flare_html_input( "button[{$button_type}][{$name}]", $button_params[$name], $props ); ?>
                            
                            <?php if( isset( $props['validate-failure'] ) ): ?>
                                <span class="<?php echo $namespace; ?>-option-error" style="display:none;" title="<?php echo $props['validate-failure']; ?>"></span>
                            <?php endif; ?>
                        </p>
                        
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <p><label class="button-color-label"><input type="text" name="button[<?php echo $button_type; ?>][color]" value="<?php echo $button_params['color']; ?>" class="button-color" /></label></p>
        </div>
        
    </div>

    <span class="button-drag-handle">Drag to Reorder</span>
</li>