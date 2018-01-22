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
 * Set namespace to encapsulating items.
 *
 * @link       http://www.php.net/manual/en/language.namespaces.rationale.php
 * @since      2018-01-03
 * @version    2018-01-03
 * @author     Frank Bültge <frank@bueltge.de>
 */
namespace Wp_Basis\Gutenberg;

class Gutenberg {

	private $prefix;

	/**
	 * Gutenberg constructor.
	 *
	 * @param string|null $prefix
	 */
	public function __construct( $prefix = null ) {

		$this->prefix = $prefix;
		$this->init();
	}

	/**
	 * Include our functions in the WP stack.
	 */
	public function init() {

		add_action( 'after_setup_theme', [ $this, 'init_gutenberg' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_style' ] );
	}

	/**
	 * Initialize the theme support for Gutenberg.
	 */
	public function init_gutenberg() {

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
				'colors'      => array(
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


	/**
	 * Register and Enqueue the CSS stylesheet for Gutenberg Editor.
	 *
	 * @link  https://wordpress.org/gutenberg/handbook/blocks/applying-styles-with-stylesheets/
	 */
	public function enqueue_style() {

		// set suffix for debug mode
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style(
			 $this->prefix . '_gutenberg',
			\get_template_directory_uri() . '/assets/css/gutenberg_editor' . $suffix . '.css',
			false,
			'2018-01-03',
			'screen'
		);
		wp_enqueue_style( $this->prefix . '_gutenberg' );
	}

}
