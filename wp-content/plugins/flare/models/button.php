<?php
class Flare_Model {
    static $namespace = "flare";
    static $label = "Flare";
    
    // The buttons available for this plugin to create
    static $available_buttons = array(
        'facebook' => array(
            'type' => "facebook",
            'label' => "Facebook",
            'description' => "The <em>Like button</em> will automatically share the URL currently being viewed.",
            'url' => 'http://developers.facebook.com/docs/reference/plugins/like/',
            'color' => "#0b59aa",
            'options' => array(
                'verb' => array(
                    'name' => 'verb',
                    'data' => "string",
                    'type' => "select",
                    'value' => "like",
                    'values' => array(
                        'like' => "Like",
                        'recommend' => "Recommend"
                    ),
                    'label' => "Button Verb",
                    'attr' => array(
                        'class' => "fancy"
                    )
                )
            ),
            'template' => array(
                'horizontal' => '<iframe src="//www.facebook.com/plugins/like.php?href={{url}}&amp;send=false&amp;layout=button_count&amp;width=120&amp;show_faces=false&amp;action={{verb}}&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:120px; height:21px;" allowTransparency="true"></iframe>',
                'vertical' => '<iframe src="//www.facebook.com/plugins/like.php?href={{url}}&amp;send=false&amp;layout=box_count&amp;width=50&amp;show_faces=false&amp;action={{verb}}&amp;colorscheme=light&amp;font&amp;height=65" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:65px;" allowTransparency="true"></iframe>'
            )
        ),
        'twitter' => array(
            'type' => "twitter",
            'label' => "Twitter",
            'description' => "",
            'url' => 'http://twitter.com/about/resources/tweetbutton',
            'color' => "#00aced",
            'options' => array(
                'via' => array(
                    'name' => "via",
                    'data' => "string",
                    'label' => "Via @",
                    'value' => ""
                ),
                'tailoring' => array(
                    'name' => 'tailoring',
                    'data' => 'boolean',
                    'type' => "checkbox",
                    'value' => false,
                    'label' => "Opt-out of tailoring Twitter",
                    'attr' => array(
                        'class' => "fancy"
                    )
                )
            ),
            'template' => array(
                'horizontal' => '<a href="https://twitter.com/share" class="twitter-share-button" {{via}} {{tailoring}}>Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>',
                'vertical' => '<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" {{via}} {{tailoring}}>Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>'
            )
        ),
        'googleplus' => array(
            'type' => "googleplus",
            'label' => "Google+",
            'description' => "",
            'url' => 'http://www.google.com/intl/en/webmasters/+1/button/',
            'color' => "#d84d2f",
            'options' => array(),
            'template' => array(
                'horizontal' => '<g:plusone></g:plusone>
<script type="text/javascript">
  (function() {
    var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
    po.src = "https://apis.google.com/js/plusone.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
  })();
</script>',
                'vertical' => '<g:plusone size="tall"></g:plusone>
<script type="text/javascript">
  (function() {
    var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
    po.src = "https://apis.google.com/js/plusone.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
  })();
</script>'
            )
        ),
        'reddit' => array(
            'type' => 'reddit',
            'label' => "Reddit",
            'description' => "",
            'url' => 'http://www.reddit.com/buttons',
            'color' => "#8fc5f2",
            'options' => array(),
            'template' => array(
                'horizontal' => '<script type="text/javascript" src="http://www.reddit.com/static/button/button1.js"></script>',
                'vertical' => '<script type="text/javascript" src="http://www.reddit.com/static/button/button3.js"></script>'
            )
        ),
        'stumbleupon' => array(
            'type' => 'stumbleupon',
            'label' => "StumbleUpon",
            'description' => "",
            'url' => 'http://www.stumbleupon.com/badges/',
            'color' => "#2bb87a",
            'options' => array(),
            'template' => array(
                'horizontal' => '<!-- Place this tag where you want the su badge to render -->
<su:badge layout="1" location="{{rawurl}}"></su:badge>

<!-- Place this snippet wherever appropriate -->
<script type="text/javascript">
  (function() {
    var li = document.createElement("script"); li.type = "text/javascript"; li.async = true;
    li.src = ("https:" == document.location.protocol ? "https:" : "http:") + "//platform.stumbleupon.com/1/widgets.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(li, s);
  })();
</script>',
                'vertical' => '<!-- Place this tag where you want the su badge to render -->
<su:badge layout="5" location="{{rawurl}}"></su:badge>

<!-- Place this snippet wherever appropriate -->
<script type="text/javascript">
  (function() {
    var li = document.createElement("script"); li.type = "text/javascript"; li.async = true;
    li.src = ("https:" == document.location.protocol ? "https:" : "http:") + "//platform.stumbleupon.com/1/widgets.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(li, s);
  })();
</script>'
            )
        ),
        'pinterest' => array(
            'type' => 'pinterest',
            'label' => "Pin It Share",
            'description' => "Pin your posts with Pinterest (only works with posts that have images)",
            'url' => 'http://pinterest.com/about/goodies/#button_for_websites',
            'color' => "#ce1c1e",
            'options' => array(),
            'template' => array(
                'horizontal' => '<a href="http://pinterest.com/pin/create/button/?url={{url}}&media={{media}}" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>',
                'vertical' => '<a href="http://pinterest.com/pin/create/button/?url={{url}}&media={{media}}" class="pin-it-button" count-layout="vertical"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>'
            )
        ),
        'linkedin' => array(
            'type' => 'linkedin',
            'label' => "LinkedIn",
            'description' => "",
            'url' => 'https://developer.linkedin.com/plugins/share-plugin-generator',
            'color' => "#0375b3",
            'options' => array(),
            'template' => array(
                'horizontal' => '<script src="//platform.linkedin.com/in.js" type="text/javascript"></script><script type="IN/Share" data-counter="right"></script>',
                'vertical' => '<script src="//platform.linkedin.com/in.js" type="text/javascript"></script><script type="IN/Share" data-counter="top"></script>'
            )
        ),
        'email' => array(
            'type' => 'email',
            'label' => "Email",
            'description' => "Share your posts by emailing them to your friends. Use <strong>{{blogname}}</strong> to dynamically insert your blog's name or <strong>{{title}}</strong> for the post's title and <strong>{{permalink}}</strong> for the post's permalink in the fields below.<span class='separator'></span>",
            'url' => '',
            'color' => "#f2d446",
            'options' => array(
                'email_subject' => array(
                    'name' => 'email_subject',
                    'data' => "string",
                    'type' => "text",
                    'value' => "Check out this article I found on {{blogname}}",
                    'label' => "Subject prefix: ",
                    'attr' => array(
                        'class' => "fancy",
                        'size' => 40
                    )
                ),
                'email_body' => array(
                    'name' => 'email_body',
                    'data' => "string",
                    'type' => "textarea",
                    'value' => "Check out this article I found on {{blogname}}:\n\n{{title}}\n{{permalink}}",
                    'label' => "Email Body",
                    'attr' => array(
                        'class' => "fancy",
                        'rows' => 5
                    )
                )
            ),
            'template' => array(
                'horizontal' => '<a href="mailto:?body={{email_body}}&subject={{email_subject}}" title="Email to a friend" target="_blank">Email to a friend</a>',
                'vertical' => '<a href="mailto:?body={{email_body}}&subject={{email_subject}}" title="Email to a friend" target="_blank">Email to a friend</a>'
            )
        ),
        'buffer' => array(
            'type' => 'buffer',
            'label' => "Buffer",
            'description' => "",
            'url' => 'http://bufferapp.com/extras/button',
            'color' => "#232323",
            'options' => array(
                'via' => array(
                    'name' => "via",
                    'data' => "string",
                    'label' => "Via @",
                    'value' => ""
                )
            ),
            'template' => array(
                'horizontal' => '<a href="http://bufferapp.com/add" class="buffer-add-button" data-count="horizontal" {{via}}>Buffer</a><script type="text/javascript" src="http://static.bufferapp.com/js/button.js"></script>',
                'vertical' => '<a href="http://bufferapp.com/add" class="buffer-add-button" data-count="vertical" {{via}}>Buffer</a><script type="text/javascript" src="http://static.bufferapp.com/js/button.js"></script>'
            )
        )
    );
    
