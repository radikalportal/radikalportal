<?php
class Flare_Follow_Model {
    static $namespace = "flare-follow";
    static $label = "Flare Follow";


    // The buttons available for this plugin to create
    static $available_buttons = array(
        'facebook' => array(
            'type' => "facebook",
            'label' => "Facebook",
            'description' => "",
            'color' => "#0b59aa",
            'options' => array(
                'vanityname' => array(
                    'name' => 'vanityname',
                    'data' => "string",
                    'validate' => "/[a-zA-Z0-9\.]{1,255}/",
                    'validate-failure' => "Make sure you enter a valid Facebook profile URL name",
                    'type' => "text",
                    'value' => "",
                    'label' => "Your Facebook Profile URL: <br />http://www.facebook.com/",
                    'prefix' => "http://www.facebook.com/",
                    'attr' => array(
                        'class' => "fancy"
                    )
                )
            ),
            'url_template' => 'http://www.facebook.com/{{vanityname}}'
        ),
        'twitter' => array(
            'type' => "twitter",
            'label' => "Twitter",
            'description' => "",
            'color' => "#00aced",
            'options' => array(
                'username' => array(
                    'name' => "username",
                    'data' => "string",
                    'validate' => "/[a-zA-Z0-9_]{1,15}/",
                    'validate-failure' => "Make sure you enter a valid Twitter username",
                    'label' => "Your Twitter Username: <br />@",
                    'value' => ""
                )
            ),
            'url_template' => 'http://www.twitter.com/{{username}}'
        ),
        'googleplus' => array(
            'type' => "googleplus",
            'label' => "Google+",
            'description' => "",
            'color' => "#d84d2f",
            'options' => array(
                'profileid' => array(
                    'name' => "profileid",
                    'data' => "string",
                    'validate' => "/[0-9]+/",
                    'validate-failure' => "Make sure you enter a valid Google+ profile ID",
                    'label' => "Your Google+ Profile ID: <br />http://plus.google.com/",
                    'value' => ""
                )
            ),
            'url_template' => 'http://plus.google.com/{{profileid}}'
        ),
        'rss' => array(
            'type' => "rss",
            'label' => "RSS",
            'description' => "",
            'color' => "#f89a3b",
            'options' => array(
                'url' => array(
                    'name' => "url",
                    'data' => "string",
                    'label' => "Your RSS Feed URL (will use this blog's RSS feed URL if left empty)<br />",
                    'value' => ""
                )
            ),
            'url_template' => "{{url}}"
        ),
        'pinterest' => array(
            'type' => 'pinterest',
            'label' => "Pin It Share",
            'description' => "",
            'color' => "#ce1c1e",
            'options' => array(
                'username' => array(
                    'name' => "username",
                    'data' => "string",
                    'validate' => "/[a-zA-Z0-9]{3,15}/",
                    'validate-failure' => "Make sure you enter a valid Pinterest username",
                    'label' => "Your Pinterest Username: <br />http://www.pinterest.com/",
                    'value' => ""
                )
            ),
            'url_template' => "http://www.pinterest.com/{{username}}"
        ),
        'soundcloud' => array(
            'type' => "soundcloud",
            'label' => "Soundcloud",
            'description' => "",
            'color' => "#ff6600",
            'options' => array(
                'username' => array(
                    'name' => 'username',
                    'data' => "string",
                    'validate' => "/[a-zA-Z0-9\.]{1,255}/",
                    'validate-failure' => "Make sure you enter a valid Soundcloud profile URL name",
                    'type' => "text",
                    'value' => "",
                    'label' => "Your Soundcloud Profile URL: <br />https://www.soundcloud.com/",
                    'prefix' => "https://www.soundcloud.com/"
                )
            ),
            'url_template' => 'https://www.soundcloud.com/{{username}}'
        ),
        'github' => array(
            'type' => "github",
            'label' => "GitHub",
            'description' => "",
            'color' => "#171516",
            'options' => array(
                'username' => array(
                    'name' => 'username',
                    'data' => "string",
                    'validate' => "/[a-zA-Z0-9\.]{1,39}/",
                    'validate-failure' => "Make sure you enter a valid GitHub profile URL name",
                    'type' => "text",
                    'value' => "",
                    'label' => "Your GitHub Profile URL: <br />https://www.github.com/",
                    'prefix' => "https://www.github.com/"
                )
            ),
            'url_template' => 'https://www.github.com/{{username}}'
        )
    );

    function __construct() {
        // Make namespace available as an instance variable
        $this->namespace = self::$namespace;
        // Make friendly name available as an instance variable
        $this->label = self::$label;
        // Make available buttons available as an instance variable
        $this->available_buttons = self::$available_buttons;
    }

    /**
     * Process a button's URL template
     *
     * Replaces any properties in a button model's URL template with
     * user entered values.
     *
     * @return string
     */
    function _process_url_template( $name, $params ) {
        $url = $this->available_buttons[$name]['url_template'];

        foreach( $params as $name => $val ) {
            $url = str_replace( '{{' . $name . '}}', $val, $url );
        }

        return $url;
    }

    function get() {
        $_buttons = get_option( "{$this->namespace}_buttons", array() );
        $buttons = array();

        foreach( $_buttons as $button_name => $button_params ) {
            $button_params['url'] = $this->_process_url_template( $button_name, $button_params );
            $button_params['type'] = $button_name;
            $button_params['label'] = $this->available_buttons[$button_name]['label'];

            $buttons[$button_name] = $button_params;
        }

        return $buttons;
    }

    /**
     * Save follow options
     *
     * Saves the follow options
     *
     * @param array $data The button data to be saved
     *
     * @uses update_post_meta()
     *
     * @return int
     */
    function save( $data ) {
        $button_data = array();

        foreach( (array) $data as $service => $button_options ) {
            foreach( $this->available_buttons[$service]['options'] as $option_name => $option_params ) {
                // Set a default value
                $button_data[$service][$option_name] = $option_params['value'];

                switch( $option_params['data'] ) {
                    case "integer":
                    case "float":
                    case "number":
                        if( isset( $data[$service][$option_name] ) ) {
                            if( is_numeric( $val ) ) {
                                switch( $type ) {
                                    case "integer":
                                        $button_data[$service][$option_name] = intval( $data[$service][$option_name] );
                                    break;

                                    case "float":
                                    case "number":
                                        $button_data[$service][$option_name] = floatval( $val );
                                    break;
                                }
                            }
                        }
                    break;

                    case "string":
                        if( isset( $data[$service][$option_name] ) ) {
                            if( isset( $option_params['validate'] ) ) {
                                if( preg_match( $option_params['validate'], $data[$service][$option_name] ) ) {
                                    $button_data[$service][$option_name] = (string) $data[$service][$option_name];
                                }
                            } else {
                                $button_data[$service][$option_name] = (string) $data[$service][$option_name];
                            }
                        }
                    break;

                    case "boolean":
                        if( isset( $data[$service][$option_name] ) ) {
                            if( $data[$service][$option_name] == "1" ) {
                                $button_data[$service][$option_name] = true;
                            } elseif( $data[$service][$option_name] == "0" ) {
                                $button_data[$service][$option_name] = false;
                            }
                        } else {
                            $button_data[$service][$option_name] = $option_params['value'];
                        }
                    break;
                }
            }

            if( isset( $data[$service]['color'] ) ) {
                $button_data[$service]['color'] = $data[$service]['color'];
            }
        }

        update_option( "{$this->namespace}_buttons", $button_data );
    }
}
