<script type="text/javascript">
    var __namespace = '<?php echo $namespace; ?>';
</script>
<div id="<?php echo $namespace; ?>-wrapper" class="wrap">
    
    <h2><?php echo $friendly_name; ?> Options</h2>
    
    <form action="" method="post" id="<?php echo $namespace; ?>-form">
        <?php wp_nonce_field( $namespace . "_share_options", $namespace . '_update_share_wpnonce' ); ?>
        
        <?php flare_primary_navigation(); ?>
        
        <?php if( isset( $_GET['message'] ) ): ?>
            <div id="message" class="updated below-h2"><p>Options successfully updated!</p></div>
        <?php endif; ?>
        
        <div class="<?php echo $namespace; ?>-form-inner">
            
            <fieldset class="<?php echo $namespace; ?>-display" id="<?php echo $namespace; ?>-share-content">
                <h3><?php printf( __( 'Choose %swhere your bar displays%s', $namespace ), '<strong>', '</strong>' ); ?></h3>
                
                <div id="<?php echo $namespace; ?>-position" class="clearfix">
                    <div id="<?php echo $namespace; ?>-position-choices">
                        <?php foreach( $available_positions_grouped as $group => $positions ): ?>
                            
                            <p class="position-<?php echo $group; ?>">
                                
                                <input type="checkbox" id="position-<?php echo $group; ?>-checkbox" autocomplete="off" value="" name="" class="choice fancy"<?php $intersection = array_intersect( array_keys( $positions ), $data['positions'] ); if( !empty( $intersection ) ) echo ' checked="checked"'; ?> />
                                
                                <select name="data[positions][]" class="fancy" autocomplete="off">
                                    <option value="none"></option>
    
                                    <?php foreach( $positions as $position => $label ): ?>
                                        <option value="<?php echo $position; ?>"<?php if( in_array( $position, $data['positions'] ) ) echo ' selected="selected"'; ?>><?php _e( $label, $namespace ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                
                            </p>
                            
                        <?php endforeach; ?>
                    </div>
                    
                    <div id="<?php echo $namespace; ?>-position-display">
                        <?php foreach( array_keys( $available_positions ) as $position ): ?>
                            <span class="<?php echo $position; ?>"<?php if( !in_array( $position, $data['positions'] ) ) echo ' style="display:none;"'; ?>></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </fieldset>
            
            <fieldset id="<?php echo $namespace; ?>-sharing-form-wrapper" class="clearfix">
                <div id="<?php echo $namespace; ?>-button-options" class="clearfix">
                    <h3><?php _e( "Stylize <strong>your Flare bar</strong>", $namespace ); ?></h3>
                    
                    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save') ?>" />
                    
                    <div id="<?php echo $namespace; ?>-choose-iconstyle-wrapper" class="button-option">
                        <h4><?php _e( "Button Type", $namespace ); ?></h4>
                        <?php flare_html_input( 'data[iconstyle]', $data['backgroundcolor'], array( 'type' => 'select', 'values' => $iconstyles, 'attr' => array( 'class' => 'fancy fancy-icons', 'id' => "$namespace-choose-iconstyle" ) ) ); ?>
                    </div>
                    
                    <div id="<?php echo $namespace; ?>-background-color-wrapper" class="button-option">
                        <h4><?php _e( "Background Color", $namespace ); ?></h4>
                        <?php flare_html_input( 'data[backgroundcolor]', $data['backgroundcolor'], array( 'type' => 'select', 'values' => $backgroundcolors, 'attr' => array( 'class' => 'fancy', 'id' => "$namespace-background-color" ) ) ); ?>
                    </div>
                    
                    <div id="<?php echo $namespace; ?>-background-color-disclaimer" class="button-option">
                        <?php _e( "Note: this option only applies to left/right floating bars. Top/bottom bars do not have backgrounds", $namespace ); ?>
                    </div>
                    
                    <div id="<?php echo $namespace; ?>-enablecounters-wrapper" class="button-option">
                        <h4><?php _e( "Options", $namespace ); ?></h4>
                        <p><?php flare_html_input( "data[enablecounters]", $data['enablecounters'], array( 'type' => "checkbox", 'label' => "Show Social Flare Score Counters", 'attr' => array( 'class' => "fancy" ) ) ); ?></p>
                        <p><?php flare_html_input( "data[enabletotal]", $data['enabletotal'], array( 'type' => "checkbox", 'label' => "Show Total Flare Count", 'attr' => array( 'class' => "fancy" ) ) ); ?></p>
                        <p><?php flare_html_input( "data[closablevertical]", $data['closablevertical'], array( 'type' => "checkbox", 'label' => "Allow visitors to hide the vertical sharebar", 'attr' => array( 'class' => "fancy" ) ) ); ?></p>
                        <p><?php flare_html_input( "data[filamenticon]", $data['filamenticon'], array( 'type' => "checkbox", 'label' => "Show love for Flare: Your support helps us build free products!", 'attr' => array( 'class' => "fancy" ) ) ); ?></p>
                        <p class="label">
                            <?php flare_html_input( "data[enablehumbleflare]", $data['enablehumbleflare'], array( 'type' => "checkbox", 'label' => "Hide Flare Count if under", 'attr' => array( 'class' => "fancy" ) ) ); ?>
                            <?php
                                $interface = array(
                                    'type' => 'text',
                                    'data' => "integer",
                                    'attr' => array(
                                        'size' => 3,
                                        'maxlength' => 2,
                                        'class' => "fancy"
                                    ),
                                    'interface' => array(
                                        'type' => "slider",
                                        'min' => 2,
                                        'max' => 20,
                                        'update' => array(
                                            'option' => 'start',
                                            'value' => 'max'
                                        )
                                    )
                                );
                                
                                flare_html_input( 'data[humbleflarecount]', $data['humbleflarecount'], $interface );
                            ?>
                            total flares.
                        </p>
                    </div>
                </div>
            </fieldset>
            
            <fieldset id="<?php echo $namespace; ?>-button-list-wrapper" class="clearfix">
                <h4><?php _e( "Button Options", $namespace ); ?></h4>
                
                <ul id="<?php echo $namespace; ?>-button-list">
                    
                    <?php foreach( $buttons as $button ) include( FLARE_DIRNAME . '/views/admin/_button.php' ); ?>
                    
                </ul>
                
                <div id="<?php echo $namespace; ?>-add-button">
                    <h5><?php _e( "Add a new sharing button", $namespace ); ?></h5>
                    
                    <a href="#" id="<?php echo $namespace; ?>-add-button-link"><?php _e( "Add button", $namespace ); ?></a>
                    
                    <ul>
                        <?php foreach( $available_buttons as $button ): ?>
                            <li id="<?php echo $namespace; ?>-available-button-<?php echo $button['type']; ?>"<?php if( in_array( $button['type'], array_keys( $buttons ) ) ) echo ' style="display:none;"'; ?>>
                                <a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php' ), "{$namespace}-add-button" ); ?>&action=<?php echo $namespace; ?>_add_button&type=<?php echo $button['type']; ?>" class="<?php echo $namespace; ?>-button button-type-<?php echo $button['type']; ?> <?php echo $namespace; ?>-iconstyle-round" style="background-color:<?php echo $button['color']; ?>">
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
            
            <fieldset id="<?php echo $namespace; ?>-data-wrapper" class="clearfix">
                <div id="<?php echo $namespace; ?>-data">
                    <h3><?php printf( __( 'Choose %swhat you want your Flare bar to appear on%s', $namespace ), "<strong>", "</strong>" ); ?></h3>
                    
                    <ul class="clearfix">
                        <?php foreach( $post_types as $post_type ): ?>
                            <li><label><input type="checkbox" name="data[post_types][]" class="fancy" value="<?php echo $post_type->name; ?>"<?php if( in_array( $post_type->name, $data['post_types'] ) ) echo ' checked="checked"'; ?> /> <?php echo $post_type->label; ?></label></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </fieldset>
            
        </div>
    </form>
    
    <?php include( FLARE_DIRNAME . '/views/elements/_footer.php' ); ?>
    
</div>