    // Simple model for new buttons
    static $scaffold = array(
        'ID' => "",
        'enabled' => false,
        'type' => "",
        'mode' => "easy",
        'code' => array(
            'horizontal' => "",
            'vertical' => ""
        )
    );
    
    function __construct() {
        // Make namespace available as an instance variable
        $this->namespace = self::$namespace;
        // Make friendly name available as an instance variable
        $this->label = self::$label;
        // Make available buttons available as an instance variable
        $this->available_buttons = self::$available_buttons;
        // Make button model available as an instance variable
        $this->scaffold = self::$scaffold;
        
        register_post_type( $this->namespace, array(
            'label' => $this->label,
            'public' => false
        ) );
    }
    
    /**
     * Parses raw HTML and returns an array of images
     * 
     * @param string $html_string Raw HTML to be processed
     * 
     * @return array
     */
    private function _parse_html_for_images( $html_string = "" ) {
        $html_string = preg_replace( "/([\n\r]+)/", "", $html_string );
        
        $image_strs = array();
        preg_match_all( '/<img(\s*([a-zA-Z]+)\=[\"|\']([a-zA-Z0-9\/\#\&\=\|\-_\+\%\!\?\:\;\.\(\)\~\s\,]*)[\"|\'])+\s*\/?>/', $html_string, $image_strs );
        
        $images_all = array();
        if( isset( $image_strs[0] ) && !empty( $image_strs[0] ) ) {
            foreach( (array) $image_strs[0] as $image_str ) {
                $image_attr = array();
                preg_match_all( '/([a-zA-Z]+)\=[\"|\']([a-zA-Z0-9\/\#\&\=\|\-_\+\%\!\?\:\;\.\(\)\~\s\,]*)[\"|\']/', $image_str, $image_attr );
                
                if( in_array( 'src', $image_attr[1] ) ) {
                    $images_all[] = array_combine( $image_attr[1], $image_attr[2] );
                }
            }
        }
        
        $images = array();
        if( !empty( $images_all ) ) {
            foreach( $images_all as $image ) {
                // Filter out advertisements and tracking beacons
                if( !preg_match( '/(tweetmeme|stats|share-buttons|advertisement|feedburner|commindo|valueclickmedia|imediaconnection|adify|traffiq|premiumnetwork|advertisingz|gayadnetwork|vantageous|networkadvertising|advertising|digitalpoint|viraladnetwork|decknetwork|burstmedia|doubleclick).|feeds\.[a-zA-Z0-9\-_]+\.com\/~ff|wp\-digg\-this|feeds\.wordpress\.com|\/media\/post_label_source|ads\.pheedo\.com/i', $image['src'] ) ) {
                    $images[] = $image['src'];
                }
            }
        }
        
        return $images;
    }
        
