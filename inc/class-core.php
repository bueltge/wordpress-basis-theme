<?php
/**
 * Core functions for WP Basis
 *
 * @package    WordPress
 * @subpackage WordPress_Basis_Theme
 * @since      2012-05-08  0.0.1
 * @version    2012-05-08
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
namespace Wp_Basis\Core;

class Core {
	
	public $default_args = array(
		'wp_update_themes' => TRUE
	);
	
	public function init( $args = FALSE ) {
		
		// Prevent automatic updates
		if ( $args['wp_update_themes'] )
			wp_clear_scheduled_hook( 'wp_update_themes' );
	}
	
} // end class