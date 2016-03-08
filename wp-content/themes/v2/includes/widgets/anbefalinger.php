<?php
/**
 *	Anbefalinger
 */
class RP_Anbefalinger extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_anbefalinger', 'description' => __( "De siste lenkene til eksterne anbefalinger") );
        parent::__construct('rp_anbefalinger', __('Anbefalinger'), $widget_ops);
        $this->alt_option_name = 'widget_recent_entries';

        add_action( 'save_post', array($this, 'flush_widget_cache') );
        add_action( 'deleted_post', array($this, 'flush_widget_cache') );
        add_action( 'switch_theme', array($this, 'flush_widget_cache') );
    }

    function widget($args, $instance) {
        $cache = wp_cache_get('widget_siste_anbefalinger', 'widget');

        if ( !is_array($cache) )
            $cache = array();

        if ( ! isset( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo $cache[ $args['widget_id'] ];
            return;
        }

        ob_start();
        extract($args);

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Anbefalinger' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )    $number = 5;
		$category = isset($instance['category']) ? $instance['category'] : '';
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
        $show_kilde = isset( $instance['show_kilde'] ) ? $instance['show_kilde'] : false;
        $thumbnail = isset( $instance['thumbnail'] ) ? $instance['thumbnail'] : 'ingen';

        $r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'cat' => $category, 'post_type' => 'anbefalinger' ,'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
        if ($r->have_posts()) :
?>
        <?php echo $before_widget; ?>
        <?php if ( $title ) echo $before_title . $title . $after_title; ?>
        <ul class="rp_anbefalinger">
        <?php while ( $r->have_posts() ) : $r->the_post(); ?>
            <li>
                <p class="cp-widget-title"><a href="<?php the_field( 'ekstern_lenke' ) ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>" target="_blank">
				 <?php if ( $thumbnail<>'ingen' ) : ?>
				 <?php if (has_post_thumbnail()) { the_post_thumbnail($thumbnail); } else { echo '<img class="mh-image-placeholder" src="' . get_template_directory_uri() . '/images/noimage_174x131.png' . '" alt="No Picture" />'; } ?>
				 <?php endif; ?>
				<?php if ( get_the_title() ) the_title(); else the_ID(); ?>
				</a></p>
				<?php if (the_content()<>""):?>
					<div class="ekstern-content"><?php the_content();?></div>
				<?php endif; ?>
            <?php if ( $show_date ) : ?>
                <span class="post-date"><?php echo get_the_date(); ?></span>
            <?php endif; ?>
            <?php if ( $show_kilde ) : ?>
				<span class="ekstern-kilde"><?php the_field('kilde');?></span>
            <?php endif; ?>
			
			<div style="clear:both;"></div>
            </li>
        <?php endwhile; ?>
        </ul>
        <?php echo $after_widget; ?>
<?php
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        endif;

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('widget_siste_anbefalinger', $cache, 'widget');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
		$instance['category'] = absint($new_instance['category']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = (bool) $new_instance['show_date'];
        $instance['show_kilde'] = (bool) $new_instance['show_kilde'];
        $instance['thumbnail'] = strip_tags($new_instance['thumbnail']);
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_recent_entries']) )
            delete_option('widget_recent_entries');

        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_siste_anbefalinger', 'widget');
    }

    function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        $show_kilde = isset( $instance['show_kilde'] ) ? (bool) $instance['show_kilde'] : false;
        $thumbnail  = isset( $instance['thumbnail'] ) ?  esc_attr($instance['thumbnail']) : 'ingen';
		if ((!is_numeric($thumbnail)) && !(in_array($thumbnail,array("ingen","cp_small","cp_large")))) $thumbnail='ingen';
?>
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	    <p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select a Category:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('category'); ?>" class="widefat" name="<?php echo $this->get_field_name('category'); ?>">
				<option value="0" <?php if (!$instance['category']) echo 'selected="selected"'; ?>><?php _e('All', 'mh'); ?></option>
				<?php
				$categories = get_categories(array('type' => 'anbefalinger'));
				foreach($categories as $cat) {
					echo '<option value="' . $cat->cat_ID . '"';
					if ($cat->cat_ID == $instance['category']) { echo ' selected="selected"'; }
					echo '>' . $cat->cat_name . ' (' . $cat->category_count . ')';
					echo '</option>';
				}
				?>
			</select>
		</p>
        <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
		<p><input class="checkbox" type="checkbox" <?php checked( $show_kilde ); ?> id="<?php echo $this->get_field_id( 'show_kilde' ); ?>" name="<?php echo $this->get_field_name( 'show_kilde' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'show_kilde' ); ?>"><?php _e( 'Vis kilde?' ); ?></label></p>
		
		<p>Bilde:<br/><input type="radio" id="<?php echo $this->get_field_id( 'thumbnail' ); ?>-ingen" name="<?php echo $this->get_field_name( 'thumbnail' ); ?>" value="ingen" <?php if ($thumbnail=='ingen') echo 'checked="checked"'; ?> ><label for="<?php echo $this->get_field_id( 'thumbnail' ); ?>"><?php _e( 'Ingen' ); ?></label><br/>
		<input type="radio" id="<?php echo $this->get_field_id( 'thumbnail' ); ?>-liten" name="<?php echo $this->get_field_name( 'thumbnail' ); ?>" value="cp_small" <?php if ($thumbnail=='cp_small') echo 'checked="checked"'; ?> ><label for="<?php echo $this->get_field_id( 'thumbnail' ); ?>"><?php _e( 'Liten' ); ?></label><br/>
		<input type="radio" id="<?php echo $this->get_field_id( 'thumbnail' ); ?>-stor" name="<?php echo $this->get_field_name( 'thumbnail' ); ?>" value="cp_large" <?php if ($thumbnail=='cp_large') echo 'checked="checked"'; ?> ><label for="<?php echo $this->get_field_id( 'thumbnail' ); ?>"><?php _e( 'Stor' ); ?></label><br/>
		<input type="radio" id="<?php echo $this->get_field_id( 'thumbnail' ); ?>-custom" name="<?php echo $this->get_field_name( 'thumbnail' ); ?>" value="" <?php if (is_numeric($thumbnail)) echo 'checked="checked"'; ?> > <input id="<?php echo $this->get_field_id( 'thumbnail' ); ?>-custom-val" type="text" value="<?php if (is_numeric($thumbnail)) echo $thumbnail; ?>" onmousedown="jQuery('#<?php echo $this->get_field_id( 'thumbnail' ); ?>-custom').prop('checked', true);" onchange="jQuery('#<?php echo $this->get_field_id( 'thumbnail' ); ?>-custom').val(jQuery(this).val());" size="3" />px</p>
		
<?php
    }
}