    /**
     * Get the code snippet for a button
     * 
     * @param string $button Button slug
     * @param string $direction (vertical|horizontal) Optional direction to retrieve (default is horizontal)
     * 
     * @return string
     */
    function code_snippet( $button_type, $direction = 'horizontal' ) {
        global $wp_query;
        
        $html = "";
        
        $button = $this->get( $button_type );
        
        if( $button['mode'] == "easy" ) {
            $html = $this->available_buttons[$button_type]['template'][$direction];
            
            $replacements = array(
                "{{url}}" => urlencode( get_permalink( $wp_query->post->ID ) ),
                "{{rawurl}}" => get_permalink( $wp_query->post->ID )
            );
            
            // Handle special cases for different button sources
            switch( $button_type ) {
                
                case "email":
                    $email_subject = $button['options']['email_subject'];
                    $email_body = $button['options']['email_body'];
                    
                    $email_replacements = array(
                        '{{blogname}}' => get_bloginfo( 'blogname' ),
                        '{{title}}' => get_the_title( $wp_query->post->ID ),
                        '{{permalink}}' => get_permalink( $wp_query->post->ID )
                    );
                    
                    $email_subject = str_replace( array_keys( $email_replacements ), array_values( $email_replacements ), $email_subject );
                    $email_body = str_replace( array_keys( $email_replacements ), array_values( $email_replacements ), $email_body );
                    
                    $replacements["{{email_subject}}"] = rawurlencode( $email_subject );
                    $replacements["{{email_body}}"] = rawurlencode( $email_body );
                break;
                
                // Pinterest
                case "pinterest":
                    $media = $this->get_post_image( $wp_query->post );
                    if( $media ) {
                        $replacements["{{media}}"] = $media;
                    }
                    // Pinterest MUST have an image present, do not show the Pinterest button if there is no image
                    else {
                        $html = "";
                    }
                break;
                
                // Facebook
                case "facebook":
                    $replacements["{{verb}}"] = $button['options']['verb'];
                break;
                
                // Twitter
                case "twitter":
                    $replacements["{{via}}"] = !empty( $button['options']['via'] ) ? ' data-via="' . $button['options']['via'] . '"' : "";
                    $replacements["{{tailoring}}"] = $button['options']['tailoring'] ? ' data-dnt="true"' : "";
                break;
                
                // Buffer
                case "buffer":
                    $replacements["{{via}}"] = !empty( $button['options']['via'] ) ? ' data-via="' . $button['options']['via'] . '"' : "";
                break;
            }
            
            $html = str_replace( array_keys( $replacements ), array_values( $replacements ), $html );
        } else {
            $html = $button['code'][$direction];
        }
        
        return $html;
    }
    
