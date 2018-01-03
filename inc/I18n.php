<?php
/**
 * Custom functions for translation
 *
 * @package    WP Basis
 * @since      2012-05-08  0.0.1
 * @version    01/21/2013
 * @author     Frank Bültge <frank@bueltge.de>
 */

/**
 * Set namespace to encapsulating items
 * @link       http://www.php.net/manual/en/language.namespaces.rationale.php
 *
 * @since      2012-05-08  0.0.1
 * @version    05/08/2012
 * @author     Frank Bültge <frank@bueltge.de>
 */
namespace Wp_Basis\I18n;

class I18n {

	protected $text_domain;

	protected $language_folder;

	public function __construct( $args = false ) {

		// string for i18n of theme
		$this->text_domain = 'wp_basis';
		// folder for language files
		$this->language_folder = 'languages';

		// Constant for translation.
		if ( ! defined( 'WP_BASIS' ) ) {
			define( 'WP_BASIS', $this->get_text_domain() );
		}

		$this->load_textdomain();
	}

	public function get_text_domain() {

		return apply_filters(
			'wp_basis_text_domain',
			$this->text_domain
		);
	}

	public function load_textdomain() {

		load_theme_textdomain(
			$this->text_domain,
			get_stylesheet_directory_uri() . DIRECTORY_SEPARATOR . $this->language_folder
		);
	}

} // end class
