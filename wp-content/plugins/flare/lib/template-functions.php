<?php
/**
 * Format a number to a shorter syntax
 * 
 * Calculates the size of a number and returns a shorthand version of it as
 * a string for display. For example:
 * 
 * 1000
 * 
 * @param integer $number The number to format
 * 
 * @return string
 */
function flare_formatted_count( $number ) {
    if($number === false){
        $number = "--";
    }
    if( $number > 1000 && $number < 1000000 ) {
        $number = round( $number / 1000, 1 ) . "K";
    } else if( $number >= 1000000 ) {
        $number = round( $number / 1000000, 1 ) . "M";
    }
    
    return $number;
}

 
/**
 * Create input fields with labels based off of model data
 * 
 * Creates an input or select element with the specified properties. Returns a string of the
 * HTML markup for the field and its label
 * 
 * @param string $name The name attribute of the field to create
 * @param string $value The value of the field
 * @param array $params Array of parameters describing the field
 *              @param string $type The type of field to create (text|email|checkbox|select)
 *              @param string $label The label for the field
 *              @param array $attr Additional attributes to apply to the field HTML element
 *              @param array $values Available values to choose from (only used by select elements and non-boolean checkboxes)
 *              @param string $description Used as the tooltip if present
 *              @param string $suffix Optional suffix to appear after element
 *              @param array $thumbnail Optional thumbnail for the field, has multiple keyed options:
 *                           @param string $src The SRC attribute for the image tag, thumbnail will not be rendered without this
 *                           @param integer $width Width of the thumbnail
 *                           @param integer $height Height of the thumbnail
 *                           @param string $alt ALT attribute of the thumbnail
 * @param boolean $echo Echo out the resulting HTML? (default is boolean(true), echo response)
 * 
 * @return string
 */