    /**
     * Get the buttons the user has created
     * 
     * Retrieves the buttons from the database stored as the custom post type and
     * returns them in a post-processed associative array format for use in this
     * plugin.
     * 
     * If a $button_type is specified a single button array will be returned instead of
     * an array of buttons, so please make sure to accommodate for this when processing
     * data returned by this function.
     * 
     * @param string $button_type Slug for the button type to retrieve a single button's data
     * 
     * @uses WP_Query
     * @uses wp_cache_get()
     * @uses wp_cache_set()
     * @uses get_post_meta()
     * 
     * @return mixed
     */
    function get( $button_type = "" ) {
        $buttons = wp_cache_get( "_get-" . $button_type, $this->namespace );
        
        if( !$buttons ) {
            $args = array(
                'post_type' => $this->namespace,
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'nopaging' => true,
                'orderby' => 'menu_order',
                'order' => 'ASC'
            );
            
            if( isset( $button_type ) ) {
                $args['post_title'] = $button_type;
            }
            
            $posts = new WP_Query( $args );
            
            $buttons = array();
            foreach( (array) $posts->posts as $post ) {
                $_button_type = $post->post_title;
                $_button_color = get_post_meta( $post->ID, "_{$this->namespace}_color", true );
                $_button_mode = get_post_meta( $post->ID, "_{$this->namespace}_mode", true );
                
                if( empty( $_button_color ) )
                    $_button_color = $this->available_buttons[$_button_type]['color'];
                
                if( empty( $_button_mode ) )
                    $_button_mode = $this->available_buttons[$_button_type]['mode'];
                
                $button = array(
                    'ID' => $post->ID,
                    'order' => $post->menu_order,
                    'enabled' => (boolean) ( $post->post_status == 'publish' ),
                    'type' => $_button_type,
                    'mode' => $_button_mode,
                    'color' => $_button_color,
                    'code' => array(
                        'horizontal' => $post->post_content,
                        'vertical' => $post->post_excerpt,
                    ),
                    'options' => array()
                );
                
                // Check for additional button type options
                if( isset( $this->available_buttons[$_button_type]['options'] ) ) {
                    // Loop through button type options if there are any and get stored values or set defaults
                    foreach( $this->available_buttons[$_button_type]['options'] as $name => $props ) {
                        $button['options'][$name] = get_post_meta( $button['ID'], "_{$this->namespace}_{$button['type']}_{$name}", true );
                        if( empty( $button['options'][$name] ) ) {
                            $button['options'][$name] = $props['value'];
                        }
                    }
                }
                
                $buttons[$_button_type] = $button;
            }
            
            // Isolate response to single array
            if( !empty( $button_type ) ) {
                if( isset( $buttons[$button_type] ) ) {
                    $buttons = $buttons[$button_type];
                }
            }
            
            wp_cache_set( "_get-" . $button_type, $buttons, $this->namespace );
        }
        
        if( !is_admin() ) {
            if( isset( $buttons['pinterest'] ) ) {
                global $post;
                if( !$this->get_post_image( $post ) ) {
                    unset( $buttons['pinterest'] );
                }
            }
        }
        
        return $buttons;
    }
    
