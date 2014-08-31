<li id="button-<?php echo $button['type']; ?>" class="button-row" data-button-type="<?php echo $button['type']; ?>">
    <a href="#delete" class="button-delete"<?php if( !empty( $button['ID'] ) ) echo ' data-button-id="' . $button['ID'] . '"'; ?>><span>Delete</span></a>
    
    <div class="inner-wrapper">
        
        <div class="button-preview-wrapper">
            <a href="#" class="<?php echo $namespace; ?>-button button-type-<?php echo $button['type']; ?>" style="background-color:<?php echo $button['color']; ?>">
                <span class="<?php echo $namespace; ?>-button-wrap">
                    <span class="<?php echo $namespace; ?>-button-icon"><?php echo $available_buttons[$button['type']]['label']; ?></span>
                </span>
            </a>
        </div>
        
        <div class="inner">
            <input type="hidden" name="button[<?php echo $button['type']; ?>][ID]" class="button-id" value="<?php echo $button['ID']; ?>" />
            <input type="hidden" name="button[<?php echo $button['type']; ?>][type]" class="button-type" value="<?php echo $button['type']; ?>" />
            
            <h5><?php echo $available_buttons[$button['type']]['label']; ?></h5>
            
            <div class="button-mode-config button-mode-easy clearfix"<?php if( $button['mode'] != "easy" ) echo ' style="display:none;"'; ?>>
                <?php if( !empty( $available_buttons[$button['type']]['description'] ) ): ?>
                    <p><?php _e( $available_buttons[$button['type']]['description'], $namespace ); ?></p>
                <?php endif; ?>
                
                <?php if( isset( $button['options'] ) ): ?>
                    <?php foreach( $available_buttons[$button['type']]['options'] as $name => $props ): ?>
                        
                        <p><?php flare_html_input( "button[{$button['type']}][options][{$name}]", $button['options'][$name], $props ); ?></p>
                        
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <p><label class="button-color-label"><input type="text" name="button[<?php echo $button['type']; ?>][color]" value="<?php echo $button['color']; ?>" class="button-color" /></label></p>
            
            <div class="button-mode-config button-mode-code clearfix"<?php if( $button['mode'] != "code" ) echo ' style="display:none;"'; ?>>
                <p><label class="button-code-vertical">Vertical Code Snippet: <br />
                    <textarea rows="5" cols="60" name="button[<?php echo $button['type']; ?>][code][vertical]"><?php echo $button['code']['vertical']; ?></textarea>
                </label></p>
                
                <p><label class="button-code-horizontal">Horizontal Code Snippet: <br /> 
                    <textarea rows="5" cols="60" name="button[<?php echo $button['type']; ?>][code][horizontal]"><?php echo $button['code']['horizontal']; ?></textarea>
                </label></p>
                
                <p class="get-code-at"><em>Get the code at: </em><a href="<?php echo $available_buttons[$button['type']]['url']; ?>" target="_blank"><?php echo $available_buttons[$button['type']]['url']; ?></a></p>
            </div>
            
            <div class="button-mode-additional-options">
                <p>
                    <label<?php if( $button['mode'] == "code" ) echo ' style="display:none;"'; ?>>
                        <input type="radio" value="code" class="button-mode-choice" name="button[<?php echo $button['type']; ?>][mode]"<?php if( $button['mode'] == "code" ) echo ' checked="checked"'; ?> /> 
                        <?php _e( "OR Paste in Code Snippet", $namespace ); ?>
                    </label>
                    <label<?php if( $button['mode'] == "easy" ) echo ' style="display:none;"'; ?>>
                        <input type="radio" value="easy" class="button-mode-choice" name="button[<?php echo $button['type']; ?>][mode]"<?php if( $button['mode'] == "easy" ) echo ' checked="checked"'; ?> /> 
                        <?php _e( "Return to Normal View", $namespace ); ?>
                    </label>
                </p>
            </div>
        </div>
        
    </div>

    <span class="button-drag-handle">Drag to Reorder</span>
</li>