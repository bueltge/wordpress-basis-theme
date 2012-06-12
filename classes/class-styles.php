<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class styles
 * @since 0.0.1
 */

class Wp_Basis_Styles {
	
	public function __construct( $args = FALSE ) {
		
		$this -> register_styles();
		if ( $args )
			$this -> enqueue_styles( $args );
	}
	
	public function enqueue_styles( $args = FALSE ) {
		
		if ( ! $args )
			return;
		
		if ( ! isset( $args['on_admin'] ) )
			$args['on_admin'] = FALSE;
		
		if ( ! $args['on_admin'] && is_admin() )
			return;
		
		foreach ( $args['styles'] as $key )
			wp_enqueue_style( $key );
	}
	
	public function register_styles( $on_admin = FALSE ) {
		
		if ( ! $on_admin && is_admin() )
			return;
		
		wp_register_style(
			'normalize',
			Wp_Basis_Templates :: get_css_dir() . 'normalize.css'
		);
		wp_register_style(
			'wp_basis_core',
			Wp_Basis_Templates :: get_css_dir() . 'core.css'
		);
	}
	
} // end class