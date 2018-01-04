<?php
/**
 * Custom functions for translation
 *
 * @package    WP Basis
 * @since      2012-05-08  0.0.1
 * @version    2018-01-04
 * @author     Frank Bültge <frank@bueltge.de>
 */

/**
 * Set namespace to encapsulating items
 * @link       http://www.php.net/manual/en/language.namespaces.rationale.php
 *
 * @since      2012-05-08  0.0.1
 * @version    2012-05-08
 * @author     Frank Bültge <frank@bueltge.de>
 */
namespace Wp_Basis\I18n;

class I18n {

	protected $text_domain;

	protected $language_folder;

	public function __construct( $prefix = null ) {

		// Fallback for empty parameter.
		if ( null === $prefix ) {
			$prefix = 'wp_basis';
		}
		// string for i18n of theme
		$this->text_domain = $prefix;
		// folder for language files
		$this->language_folder = 'languages';

		$this->load_textdomain();
	}

	public function get_text_domain() {

		// Set filter hook to change outside.
		return apply_filters(
			$this->text_domain . '.text_domain',
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
