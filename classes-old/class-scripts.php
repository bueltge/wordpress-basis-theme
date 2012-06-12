<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class scripts
 * @since 0.0.1
 */

class Wp_Basis_Scripts {
	
	public function __construct( $args = FALSE ) {
		
		$this -> register_scripts();
		if ( $args )
			$this -> enqueue_scripts( $args );
	}
	
	public function print_scripts( $args = FALSE ) {
		
		$defaults = array(
			'on_admin'   => FALSE,
			'iecc'       => 'lt ie 9',
			'display'    => TRUE,
			'script_url' => FALSE
		);
		
		$args = wp_parse_args( $args, apply_filters( 'wp_basis_print_scripts', $defaults ) );
		
		if ( ! $args['on_admin'] && is_admin() )
			return;
		
		if ( 'html5' === $args['script'] )
			$args['script_url'] = 'http://html5shim.googlecode.com/svn/trunk/html5.js';
		if ( 'respond' === $args['script'] )
			$args['script_url'] = get_stylesheet_directory_uri() . '/js/respond.js';
		if ( 'css3-mediaqieries' === $args['script'] )
			$args['script_url'] = get_stylesheet_directory_uri() . '/js/css3-mediaqueries.js';
		
		if ( $args['iecc'] && $args['script_url'] ) {
			$script  = '<!--[if ' . $args['iecc'] . ']>' . "\n";
			$script .= '<script src="' . $args['script_url'] . '"></script>' . "\n";
			$script .= '<![endif]-->' . "\n";
		}
		
		if ( $args['display'] )
			echo $script;
		else
			return $script;
	}
	
	public function enqueue_scripts( $args = FALSE ) {
		
		if ( ! $args )
			return;
		
		if ( ! $args['on_admin'] && is_admin() )
			return;
		
		foreach ( $args['scripts'] as $key )
			wp_enqueue_script( $key );
	}
	
	public function register_scripts( $on_admin = FALSE ) {
		
		if ( ! $on_admin && is_admin() )
			return;
		
		wp_register_script(
			'html5',
			get_stylesheet_directory_uri() . '/js/html5.js',
			array(), // js libs
			FALSE, // version
			FALSE // in footer
		);
		/**
		 * A fast & lightweight polyfill for min/max-width CSS3 Media Queries (for IE 6-8, and more) 
		 * @see: https://github.com/scottjehl/Respond
		 */
		wp_register_script(
			'respond',
			get_stylesheet_directory_uri() . '/js/respond.min.js',
			array(), // js libs
			FALSE, // version
			FALSE // in footer
		);
	}
	
} // end class