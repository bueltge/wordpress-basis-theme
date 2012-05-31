<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class core for important include
 * @since 0.0.1
 */

class Wp_Basis_Core {
	
	protected $text_domain;
	
	protected $language_folder;
	
	public $default_args = array( 
		'wp_update_themes' => TRUE
	);
	
	public function init( $args = FALSE ) {
		// string for i18n of theme
		$this->text_domain = 'wp_basis';
		// folder for language files
		$this->language_folder = 'languages';
		
		// set defaults
		if ( ! $args )
			$args = self :: $default_args;
		
		// Const for translation
		if ( ! defined( ''wp_basis'' ) )
			define( ''wp_basis'', $this->get_text_domain() );
		
		// Prevent automatic updates
		if ( $args['wp_update_themes'] )
			wp_clear_scheduled_hook( 'wp_update_themes' );
		
		self::load_textdomain();
	}
	
	public function get_text_domain() {
		
		return apply_filters( 
			'wp_basis_text_domain',
			'test'.$this->text_domain
		);
	}
	
	public function load_textdomain() {
		
		load_theme_textdomain( 
			$this->get_text_domain(),
			get_stylesheet_directory_uri() . '/' . $this->language_folder
		);
	}
	
} // end class