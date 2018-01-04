<?php
/**
 * Setup and initialization of theme functions and definitions
 *
 * @package    WordPress
 * @subpackage WordPress_Basis_Theme
 * @since      2012-05-08  0.0.1
 * @version    2018-01-03
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
namespace Wp_Basis\Setup;

use Wp_Basis\Admin_Branding\Wp_Basis_Admin_Branding;
use Wp_Basis\I18n\I18n;

/**
 * Set the content width in pixels based on the theme's design and stylesheet.
 * Also the width of oEmbed objects to scale specific size
 *
 * @since    2012-05-08  0.0.1
 */
if ( ! isset( $content_width ) ) {
	/** @noinspection PhpUnusedLocalVariableInspection */
	$content_width = 640;
} /* pixels */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 * 
 * @since      2012-05-08  0.0.1
 * @version    2014-04-29
 * @author     Frank Bültge <frank@bueltge.de>
 */
add_action( 'init', __NAMESPACE__ . '\\setup' );
function setup() {
	
	/**
	 * Make the theme available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Twelve, use a find and replace
	 * to change 'wp_basis' to the name of your theme in all the template files.
	 */
	new I18n();
	
	/**
	 * Allow to easily brand the WordPress login and admin screens
	 */
	require_once 'admin/class-branding.php';
	new Wp_Basis_Admin_Branding();
	
	/**
	 * Custom functions for comments
	 * See the documentation inside the file for more information and possibilities
	 */
	require_once 'comments/comment.php';

	/**
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 * Usable since WordPress Version 4.1
	 *
	 * @see   https://github.com/bueltge/wordpress-basis-theme/issues/8
	 * @since 2014-11-03
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );
}

add_action( 'wp_enqueue_scripts', '\Wp_Basis\Setup\scripts' );
function scripts() {
	
	// set suffix for debug mode
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	
	// load stylesheet inside the css folder
	wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/assets/css/style' . $suffix . '.css' );
	
	// load comment reply script, if comments open, if thread comments are active and also on single view
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
