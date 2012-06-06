<?php
/**
 * Setup and initialization of theme functions and definitions
 * 
 * @package  WP Basis
 * @since    05/08/2012  0.0.1
 * @version  06/05/2012
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
namespace Wp_Basis\Setup;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 * 
 * @since    05/08/2012  0.0.1
 * @version  06/05/2012
 * @author   fb
 */
add_action( 'after_setup_theme', '\Wp_Basis\Setup\wp_basis_setup' );
function wp_basis_setup() {
	
	/**
	 * Make the theme available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Twelve, use a find and replace
	 * to change 'wp_basis' to the name of your theme in all the template files.
	 */
	require_once( 'class-i18n.php' );
	new \Wp_Basis\I18n\Wp_Basis_I18n;
	
}
