<?php
/**
 * Init wor Widgetized area.
 * 
 * @package  WP Basis
 * @since    06/05/2012  0.0.1
 * @version  06/05/2012
 * @author     Frank Bültge <frank@bueltge.de>
 */

/**
 * Set namespace to encapsulating items
 * @link     http://www.php.net/manual/en/language.namespaces.rationale.php
 * 
 * @since    06/05/2012  0.0.1
 * @version  06/05/2012
 * @author   Frank Bültge <frank@bueltge.de>
 */
namespace Wp_Basis\Widgets_Init;

// Add theme support for selective refresh for widgets.
add_theme_support( 'customize-selective-refresh-widgets' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since    06/05/2012  0.0.1
 * @version  06/05/2012
 * @author   Frank Bültge <frank@bueltge.de>
 * @link     https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
add_action( 'widgets_init', __NAMESPACE__ . '\\widgets_init' );
function widgets_init() {

	register_sidebar( array(
		'id'            => 'sidebar-1',
		'name'          => esc_attr__( 'Sidebar', 'wp_basis' ),
		'description'   => esc_attr__( 'The primary Sidebar', 'wp_basis' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
