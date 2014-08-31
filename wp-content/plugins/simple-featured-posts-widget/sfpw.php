<?php
/*
Plugin Name: Simple Featured Posts Widget
Plugin URI: http://www.nebulosaweb.com/wordpress/simple-featured-post-widget-articoli-con-immagine-di-anteprima/
Description: Simple Featured Posts is a pratical widget that allows you to show a post list with thumbnails ordered by random or recent posts. You can also choose post's categories and how many posts you want to show.
Author: Fabio Di Stasio
Version: 1.3.2
Author URI: http://nebulosaweb.com
*/

include("sfpw-func.php");

wp_enqueue_style('sfpw-style', plugin_dir_url(__FILE__).'/sfpw-style.css');
load_plugin_textdomain('sfpw', false, 'simple-featured-posts-widget/lang');

class sfpWidget extends WP_Widget {
	function sfpWidget() {
		parent::__construct( 
			false, 
			'Simple Featured Posts Widget',
			array( 'description' => "Show a posts list ordered by random or post date." ) 
		);

	}
	function widget( $args, $instance ) {
		extract($args);
		echo $before_widget;
		echo $before_title.$instance['title'].$after_title;
 
		?>
		<ul id='sfpw'>
			<?php
			global $post;
			$tmp_post = $post;
			$args = array( 
				'numberposts' => $instance['nPosts'], 
				'orderby'=> $instance['order'], 
				'category' => $instance['category'] 
			);
			$myposts = get_posts( $args );
			foreach( $myposts as $post ) : setup_postdata($post); ?>
				<li>
					<?php 
						if($instance['image'] == 1){ 
							
							if(has_post_thumbnail()){ //<- check if the post has a Post Thumbnail assigned to it
								$extractUrl = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail');
								$imageUrl = $extractUrl[0];
							}
							else{
								$imageUrl = first_image();
							}
							
							if($instance['sizeH'] == NULL){ //<- if is set just width
								$size = imgSize(first_image());
								if($instance['size'] == '' or $instance['size'] == 0){
									$w = "150";
								}
								else{
									$w = $instance['size'];
								}
								$h = @ceil($size[1]/($size[0]/$w));
							}
							else{
								if($instance['size'] == '' or $instance['size'] == 0){
									$w = "150";
								}
								else{
									$w = $instance['size'];
								}
								$h = $instance['sizeH'];
							}
							
							if($instance['timthumb'] != ''){//<- if is set timthumb script url
								$imageUrl = $instance['timthumb']."?src=".$imageUrl."&amp;w=".$w."&amp;h=".$h;
							}
							
							echo "<a href='".get_permalink()."' title='".get_the_title()."'><img width='".$w."' height='".$h."' src='".$imageUrl."' alt='".the_title('','',FALSE)."'/></a>";
						} 
					?>
				<h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
				<?php if($instance['date'] == 1):?><span><?php the_time('j F Y') ?></span><?php endif; ?>
				</li>
			<?php endforeach;
			$post = $tmp_post; ?>
		</ul><?php
		wp_reset_postdata();
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
	function form( $instance ) { //<- set default parameters of widget
		if($instance){
			$title = esc_attr($instance['title']);
			$nPosts = esc_attr($instance['nPosts']);
			$order = $instance['order'];
			$category = esc_attr($instance['category']);
			$image = $instance['image'];
			$date = $instance['date'];
			$size = $instance['size'];
			$sizeH = $instance['sizeH'];
			$timthumb = $instance['timthumb'];
		}
		else{
			$title = "Featured Posts";
			$nPosts = 5;
			$order = "rand";
			$category = "";
			$image = 1;
			$date = 1;
			$size = 150;
			$sizeH = '';
			$timthumb = '';
		}?>
		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $title; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('nPosts');?>"><?php _e('Number of posts to show:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('nPosts');?>" name="<?php echo $this->get_field_name('nPosts');?>" type="text" value="<?php echo $nPosts; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('order');?>"><?php _e('Order:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('order');?>" name="<?php echo $this->get_field_name('order');?>" type="radio">
				<option value='<?php if($order == "rand"):?>rand<?php else:?>post_date<?php endif?>'><?php if($order == "rand"):?><?php _e('Random'); ?><?php else:?><?php _e('Recent Posts'); ?><?php endif?></option>
				<option value='<?php if($order == "rand"):?>post_date<?php else:?>rand<?php endif?>'><?php if($order == "rand"):?><?php _e('Recent Posts'); ?><?php else:?><?php _e('Random'); ?><?php endif?></option>
			</select>
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id('category');?>"><?php _e('Category ID (optional):','sfpw'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('category');?>" name="<?php echo $this->get_field_name('category');?>" type="text" value="<?php echo $category; ?>"/>
			<small><?php _e('Category IDs, separated by commas'); ?></small>
		</p>
		<p>
			<input class="checkbox" <?php if($date == 1): ?>checked="checked"<?php endif?> id="<?php echo $this->get_field_id('date');?>" name="<?php echo $this->get_field_name('date');?>" type="checkbox" value="1"/>
			<label for="<?php echo $this->get_field_id('date');?>"><?php _e('Show date','sfpw'); ?></label> 
		</p>
		<p>
			<input class="checkbox" <?php if($image == 1): ?>checked="checked"<?php endif?> id="<?php echo $this->get_field_id('image');?>" name="<?php echo $this->get_field_name('image');?>" type="checkbox" value="1"/>
			<label for="<?php echo $this->get_field_id('imahe');?>"><?php _e('Show thumbnail','sfpw'); ?></label> 
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('size');?>"><?php _e('Thumbnail witdh:','sfpw'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('size');?>" name="<?php echo $this->get_field_name('size');?>" type="text" value="<?php echo $size; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('sizeH');?>"><?php _e('Thumbnail height:','sfpw'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('sizeH');?>" name="<?php echo $this->get_field_name('sizeH');?>" type="text" value="<?php echo $sizeH; ?>"/>
			<small><?php _e('Automatically set if blank'); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('timthumb');?>"><?php _e('TimThumb script URL','sfpw'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('timthumb');?>" name="<?php echo $this->get_field_name('timthumb');?>" type="text" value="<?php echo $timthumb; ?>"/>
			<small><?php _e('Example "/scripts/timthumb.php"'); ?></small>
		</p>
		<?php
	}
}
 
function sfpw_register() {
	register_widget( 'sfpWidget' );
}
 
add_action( 'widgets_init', 'sfpw_register' );
?>