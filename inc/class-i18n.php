<?php
/**
 * Custom functions for translation
 * 
 * @package  WP Basis
 * @since    05/08/2012  0.0.1
 * @version  05/05/2012
 * @author   fb
 */

/**
 * Set namespace to encapsulating items
 * @link     http://www.php.net/manual/en/language.namespaces.rationale.php
 * 
 * @since    05/08/2012  0.0.1
 * @version  05/08/2012
 * @author   fb
 */
namespace Wp_Basis\I18n;

class Wp_Basis_I18n {
	
	protected $text_domain;
	
	protected $language_folder;
	
	public function __construct( $args = FALSE ) {
		
		// string for i18n of theme
		$this->text_domain = 'wp_basis';
		// folder for language files
		$this->language_folder = 'languages';
		
		// Const for translation
		if ( ! defined( 'wp_basis' ) )
			define( 'wp_basis', $this->get_text_domain() );
		
		self::load_textdomain();
	}
	
	public function get_text_domain() {
		
		return apply_filters( 
			'wp_basis_text_domain',
			$this->text_domain
		);
	}
	
	public function load_textdomain() {
		
		load_theme_textdomain( 
			$this->get_text_domain(),
			get_stylesheet_directory_uri() . '/' . $this->language_folder
		);
	}
	
} // end class
