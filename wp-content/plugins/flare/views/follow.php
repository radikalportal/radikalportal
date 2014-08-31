<script type="text/javascript">
    var __namespace = '<?php echo $namespace; ?>';
</script>
<div id="<?php echo $namespace; ?>-wrapper" class="wrap">
    
    <h2><?php echo $friendly_name; ?> Options</h2>
    
    <form action="" method="post" id="<?php echo $namespace; ?>-form">
        <?php wp_nonce_field( $namespace . "_follow_options", $namespace . '_update_follow_wpnonce' ); ?>
        
        <?php flare_primary_navigation(); ?>
        
        <?php if( isset( $_GET['message'] ) ): ?>
            <div id="message" class="updated below-h2"><p>Options successfully updated!</p></div>
        <?php endif; ?>
        
        <div class="<?php echo $namespace; ?>-form-inner">
            
            <fieldset id="<?php echo $namespace; ?>-sharing-form-wrapper" class="clearfix">
                <div id="<?php echo $namespace; ?>-button-options" class="clearfix">
                    <h3><?php _e( "Stylize <strong>your follow bar</strong>", $namespace ); ?></h3>
                    
                    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save') ?>" />
                    
                    <div id="<?php echo $namespace; ?>-choose-iconstyle-wrapper" class="button-option">
                        <h4><?php _e( "Button Type", $namespace ); ?></h4>
                        <select name="data[follow_iconstyle]" class="fancy fancy-icons" id="<?php echo $namespace; ?>-choose-iconstyle">
                            <?php foreach( $iconstyles as $value => $label ): ?>
                                <option value="<?php echo $value; ?>"<?php if( $value == $follow_iconstyle ) echo ' selected="selected"'; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div id="<?php echo $namespace; ?>-follow-usage" class="button-option">
                        <?php _e( "To use your follow bar, save your options here and drag the Flare widget into your sidebar to activate!", $namespace ); ?>
                    </div>
                </div>
            </fieldset>
            
            <fieldset id="<?php echo $namespace; ?>-button-list-wrapper" class="clearfix">
                <h4><?php _e( "Button Options", $namespace ); ?></h4>
                
                <ul id="<?php echo $namespace; ?>-button-list">
                    
                    <?php
                        foreach( $buttons as $button_type => $button_params ) {
                            $button_model = $available_buttons[$button_type];
                            
                            include( FLARE_DIRNAME . '/views/admin/_follow_button.php' );
                        }
                    ?>
                    
                </ul>
                
                <div id="<?php echo $namespace; ?>-add-button">
                    <h5><?php _e( "Add a new follow button", $namespace ); ?></h5>
                    
                    <a href="#" id="<?php echo $namespace; ?>-add-button-link"><?php _e( "Add button", $namespace ); ?></a>
                    
                    <ul>
                        <?php foreach( $available_buttons as $button ): ?>
                            <li id="<?php echo $namespace; ?>-available-button-<?php echo $button['type']; ?>"<?php if( in_array( $button['type'], array_keys( $buttons ) ) ) echo ' style="display:none;"'; ?>>
                                <a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php' ), "{$namespace}-add-follow-button" ); ?>&action=<?php echo $namespace; ?>_add_follow_button&type=<?php echo $button['type']; ?>" class="<?php echo $namespace; ?>-button button-type-<?php echo $button['type']; ?> <?php echo $namespace; ?>-iconstyle-round" style="background-color:<?php echo $button['color']; ?>">
                                    <span class="<?php echo $namespace; ?>-button-wrap">
                                        <span class="<?php echo $namespace; ?>-button-icon"><?php echo $available_buttons[$button['type']]['label']; ?></span>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save') ?>" />
                </p>
            </fieldset>
            
        </div>
    </form>
    
    <?php include( FLARE_DIRNAME . '/views/elements/_footer.php' ); ?>
    
    <script type="text/javascript">
        var FlareOptionsModel = <?php echo json_encode( $available_buttons ); ?>;
    </script>
</div>