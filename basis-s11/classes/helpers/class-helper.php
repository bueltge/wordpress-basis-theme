<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class helper for development
 * @since 0.0.1
 */

class Wp_Basis_Helper {
	
	/**
	 * The class object
	 *
	 * @static
	 * @since  0.0.1
	 * @var    NULL
	 */
	static protected $class_object = NULL;
	
	public function __construct() {

		add_filter( 'contextual_help', array( $this, 'get_screen_info' ), 10, 3);
	}
	
	/**
	 * to load the object and get the current state 
	 *
	 * @access  public
	 * @since   0.0.1
	 * @return  $class_object  Object
	 */
	public function get_object() {
		
		if ( NULL == self::$class_object )
			self::$class_object = new self;
		
		return self::$class_object;
	}
	
	public function get_var_dump( $var = FALSE ) {

		if ( ! $var )
			return;
		
		return var_export( $var, TRUE );
	}

	public function get_screen_info( $contextual_help, $screen_id, $screen ) {
		global $hook_suffix;

		$theme_mods = $this->get_var_dump( get_theme_mods() );

		$content = "<ul>
			<li>hook_suffix: <code>$hook_suffix</code></li>
			<li>screen->id: <code>$screen->id</code></li>
			<li>screen->parent_base: <code>$screen->parent_base</code></li>
			<li>screen->base: <code>$screen->base</code></li>
			<li>theme_mods: $theme_mods</li>	
			</ul>";
		
		$screen->add_help_tab( 
			array( 
				'id' => 'wp-basis-help', 
				'title' => __( 'WP Basis Helper' ), 
				'content' => $content )
		);
		
	}

} // end class