    /**
     * Get the count for a particular service
     * 
     * @param string $service The service to retrieve the count for
     * @param string $url The URL to get the total for
     * 
     * @return int
     */
    function get_count( $service, $url ) {
        $count = false;
        
        if( method_exists( $this, "get_count_{$service}" ) ) {
            $count = call_user_func( array( &$this, "get_count_{$service}" ), array( $url ) );
        }
        
        return $count;
    }
    
    function get_count_facebook( $vars ) {
        $url = $vars[0];
        $count = 0;
        $cache_key = $this->namespace . '-facebook-fql-' . md5( $url );
        
        $response = get_transient( $cache_key );
        
        if( !$response ) {
            $fql = "https://graph.facebook.com/fql?q=" . urlencode( "SELECT url, normalized_url, share_count, like_count, comment_count, total_count, commentsbox_count, comments_fbid, click_count FROM link_stat WHERE url='{$url}'" );
            $response = wp_remote_get( $fql, array( 'sslverify' => false ) );
            
            if( !is_wp_error( $response ) ) {
                set_transient( $cache_key, $response, FLARE_STATS_CACHE_LENGTH );
            }
        }
        
        if( !is_wp_error( $response ) ) {
            $response_json = json_decode( $response['body'] );
            
            if( isset( $response_json->data[0]->total_count ) ) {
                $count = (int) $response_json->data[0]->total_count;
            }
        }
        
        return $count;
    }
    
    function get_count_googleplus( $vars ) {
        $url = $vars[0];
        $count = 0;
        $cache_key = $this->namespace . '-googleplus-' . md5( $url );
        
        $response = get_transient( $cache_key );
        
        if( !$response ) {
            $response = wp_remote_get( "https://plusone.google.com/u/0/_/%2B1/fastbutton?count=true&url=" . $url, array( 'sslverify' => false ) );
            
            if( !is_wp_error( $response ) ) {
                set_transient( $cache_key, $response, FLARE_STATS_CACHE_LENGTH );
            }
        }
        
        if( !is_wp_error( $response ) ) {
            $body = $response['body'];
            
            $json_obj = substr( $body, strpos( $body, "window.__SSR = {" ) + 15 );
            $json_obj = substr( $json_obj, 0, strpos( $json_obj, "};" ) + 2 );
            $json_obj = preg_replace( "/(\s)/", "", $json_obj );
            
            $matches = array();
            preg_match( "/c\:([\d\.]+)/", $json_obj, $matches );
            
            if( !empty( $matches ) ) {
                $count = intval( $matches[1] );
            }
        }
        
        return $count;
    }
    
