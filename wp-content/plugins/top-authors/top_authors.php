<?php
/**
 * Plugin Name: Top Authors
 * Plugin URI: http://developr.nl/work/top-authors
 * Description: A highly customizable widget that sums the top authors(most contributing) on your blog
 * Version: 0.5.7
 * Author: developR | Seb van Dijk
 * Author URI: http://www.developr.nl
 *
 */

/**
 * Add function to widgets_init that'll load top_authors
 */
add_action( 'widgets_init', 'top_authors' );

/**
 * Register our Top_Authors widget.
 *
 */
function top_authors() {
	register_widget( 'Top_Authors' );
}

/**
 * Top Authors Widget Class
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class Top_Authors extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Top_authors	() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Top Authors', 'description' => __('A widget that sums the top authors on your blog', 'top_authors') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'top_authors' );

		/* Create the widget. */
		$this->WP_Widget( 'top_authors' , __( 'Top Authors' , 'top_authors' ), $widget_ops, $control_ops );
	}

	/**
	 * This is the part where the heart of this widget is!
	 * here we get al the authors and count their posts. 
	 *
	 * The frontend function
	 */
	function widget( $args, $instance ) {
		extract( $args );
		
		// default values 
		$exclude_admin = false;
		$exclude_zero = false;
		
		/* Our variables from the widget settings. (nice tabbed huh!?)*/
		if(isset($instance))
		{
			if( isset( $instance[ 'title' ] ) ) {
				$title = 				apply_filters( 'widget_title', $instance[ 'title' ] );
			}
			
			if( isset( $instance[ 'number' ]) ) {
				$number_of_authors = 	$instance[ 'number' ];
			}
			
			if( isset( $instance[ 'template' ] ) ) {
				$template = 			htmlspecialchars_decode( $instance[ 'template' ] );
			}
			
			if( isset( $instance[ 'before' ] ) ) {
				$before_the_list =		htmlspecialchars_decode( $instance[ 'before' ] );
			}
			
			if( isset( $instance[ 'after' ] ) ) {
				$after_the_list = 		htmlspecialchars_decode( $instance [ 'after' ] );
			}
			
			if( isset( $instance[ 'gravatar_size' ] ) ) {
				$gravatar_size =		$instance[ 'gravatar_size' ];
			}
			
			if( isset( $instance[ 'exclude_admin' ] ) ) {
				$exclude_admin = 		$instance[ 'exclude_admin' ];
		 	}
		 	
			if( isset( $instance[ 'exclude_zero' ] ) ) {
				$exclude_zero = 		$instance[ 'exclude_zero' ];
			}
			
			if( isset( $instance[ 'include_CPT' ] ) ) {
				$include_CPT = 		$instance[ 'include_CPT' ];
			}
			
			
			if( isset( $instance[ 'linkbase'] ) ) {
				$author_slug =			$instance[ 'linkbase' ];
			}
			
			if( isset( $instance[ 'author_link' ] ) ) {
				$author_link = 			$instance[ 'author_link' ];
			}
		}
		if( !isset( $author_slug ) ) { $author_slug = 'author' ; }
		if( !isset( $author_link ) ) { $author_link = 'username' ;}
		
		// define vars
		$counter=0;
		
		/* Before widget (defined by themes). */
		if( isset( $before_widget ) ) {
			echo $before_widget;
		}

		/* Display the widget title if one was input (before and after defined by themes). */
		if( isset( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		$user_list = array();
		
		$blogusers = get_users(); // doh

		// this part can be a heavyload process if you have a lot of authors
	
 		if ( $blogusers ) {
		  foreach ( $blogusers as $bloguser ) {
		  	
		   	$user_list[] = $bloguser->ID;
		   }
		   global $wpdb;
		
		   
		  // to add CPT support we have to use a custom query
		  foreach ($user_list as $user)
			{
					
					if(isset($include_CPT))
					{
						$posts[$user] = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_author = $user AND NOT post_type = 'page' AND post_status = 'publish'" );
					}else{
						$posts[$user] = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_author = $user AND post_type = 'post' AND NOT  post_type = 'page' AND post_status = 'publish'" );
					}
			}
	 
		 arsort( $posts ); //use asort($user_list) if ascending by post count is desired
		
		  
		  // user defined html element before the list
		  if( $user_list ) { echo $before_the_list; }
		
		  if( count( $user_list ) < $number_of_authors )
		  {
		  	$number_of_authors=count($user_list);
		  }

		 foreach($posts as  $userid => $post) 
		 {
			$counter++;
			if($counter>$number_of_authors)
			{
				break;
			}

			// create a WP user object
			$user = new WP_User( $userid );
			
			// detect if user is administrator
			// Introduced in version 0.5 of top-authors. Hope this is fool-proof.
			if( isset( $user->wp_capabilities[ 'administrator' ] ) || isset( $user->blog_capabilities[ 'administrator' ] ) ){
				$user_is_admin = true;
			}
			else
			{
				$user_is_admin = false;
			}
		
			$author_posts_url = get_author_posts_url($userid);
			
			if(!$user->user_firstname && !$user->user_lastname)
			{
				$user->user_firstname = $user->user_login;
			}    
			//replace anchors in usertemplate		
			//author_slug / display_name	
			
			// linkbase - author_link
			
			$arr_replace['username'] = $user->user_login;
			$arr_replace['nickname'] = $user->nickname;
			$arr_replace['display_name'] = $user->display_name;
			
			$output = str_replace("%linktoposts%",get_bloginfo("url") .'/'.$author_slug.'/'.str_replace(" ","-",strtolower($arr_replace[$author_link])),$template);
			
			$output = str_replace("%firstname%",$user->user_firstname,$output);
			$output = str_replace("%lastname%",$user->user_lastname,$output);
			$output = str_replace("%nrofposts%",$post,$output);
			$output = str_replace("%nickname%",$user->nickname,$output);
			$output = str_replace("%displayname%",$user->display_name,$output);
			$output = str_replace("%author_id%",$user->ID,$output);
			$output = str_replace("%link_author_id%",get_bloginfo("url") .'/?author=' .$user->ID,$output);
			
			$gravatar_detect = strpos($output,"%gravatar%");
			
			if($gravatar_detect !== false){
				$gravatar = get_avatar($user->ID, $gravatar_size);
				 $output = str_replace("%gravatar%",$gravatar,$output);
			}
			 
			  if(($user_is_admin && $exclude_admin == "on") || ($post<1 && $exclude_zero=="on"))
			  {
			  	// aiii we skipped a user but we still want to get the total number of users right!
			  	$counter--;
			  }
			  else
			  {
				  // newline in html, al  for the looks!
				  echo $output ."\n";
			  }
			}
	
		  // user defined html after the list
		  if($user_list){echo $after_the_list;}
		}

		/* After widget (defined by themes). */
		echo $after_widget;
	
	}




	/**
	 * Update the widget settings.
	 *
	 * Backend widget settings
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = 			strip_tags( $new_instance['title'] );
		
		// htmlspecialchars to save html markup in database, at frontend we use htmlspecialchars_decode
		$instance['template'] = 		htmlspecialchars($new_instance['template']);
		
		$instance['linkbase'] = 		$new_instance['linkbase'];
		$instance['author_link']	=	$new_instance['author_link'];
		
		$instance['before'] = 			htmlspecialchars($new_instance['before']);
		$instance['after'] = 			htmlspecialchars($new_instance['after']);
		
		$instance['exclude_admin'] =	$new_instance['exclude_admin'];
		$instance['exclude_zero'] =		$new_instance['exclude_zero'];
		$instance['include_CPT']	= $new_instance['include_CPT'];
		// check if datainput isnummeric
		if(is_numeric($new_instance['gravatar_size']))
		{
			$instance['gravatar_size'] = 	$new_instance['gravatar_size'];
		}
		
		// check if datainput isnummeric and postive and under 100
		if(is_numeric($new_instance['number']))
		{
			if($new_instance['number'] <100 && $new_instance['number'] >0)
			{
				$instance['number'] =  $new_instance['number'];
			}
			else
			{
				if($new_instance['number'] < 1)
				{
					$instance['number'] = 1;
				}	
				else
				{
					$instance['number'] = 99;
				}
			
			}
		}
		

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 *
	 * Backend widget options form
	 */
	function form( $instance ) {
		$defaults = array( 
			'title' => __(	'Top Authors', 'top_authors'), 
							'number' => __(5, 'top_authors'), 
							'template' => __('<li><a href="%linktoposts%">%gravatar% %firstname% %lastname% </a> number of posts: %nrofposts%</li>', 'top_authors'),
							'linkbase' => 'author',
							'author_link' => 'username',
							'before' => __('<ul>', 'top_authors'),
							'after' => __('</ul>', 'top_authors'),
							'gravatar_size' => __(24),
							
						);
						
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>Thank you for using this widget, please, give me some <a href="mailto:feedback@developr.nl">feedback</a>! And <a href="http://wordpress.org/extend/plugins/top-authors/" target="_blank">rate</a> this plugin.</p>	
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:96%;float:right;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number of authors: (1-99)', 'top_authors'); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" style="width:96%;float:right;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude_admin' ); ?>"><?php _e('Exclude administrator users?', 'top_authors'); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'exclude_admin' ); ?>" name="<?php echo $this->get_field_name( 'exclude_admin' ); ?>" <?php if(isset($instance['exclude_admin'])){echo " checked=checked";} ?> />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude_zero' ); ?>"><?php _e('Exclude users without posts?', 'top_authors'); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'exclude_zero' ); ?>" name="<?php echo $this->get_field_name( 'exclude_zero' ); ?>" <?php if(isset($instance['exclude_zero'])){echo " checked=checked";} ?> />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'include_CPT' ); ?>"><?php _e('Include Custom Post Types?', 'top_authors'); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'include_CPT' ); ?>" name="<?php echo $this->get_field_name( 'include_CPT' ); ?>" <?php if(isset($instance['include_CPT'])){echo " checked=checked";} ?> />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'template' ); ?>"><?php _e('HTML template use: (%linktoposts% | %firstname% | %lastname% | %displayname% | %nickname% | %nrofposts% | %author_id% | %gravatar% )', 'top_authors'); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'template' ); ?>" name="<?php echo $this->get_field_name( 'template' ); ?>"  style="width:100%;height:100px;"><?php echo $instance['template']; ?></textarea>
		</p>
		<p>Linkbase and auhtor link used to find your author page. (don't touch in default WP config). You will need this when using a plugin like author_slug.
		<br /> example how used www.example.com/slug/author_link  </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'linkbase' ); ?>"><?php _e('author slug', 'top_authors'); ?></label>
			<input id="<?php echo $this->get_field_id( 'linkbase' ); ?>" name="<?php echo $this->get_field_name( 'linkbase' ); ?>" value="<?php echo $instance['linkbase']; ?>" style="width:50%;float:right;" />
		</p>
		
		
				<p>
			<label for="<?php echo $this->get_field_id( 'author_link' ); ?>"><?php _e('author_link', 'top_authors'); ?></label>
			<?php
				$select_un = false; $select_dn = false; $select_nn = false;
				if(isset($instance['author_link'])){
					switch($instance['author_link'])
					{
						case "username":
							$select_un = ' selected="selected" ';
						break;
						
						case "display_name":
							$select_dn = ' selected="selected" ';
						break;
						
						case "nickname":
							$select_nn = ' selected="selected" ';
						break;
					}	
				}
				else
				{
					$select_un = ' selected="selected ';
				}
				
			?>
			
		
			<select name="<?php echo $this->get_field_name( 'author_link' ); ?>" id="<?php echo $this->get_field_id( 'author_link' ); ?>" style="width:50%;float:right;">
				<option <?php echo $select_un; ?> value="username">Username (default WP) </option>
				<option <?php echo $select_dn; ?> value="display_name">Display Name</option>
				<option <?php echo $select_nn; ?> value="nickname">Nickname</option>
			</select>
			
				</p>
		
		
		<p>
			<label for="<?php echo $this->get_field_id( 'before' ); ?>"><?php _e('before the list', 'top_authors'); ?></label>
			<input id="<?php echo $this->get_field_id( 'before' ); ?>" name="<?php echo $this->get_field_name( 'before' ); ?>" value="<?php echo $instance['before']; ?>" style="width:50%;float:right;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'after' ); ?>"><?php _e('after the list', 'top_authors'); ?></label>
			<input id="<?php echo $this->get_field_id( 'after' ); ?>" name="<?php echo $this->get_field_name( 'after' ); ?>" value="<?php echo $instance['after']; ?>" style="width:50%;float:right;" />
		</p>
			<p>
			<label for="<?php echo $this->get_field_id( 'gravatar_size' ); ?>"><?php _e('size of gravatar', 'top_authors'); ?></label>
			<input id="<?php echo $this->get_field_id( 'gravatar_size' ); ?>" name="<?php echo $this->get_field_name( 'gravatar_size' ); ?>" value="<?php echo $instance['gravatar_size']; ?>" style="width:50%;float:right;" />
		</p>
			
	<?php
	}
}
?>