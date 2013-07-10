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
 * Set the content width in pixels based on the theme's design and stylesheet.
 * Also the width of oEmbed objects to scale specific size
 *
 * @since 05/08/2012  0.0.1
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

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
add_action( 'after_setup_theme', '\Wp_Basis\Setup\setup' );
function setup() {
	
	/**
	 * Make the theme available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Twelve, use a find and replace
	 * to change 'wp_basis' to the name of your theme in all the template files.
	 */
	new \Wp_Basis\I18n\I18n;
	
	/**
	 * Allow to easily brand the WordPress login and admin screens
	 */
	require_once( 'admin/class-branding.php' );
	new \Wp_Basis\Admin_Branding\Wp_Basis_Admin_Branding;
	
	/**
	 * Custom functions for comments
	 * See the documentation inside the file for more inforamtion and possibilities
	 */
	require_once( 'comments/comment.php' );
	
	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );
}

add_action( 'wp_enqueue_scripts', '\Wp_Basis\Setup\scripts' );
function scripts() {
	
	// set suffix for debug mode
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';
	
	// load stylesheet inside the css folder
	wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/assets/css/style' . $suffix . '.css' );
	
	// load comment reply script, if comments open, if thread comments are active and also on single view
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
}