    function get_count_linkedin( $vars ) {
        $url = $vars[0];
        $count = 0;
        $cache_key = $this->namespace . '-linkedin-' . md5( $url );
        
        $response = get_transient( $cache_key );
        
        if( !$response ) {
            $response = wp_remote_get( "http://www.linkedin.com/countserv/count/share?url=" . $url );
            
            if( !is_wp_error( $response ) ) {
                set_transient( $cache_key, $response, FLARE_STATS_CACHE_LENGTH );
            }
        }
        
        if( !is_wp_error( $response ) ) {
            // Strip out the stupid JavaScript code wrapped around the JSON data
            $response_body_clean = preg_replace( "/(^IN\.Tags\.Share\.handleCount\(|\);$)/", "", $response['body'] );
            $response_json = json_decode( $response_body_clean );
            
            if( isset( $response_json->count ) ) {
                $count = (int) $response_json->count;
            }
        }
        
        return $count;
    }
    
    function get_count_pinterest( $vars ) {
        $url = $vars[0];
        $count = 0;
        $cache_key = $this->namespace . '-pinterest-' . md5( $url );
        
        $response = get_transient( $cache_key );

        if( !$response ) {
            $response = wp_remote_get( "http://api.pinterest.com/v1/urls/count.json?callback=count&url=" . $url );
            
            if( !is_wp_error( $response ) ) {
                set_transient( $cache_key, $response, FLARE_STATS_CACHE_LENGTH );
            }
        }
        
        if( !is_wp_error( $response ) ) {
            // Trim the response - no likey white space
            $response_body = trim( $response['body'] );
        
            $response_body = preg_replace('/^.*count\(/', '', $response_body);
            $response_body = preg_replace('/\)$/', '', $response_body);
            
            $response_json = json_decode( $response_body );
            
            if( isset( $response_json->count ) ) {
                $count = (int) $response_json->count;
            }
        }
        
        return $count;
    }
    
    function get_count_reddit( $vars ) {
        $url = $vars[0];
        $count = 0;
        $cache_key = $this->namespace . '-reddit-' . md5( $url );
        
        $response = get_transient( $cache_key );
        
        if( !$response ) {
            $response = wp_remote_get( "http://www.reddit.com/api/info.json?url=" . $url );
            
            if( !is_wp_error( $response ) ) {
                set_transient( $cache_key, $response, FLARE_STATS_CACHE_LENGTH );
            }
        }
        
        if( !is_wp_error( $response ) ) {
            $response_json = json_decode( $response['body'] );
            
            if( isset( $response_json->data->children ) ) {
                foreach( $response_json->data->children as $child ) {
                    if( isset( $child->score ) ) {
                        $count = $count + (int) $child->score;
                    } elseif( isset ( $child->data->score ) ) {
                        $count = $count + (int) $child->data->score;
                    }
                }
            }
        }
        
        return $count;
    }
    
    function get_count_stumbleupon( $vars ) {
        $url = $vars[0];
        $count = 0;
        $cache_key = $this->namespace . '-stumbleupon-' . md5( $url );
        
        $response = get_transient( $cache_key );
        
        if( !$response ) {
            $response = wp_remote_get( "http://www.stumbleupon.com/services/1.01/badge.getinfo?url=" . $url );
            
            if( !is_wp_error( $response ) ) {
                set_transient( $cache_key, $response, FLARE_STATS_CACHE_LENGTH );
            }
        }
        
        if( !is_wp_error( $response ) ) {
            $response_json = json_decode( $response['body'] );
            
            if( isset( $response_json->result->views ) ) {
                $count = (int) $response_json->result->views;
            }
        }
        
        return $count;
    }
    
    function get_count_twitter( $vars ) {
        $url = $vars[0];
        $count = 0;
        $cache_key = $this->namespace . '-twitter-' . md5( $url );
        
        $response = get_transient( $cache_key );
        
        if( !$response ) {
            $response = wp_remote_get( "http://urls.api.twitter.com/1/urls/count.json?url=" . $url );
            
            if( !is_wp_error( $response ) ) {
                set_transient( $cache_key, $response, FLARE_STATS_CACHE_LENGTH );
            }
        }
        
        if( !is_wp_error( $response ) ) {
            $response_json = json_decode( $response['body'] );
            
            if( isset( $response_json->count ) ) {
                $count = (int) $response_json->count;
            }
        }
        
        return $count;
    }
    
