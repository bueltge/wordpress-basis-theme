<?php
/**
 * Core functions for WP Basis
 *
 * @package    WordPress
 * @subpackage WordPress_Basis_Theme
 * @since      2012-05-08  0.0.1
 * @version    2018-01-04
 * @author     Frank Bültge <frank@bueltge.de>
 */

/**
 * Set namespace to encapsulating items.
 *
 * @link       http://www.php.net/manual/en/language.namespaces.rationale.php
 * @since      2012-05-08  0.0.1
 * @version    2012-05-08
 * @author     Frank Bültge <frank@bueltge.de>
 */
namespace Wp_Basis\Core;

class Core {

	private $wp_update_themes;
	private $prefix;

	public function __construct( $prefix = null ) {

		// Fallback for empty parameter.
		if ( null === $prefix ) {
			$prefix = 'wp_basis';
		}

		$this->prefix = $prefix;
		$this->wp_update_themes = true;

		$this->init();
	}

	public function init() {

		// Prevent automatic updates
		if ( $this->wp_update_themes ) {
			wp_clear_scheduled_hook( 'wp_update_themes' );
		}
	}

	/**
	 * Return the prefix for usage on hooks, domain, text domain.
	 *
	 * @return string
	 */
	public function get_prefix() {

		return $this->prefix;
	}

} // end class
