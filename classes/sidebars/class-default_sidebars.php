<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class to register default sidebars
 * @since 0.0.1
 */

class Wp_Basis_Default_Sidebars {
	
	public static function init() {
		
		add_action( 'widgets_init', array( __CLASS__, 'register_sidebars' ) );
	}
	
	public static function get_text_domain() {
		
		return Wp_Basis_Core :: get_text_domain();
	}
	
	public static function register_sidebars() {
		
		register_sidebar(
			array (
				'id'            => 'sidebar-1',
				'name'          => __( 'Sidebar Main', self :: get_text_domain() ),
				'description'   => __( 'The main Sidebar', self :: get_text_domain() ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>'
			)
		);
	}
	
} // end class