    function get_count_buffer( $vars ) {
        $url = $vars[0];
        $count = 0;
        $cache_key = $this->namespace . '-buffer-' . md5( $url );
        
        $response = get_transient( $cache_key );
        
        if( !$response ) {
            $response = wp_remote_get( "https://api.bufferapp.com/1/links/shares.json?url=" . $url, array( 'sslverify' => false ) );
            
            if( !is_wp_error( $response ) ) {
                set_transient( $cache_key, $response, FLARE_STATS_CACHE_LENGTH );
            }
        }
        
        if( !is_wp_error( $response ) ) {
            $response_json = json_decode( $response['body'] );
            
            if( isset( $response_json->shares ) ) {
                $count = (int) $response_json->shares;
            }
        }
        
        return $count;
    }
    
    /**
     * Get the image for a post
     * 
     * Looks up the type of image that is supposed to be retrieved and returns its URL or boolean(false) if
     * no image could be found.
     * 
     * @param object $post The post object to process
     * @param string $source The source location to try
     * @param array $tried_sources The sources that have already been tried
     * 
     * @return mixed
     */
    function get_post_image( $post, $source = null, $tried_sources = array() ) {
        // Set default return value
        $image_src = false;
        
        $sources = array( 'thumbnail', 'content', 'gallery' );
        
        if( !isset( $source ) )
            $source = 'thumbnail';
        
        switch( $source ) {
            default:
            case "content":
                $images = $this->_parse_html_for_images( $post->post_content );
                if( !empty( $images ) ) {
                    $image_src = reset( $images );
                }
            break;
            
            case "gallery":
                $query_args = array(
                    'post_parent' => $post->ID,
                    'posts_per_page' => -1,
                    'post_type' => 'attachment',
                    'post_status' => 'any',
                    'order' => 'ASC',
                    'orderby' => 'menu_order'
                );
                $attachments = new WP_Query( $query_args );
                
                if( !empty( $attachments->posts ) ) {
                    /**
                     * By default, when a media attachment is uploaded, it has no specified menu order, so all
                     * attachments will have a menu_order value of 0 and the sort order will default to the
                     * PRIMARY KEY of the database (the ID column), effectively sorting them by upload order.
                     * Once a user intentionally sorts and saves the gallery order, this gets updated, but lets
                     * make sure and accommodate for the "un-sorted" default as well. 
                     */
                    
                    // Assume no sort has been implied
                    $menu_order_set = false;
                    // Loop through media attachments
                    foreach( $attachments->posts as $post ) {
                        // If any media attachment has a non-zero menu_order, a sort has at one point been implied
                        if( $post->menu_order > 0 ) {
                            $menu_order_set = true;
                        }
                    }
                    
                    // If no sort order has been applied by the user, flip the order so the image that is displayed first in the gallery list is used
                    if( $menu_order_set === false ) {
                        $attachments->posts = array_reverse( $attachments->posts );
                    }
                    
                    $first_image = reset( $attachments->posts );
                    $thumbnail = wp_get_attachment_image_src( $first_image->ID, "full" );
                    $image_src = $thumbnail[0];
                }
            break;
            
            case "thumbnail":
                if( current_theme_supports( 'post-thumbnails' ) ) {
                    if( is_numeric( $post->ID ) ) {
                        $thumbnail_id = get_post_thumbnail_id( $post->ID );
                        if( $thumbnail_id ) {
                            $thumbnail = wp_get_attachment_image_src( $thumbnail_id, "full" );
                            $image_src = $thumbnail[0];
                        }
                    }
                }
            break;
        }
        
        if( $image_src == false ) {
            $tried_sources[] = $source;
            // Only try other sources if we haven't tried them all
            if( count( array_intersect( $sources, $tried_sources ) ) < count( $sources ) ) {
                // Loop through sources to find an untried source to try
                $next_source = false;
                foreach( $sources as $untried_source ) {
                    if( !in_array( $untried_source, $tried_sources ) ) {
                        $next_source = $untried_source;
                    }
                }
                
                if( $next_source ) {
                    $image_src = $this->get_post_image( $post, $next_source, $tried_sources );
                }
            }
        }
        
        return $image_src;
    }

