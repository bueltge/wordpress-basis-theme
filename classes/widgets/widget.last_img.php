<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class widgt for last photos
 * @since 0.0.1
 */
if ( ! class_exists( 'Wp_Basis_Templates' ) )
	return;

class Wb_Basis_Last_Img_Widget extends WP_Widget {
	
	function Wb_Basis_Last_Img_Widget() {
		$widget_ops = array('classname' => 'widget_last_img', 'description' => __( 'Die letzten Bilder auf deinem Blog', 'wp_basis') );
		$this->WP_Widget('greyfoto-last-img', __('Photoblog Letzte Bilder', 'wp_basis'), $widget_ops);
		$this->alt_option_name = 'widget_last_img';
		
		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}
	
	function widget($args, $instance) {
		$cache = wp_cache_get('widget_last_img', 'widget');
		
		$image = FALSE;
		
		if ( !is_array($cache) )
			$cache = array();
		
		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}
		
		ob_start();
		extract($args);
		extract($args, EXTR_SKIP);
		
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Letzte Bilder', 'wp_basis') : $instance['title']);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 99 )
			$number = 99;
		if ( !$imgsize = (int) $instance['imgsize'] )
			$imgsize = 40;
		else if ( $imgsize < 1 )
			$imgsize = 1;
		else if ( $imgsize > 200 )
			$imgsize = 200;
		
		$r = new WP_Query( array(
			'showposts' => $number,
			'nopaging' => 0, 
			'post_status' => 'publish', 
			'caller_get_posts' => 1
		) );
		if ( $r->have_posts() ) :
			while ( $r->have_posts() ) : $r->the_post();
				$image = Wp_Basis_Templates :: get_teaser_pics( get_the_ID(), 'srcthumbnail' );
				if ($image) {
					$html = '<a href="' . the_permalink() . 
					'" title="' . esc_attr( get_the_title() ? get_the_title() : get_the_ID() ) . 
					'"><img src="' . $image . '" width="' . $imgsize . 
					'" alt="' . esc_attr( get_the_title() ? get_the_title() : get_the_ID() ) . 
					'" /></a>&nbsp;';
				}
			endwhile;
		?>
			<li><h3><a href="#" id="imglist" title="<?php _e('&ouml;ffnen/schlie&szlig;en', 'wp_basis'); ?>"><?php _e('&#177;', 'wp_basis'); ?><?php echo ' ' . $title; ?></a></h3>
				<div class="imglist">
					<?php echo $html; ?>
				</div>
			</li>
		<?php
			wp_reset_query(); // Restore global post data stomped by the_post().
		endif;
		
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('widget_last_img', $cache, 'widget');
	}
		
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['imgsize'] = (int) $new_instance['imgsize'];
		$this->flush_widget_cache();
		
		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_last_img']) )
			delete_option('widget_last_img');
		
		return $instance;
	}
	
	function flush_widget_cache() {
		wp_cache_delete('widget_last_img', 'widget');
	}
	
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;
		if ( !isset($instance['imgsize']) || !$imgsize = (int) $instance['imgsize'] )
			$imgsize = 20;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:', 'wp_basis'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Anzahl der Bilder, die angezeigt werden:', 'wp_basis'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e('(h&ouml;chstens 99)', 'wp_basis'); ?></small></p>
		
		<p><label for="<?php echo $this->get_field_id('imgsize'); ?>"><?php _e('Breite des Bild:', 'wp_basis'); ?></label>
		<input id="<?php echo $this->get_field_id('imgsize'); ?>" name="<?php echo $this->get_field_name('imgsize'); ?>" type="text" value="<?php echo $imgsize; ?>" size="5" /><br /><small><?php _e('(in Pixel, max. 200px)', 'wp_basis'); ?></small></p>
		<?php
	}

} // end class
?>