function flare_html_input( $name, $value, $params, $echo = true ) {
    // The HTML return string built by this function
    $html = "";
    
    $field_model = array(
        'type' => "text",
        'label' => "",
        'attr' => array(
            'class' => ""
        ),
        'values' => array(),
        'description' => "",
        'thumbnail' => array(),
        'suffix' => "",
        'interface' => array(),
        'required' => false
    );
    $merged_params = array();
    foreach( $field_model as $key => $val ) {
        if( is_array( $val ) ) {
            if( isset( $params[$key] ) ) {
                $merged_params[$key] = $params[$key];
            } else {
                $merged_params[$key] = $val;
            }
        } else {
            $merged_params[$key] = isset( $params[$key] ) ? $params[$key] : $val;
        }
    }
    extract( $merged_params );
    
    // Alias the $description value as the tooltip
    if( !isset( $tooltip ) )
        $tooltip = &$description;
    
    // Build an ID from the name
    $id = trim( str_replace( array( "[", "]", " " ), array( "-", "", "_" ), trim( $name ) ) );
    // Override ID if it was passed in as an attribute
    if( array_key_exists( 'id',  $attr ) )
        $id = $attr['id'];
    
    // Build the Tooltip HTML string
    $tooltip_str = "";
    if( !empty( $tooltip ) )
        $tooltip_str = '<span class="tooltip" title="' . __( $tooltip, 'ssb' ) . '"></span>';
    
    // Build the Thumbnail HTML string
    $thumbnail_str = "";
    if( array_key_exists( 'src', $thumbnail ) ) {
        $thumbnail_params = array(
            'src' => "",
            'alt' => "",
            'width' => "",
            'height' => ""
        );
        $thumbnail = array_merge( $thumbnail_params, $thumbnail );
        
        $thumbnail_str .= '<img src="' . $thumbnail['src'] . '" alt="' . $thumbnail['alt'] . '"';
        if( !empty( $thumbnail['width'] ) ) $thumbnail_str .= ' width="' . $thumbnail['width'] . '"';
        if( !empty( $thumbnail['height'] ) ) $thumbnail_str .= ' height="' . $thumbnail['height'] . '"';
        $thumbnail_str .= ' />';
    }
    
    $required_str = "";
    if( $required == true ) {
        $required_str = '<span class="required" title="' . __( "Required", 'ssb' ) . '">*</span>';
    }

    $is_fancy = ( strpos( $attr['class'], "fancy" ) !== false );
    
    switch( $type ) {
        case "hidden":
            $html .= '<input type="hidden" name="' . $name . '" value="' . $value . '" id="' . $id . '" />';
        break;
        
        case "checkbox":
            $html .= '<input type="checkbox" name="' . $name . '" value="1" id="' . $id . '"';
            
            // Check the checkbox if the value is true
            if( $value == true )
                $html .= ' checked="checked"';
            
            foreach( $attr as $key => $val )
                if( !in_array( $key, array( 'type', 'name', 'value', 'id', 'checked' ) ) ) 
                    $html .= ' ' . $key . '="' . trim( $val ) . '"';
            
            $html .= ' />';

            if( !empty( $label ) ) {
                $html .= ' <span class="label' . ( $is_fancy ? ' fancy-label' : '' ) . '">' . $required_str . __( $label, 'ssb' );
                
                $html .= $tooltip_str;
                $html .= $thumbnail_str;
                
                $html .= '</span> ';
            }
        break;
        
        case "email":
        case "text":
        case "password":
            if( !empty( $label ) ) {
                $html .= '<label for="' . $id . '" class="label' . ( $is_fancy ? ' fancy-label' : '' ) . '">' . $required_str . __( $label, 'ssb' );
                
                $html .= $tooltip_str;
                $html .= $thumbnail_str;
                
                $html .= '</label> ';
            }
            
            $html .= '<input type="' . $type . '" name="' . $name . '" value="' . $value . '" id="' . $id . '"';
            
            foreach( $attr as $key => $val )
                if( !in_array( $key, array( 'type', 'name', 'value', 'id' ) ) ) 
                    $html .= ' ' . $key . '="' . trim( $val ) . '"';
            
            $html .= ' />';
        break;
        
        case "textarea":
            if( !empty( $label ) ) {
                $html .= '<label for="' . $id . '" class="label' . ( $is_fancy ? ' fancy-label' : '' ) . '">' . $required_str . __( $label, 'ssb' );
                
                $html .= $tooltip_str;
                $html .= $thumbnail_str;
                
                $html .= '</label> ';
            }
            
            $html .= '<textarea type="' . $type . '" name="' . $name . '" id="' . $id . '"';
            
            foreach( $attr as $key => $val )
                if( !in_array( $key, array( 'type', 'name', 'id' ) ) ) 
                    $html .= ' ' . $key . '="' . trim( $val ) . '"';
            
            $html .= '>'; // Close
            
            $html .= esc_textarea( $value );
            $html .= '</textarea>';
        break;
        
        case "select":
            if( !empty( $label ) ) {
                $html .= '<label for="' . $id . '" class="label' . ( $is_fancy ? ' fancy-label' : '' ) . '">' . $required_str . __( $label, 'ssb' );
                
                $html .= $tooltip_str;
                $html .= $thumbnail_str;
                
                $html .= '</label> ';
            }
            
            $html .= '<select name="' . $name . '" id="' . $id . '"';
            
            foreach( $attr as $key => $val )
                if( !in_array( $key, array( 'name', 'id' ) ) ) 
                    $html .= ' ' . $key . '="' . trim( $val ) . '"';
            
            $html .= '>';
            
            foreach( $values as $option_value => $option_text )
                $html .= '<option value="' . $option_value . '"' . ( $option_value == $value ? ' selected="selected"' : '' ) . '>' . $option_text . '</option>';
            
            $html.= '</select>';
        break;
        
        case "radio":
            if( !empty( $label ) ) {
                $html .= '<span class="label' . ( $is_fancy ? ' fancy-label' : '' ) . '">' . $required_str . __( $label, 'ssb' );
                
                $html .= $tooltip_str;
                $html .= $thumbnail_str;
                
                $html .= '</span> ';
            }
            
            $is_radio_boolean = false;
            
            if( empty( $values ) ) {
                $is_radio_boolean = true;    
                $values = array(
                    '1' => 'On',
                    '' => 'Off'
                );                    
            }
            
            foreach( $values as $radio_value => $radio_text ){
                
                $id_suffix = $radio_value;
                
                if( $is_radio_boolean ){
                    switch( $radio_value ){
                        case '1':
                            $id_suffix = 'on';
                        break;
                        default:
                            $id_suffix = 'off';
                        break;    
                    }
                }
                
                $html .= '<label for="' . $id . '-' . $id_suffix . '" class="label">' . $required_str . __( $radio_text, 'ssb' );
                $html .= $thumbnail_str;
                $html .= '<input id="' . $id . '-' . $id_suffix . '" type="radio" name="' . $name . '" value="' . $radio_value . '"' . ( $radio_value == $value ? ' checked="checked"' : '' );
                
                foreach( $attr as $key => $val )
                    if( !in_array( $key, array( 'type', 'name', 'id' ) ) ) 
                        $html .= ' ' . $key . '="' . trim( $val ) . '"';
                
                $html .= ' />';
                
                $html .= '</label> ';
            }
        break;
    }
    
    if( !empty( $suffix ) && $type != "hidden" )
        $html.= '<span class="suffix">' . __( $suffix, 'ssb' ) . '</span>';
    
    if( !empty( $interface ) ) {
        $html .= '<script type="text/javascript">FlareInterfaces["' . $id . '"] = ' . json_encode( $interface ) . ';</script>';
    }
    
    $html = apply_filters( "flare_html_input", $html, $type, $name, $value, $label, $attr, $values );
    
    if( $echo == true )
        echo $html;
    
    return $html;
}

/**
 * Output the code snippet for a button's direction
 * 
 * @param string $button Button type slug
 * @param string $direction (vertical|horizontal) Optional direction to retrieve (default is horizontal)
 * @param boolean $echo Optionally echo or return the code snippet (default is to echo)
 * 
 * @global $Flare
 * 
 * @uses Flare::get_code_snippet()
 * 
 * @return string
 */
function flare_code_snippet( $button, $direction = 'horizontal', $echo = true ) {
    global $Flare;
    
    $html = $Flare->Button->code_snippet( $button, $direction );
    
    if( $echo === true ) {
        echo $html;
    }
    
    return $html;
}

/**
 * Output the primary navigation for the admin page
 * 
 * Builds and outputs HTML navigation for the admin section navigation
 * 
 * @param boolean $echo Optionally echo or return the navigation HTML code (default is to echo)
 * 
 * @global $Flare
 * 
 * @uses Flare::primary_navigation()
 * 
 * @return string
 */
function flare_primary_navigation( $echo = true ) {
    global $Flare;
    
    $html = $Flare->primary_navigation();
    
    if( $echo === true ) {
        echo $html;
    }
    
    return $html;
}