    /**
     * Get the empty scaffold for a button
     * 
     * Builds an "empty" button scaffold ready for display on the page, but without a
     * saved post association in the database.
     * 
     * @param string $button_type The button type to build a scaffold off of
     * 
     * @return array
     */
    function get_scaffold( $button_type ) {
        // The model for the button type
        $button_model = $this->available_buttons[$button_type];
        
        // The button object template
        $button = $this->scaffold;
        $button['type'] = $button_type;
        $button['color'] = $button_model['color'];
        
        // Check for additional button type options
        if( isset( $button_model['options'] ) ) {
            foreach( $button_model['options'] as $key => $props ) {
                $val = $props['value'];
                if( isset( $props['value'] ) )
                    $val = $props['value'];
                
                $button['options'][$key] = $val;
            }
        }
        
        return $button;
    }
    
    /**
     * Save button
     * 
     * Saves the button and returns the ID of the post entry saved
     * 
     * @param array $data The button data to be saved
     * @param array $menu_order Optional menu order
     * 
     * @uses wp_insert_post()
     * @uses update_post_meta()
     * 
     * @return int
     */
    function save( $data, $menu_order = 0 ) {
        $data = array_merge( $this->scaffold, $data );
        foreach( $this->scaffold as $key => $val ) {
            if( isset( $data[$key] ) ) {
                if( is_array( $val ) )
                    $data[$key] = array_merge( $val, $data[$key] );
            } else {
                $data[$key] = $val;
            }
        }
        
        $args = array(
            'post_title' => $data['type'],
            'post_type' => $this->namespace,
            'post_status' => "publish",
            'post_content' => $data['code']['horizontal'],
            'post_excerpt' => $data['code']['vertical'],
        );
        if( !empty( $menu_order ) ) {
            $args['menu_order'] = $menu_order;
        }
        
        if( !empty( $data['ID'] ) )
            $args['ID'] = $data['ID'];
        
        $post_id = wp_insert_post( $args );
        
        update_post_meta( $post_id, "_{$this->namespace}_color", $data['color'] );
        update_post_meta( $post_id, "_{$this->namespace}_mode", $data['mode'] );
        
        $options = array();
        if( isset( $this->available_buttons[$data['type']]['options'] ) ) {
            // Loop through options that should exist
            foreach( $this->available_buttons[$data['type']]['options'] as $name => $props ) {
                $val = $props['value'];
                
                // Validate property for storage
                switch( $props['data'] ) {
                    case "string":
                        $val = "";
                        if( isset( $data['options'][$name] ) ) 
                            $val = $data['options'][$name];
                    break;
                    
                    case "boolean":
                        $val = (bool) ( isset( $data['options'][$name] ) && in_array( $data['options'][$name], array( 1, true, "true", "on" ) ) );
                    break;
                    
                    case "integer":
                        $val = 0;
                        if( isset( $data['options'][$name] ) ) 
                            $val = intval( $data['options'][$name] );
                    break;
                    
                    default:
                        if( isset( $data['options'][$name] ) ) 
                            $val = $data['options'][$name];
                    break;
                }
                
                update_post_meta( $post_id, "_{$this->namespace}_{$data['type']}_{$name}", $val );
            }
        }

        wp_cache_delete( "_get-" . $data['type'], $this->namespace );
        
        return $post_id;
    }
}
