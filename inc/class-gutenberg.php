<?php
/**
 * Add theme support for Gutenberg Editor project.
 *
 * @package    WP Basis
 * @since      2018-01-03
 * @version    2018-01-03
 * @author     Frank Bültge <frank@bueltge.de>
 */

/**
 * Set namespace to encapsulating items
 * @link     http://www.php.net/manual/en/language.namespaces.rationale.php
 *
 * @since      2018-01-03
 * @version    2018-01-03
 * @author     Frank Bültge <frank@bueltge.de>
 */
namespace Wp_Basis\Gutenberg;

add_action( 'after_setup_theme', __NAMESPACE__ . '\\init_gutenberg' );
/**
 * Initialize the support for Gutenberg.
 */
function init_gutenberg() {

	/**
	 * Add support for the Gutenberg Editor.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/reference/theme-support/
	 */
	add_theme_support(
		'gutenberg',
		array(
			// Full width images and other content such as videos.
			// Set to false if the theme should not support them.
			'wide-images' => true,
			// Define custom colours for use in the editor.
			// A nice way to provide consistency in user editable content.
			'colors' => array(
				'#ffffff',
				'#ffffff',
				'#ffffff',
				'#ffffff',
				'#ffffff',
				'#ffffff',
			),
		)
	);
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_style' );
/**
 * Register and Enqueue the CSS stylesheet for Gutenberg blocks.
 */
function enqueue_style() {

	wp_register_style(
		'wp_basis_gutenberg',
		get_theme_file_uri( '/assets/css/gutenberg.css' ),
		false,
		'1.0',
		'screen'
	);
	wp_enqueue_style( 'wp_basis_gutenberg' );
}