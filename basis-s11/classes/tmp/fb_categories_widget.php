<?php

// widget for wp 2.8 and higher
class FB_Categories_Widget extends WP_Widget {

	function FB_Categories_Widget() {
		$widget_ops = array('classname' => 'widget_categories', 'description' => __( 'A list of categories ', FB_GREYFOTO_TEXTDOMAIN) );
		$this->WP_Widget('greyfoto-categories', __('Categories', FB_GREYFOTO_TEXTDOMAIN), $widget_ops);
		$this->alt_option_name = 'widget_categories';
		
		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_last_img', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);
		extract($args, EXTR_SKIP);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Letzte Bilder', FB_GREYFOTO_TEXTDOMAIN) : $instance['title']);
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
		
		$r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1));
		if ( $r->have_posts() ) :
?>
		<li><h3><a href="#" id="imglist" title="<?php _e('&ouml;ffnen/schlie&szlig;en', FB_GREYFOTO_TEXTDOMAIN); ?>"><?php _e('&#177;', FB_GREYFOTO_TEXTDOMAIN); ?><?php echo ' ' . $title; ?></a></h3>
			<div class="imglist">
			<?php while ( $r->have_posts() ) : $r->the_post();
				
				if ( function_exists('fb_meta_image') )
					$image = fb_meta_image();
				if ( $image != '' ) {
					if ( strpos( $image, 'http://' ) )
						$image = clean_url($image);
					else
						$image = WP_CONTENT_URL . '/uploads/' . attribute_escape($image);
				} elseif ( function_exists('fb_getTeaserPics') ) {
					$image = fb_getTeaserPics(get_the_ID(), 'srcthumbnail');
				}
				if ($image) { ?>
					<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><img src="<?php echo $image; ?>" width="<?php echo $imgsize; ?>" alt="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>" /></a>&nbsp;
			<?php } 
			endwhile; ?>
			</div>
		</li>
<?php
			wp_reset_query();  // Restore global post data stomped by the_post().
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
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:', FB_GREYFOTO_TEXTDOMAIN); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Anzahl der Bilder, die angezeigt werden:', FB_GREYFOTO_TEXTDOMAIN); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e('(h&ouml;chstens 99)', FB_GREYFOTO_TEXTDOMAIN); ?></small></p>
			
		<p><label for="<?php echo $this->get_field_id('imgsize'); ?>"><?php _e('Breite des Bild:', FB_GREYFOTO_TEXTDOMAIN); ?></label>
		<input id="<?php echo $this->get_field_id('imgsize'); ?>" name="<?php echo $this->get_field_name('imgsize'); ?>" type="text" value="<?php echo $imgsize; ?>" size="5" /><br /><small><?php _e('(in Pixel, max. 200px)', FB_GREYFOTO_TEXTDOMAIN); ?></small></p>
<?php
	}
}

add_action( 'widgets_init', create_function('', 'return register_widget("FB_Categories_Widget");') );
?>