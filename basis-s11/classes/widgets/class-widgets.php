<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class to add widgets
 * @since 0.0.1
 */

class Wp_Basis_Widgets {
	
	public static $default_args = array( 
		'Wb_Basis_Last_Img_Widget'
	);
	
	public static function init( $args = FALSE ) {
		
		// set defaults
		if ( ! $args )
			$args = self :: $default_args;
		
		require( dirname( __FILE__ ) . '/widgets/widget.last_img.php' );
		self :: widgets_init( $args );
	}
	
	public static function widgets_init( $args ) {
		
		foreach( $args as $key ) {
			if ( class_exists( $key ) )
				register_widget( $key );
		}
	}
	
} // end class