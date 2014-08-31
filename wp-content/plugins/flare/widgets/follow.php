<?php
class FlareFollowWidget extends WP_Widget {
    static $namespace = "flare";
    
    /**
     * Constructor function for Class
     * 
     * @uses WP_Widget::WP_Widget()
     */
    function __construct() {
        $this->namespace = self::$namespace;
        
        $widget_options = array(
            'classname' => "{$this->namespace}_widget",
            'description' => __( "Show your Flare follow icons to your users and grow your social network!", $this->namespace )
        );
        
        $this->WP_Widget( "{$this->namespace}_widget", 'Flare Follow Widget', $widget_options );
    }
    
    /**
     * Initialization function to register Flare widget for use
     * 
     * @uses register_widget()
     */
    static function initialize() {
        register_widget( 'FlareFollowWidget' );
    }
    
    /**
     * Form function for the widget control panel
     * 
     * @param object $instance Option data for this widget instance
     * 
     * @global $Flare
     * 
     * @uses get_option()
     * @uses wp_parse_args()
     */
    function form( $instance ) {
        global $Flare;
        
        $instance = wp_parse_args( $instance, array(
            'title' => "",
            'iconstyle' => get_option( "_{$this->namespace}_follow_iconstyle", 'round-bevel' ),
            'iconsize' => 24,
            'iconspacing' => 2
        ) );
        
        $iconstyles = $Flare->iconstyles;
        $namespace = $this->namespace;
        $sizes = array( 16, 18, 24, 32, 40, 48, 64 );
        
        include( FLARE_DIRNAME . '/views/widget/form.php' );
    }
    
    /**
     * Update processing function for saving widget instance settings
     * 
     * @param object $new_instance Option data submitted for this widget instance
     * @param object $old_instance Existing option data for this widget instance
     * 
     * @return object $instance Updated option data
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['iconstyle'] = strip_tags( $new_instance['iconstyle'] );
        $instance['iconsize'] = intval( $new_instance['iconsize'] );
        $instance['iconspacing'] = intval( $new_instance['iconspacing'] );
        
        return $instance;
    }
    
    /**
     * Widget output function
     * 
     * Loads the Flare widget instance with its instance settings
     * 
     * @param object $args Extra arguments provided for this widget output see documentation at
     *                     http://codex.wordpress.org/Function_Reference/the_widget
     * @param object $instance Option data for this widget instance
     */
    function widget( $args, $instance ) {
        global $Flare;
        
        extract( $args );
        
        $buttons = $Flare->Follow->get();
        $direction = 'horizontal';
        $namespace = $this->namespace;
        $available_buttons = $Flare->Follow->available_buttons;
        
        include( FLARE_DIRNAME . '/views/widget/widget.php' );
    }
}
