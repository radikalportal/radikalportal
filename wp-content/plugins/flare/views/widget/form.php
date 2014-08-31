<?php
/**
 * Flare Follow Widget Form
 * 
 * Form fields for the Flare Follow widget administrative interface.
 * 
 * @uses WP_Widget::get_field_name()
 * @uses _e()
 */
?>
<p>
    <label>
        <?php _e( "Title", $namespace ); ?>:<br />
        <input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" size="30" />
    </label>
</p>
<p>
    <label>
        <?php _e( "Button Type", $namespace ); ?>:<br />
        <select name="<?php echo $this->get_field_name( 'iconstyle' ); ?>">
            <?php foreach( $iconstyles as $value => $label ): ?>
                <option value="<?php echo $value; ?>"<?php if( $instance['iconstyle'] == $value ) echo ' selected="selected"'; ?>><?php _e( $label, $namespace ); ?></option>
            <?php endforeach; ?>
        </select>
    </label>
</p>
<p>
    <label>
        <?php _e( "Icon Size", $namespace ); ?>:
        <select name="<?php echo $this->get_field_name( 'iconsize' ); ?>">
            <?php foreach( $sizes as $size ): ?>
                <option value="<?php echo $size; ?>"<?php if( $size == $instance['iconsize'] ) echo ' selected="selected"'; ?>><?php echo $size; ?>px</option>
            <?php endforeach; ?>
        </select>
    </label>
</p>
<p>
    <label>
        <?php _e( "Icon Spacing", $namespace ); ?>:
        <input type="text" name="<?php echo $this->get_field_name( 'iconspacing'); ?>" value="<?php echo $instance['iconspacing']; ?>" size="2" maxlength="2" />px
    </label>
</p>
<div class="more-information">
    <p><em><?php _e( "If you wish to further customize your Flare Follow Me widget appearance, we recommend styling it via CSS in your theme's style.css file.", $namespace ); ?></em></p>
</div>