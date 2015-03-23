<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class capabilities
 * @since 0.0.1
 */

class Wp_Basis_Capabilities {
	
	public static function init( $args = FALSE ) {
		
		if ( ! $args )
			return;
		
		add_action('admin_init', array( __CLASS__, 'allow_uploads' ) );
	}
	
	public static function allow_uploads( $roles = FALSE ) {
		
		if ( ! is_array( $roles ) )
			return;
		
		if ( ! $roles )
			$roles = array( 'contributor' );
		
		foreach ($roles as $role) {
			if ( current_user_can($role) && ! current_user_can('upload_files') ) {
				$contributor = get_role( $role );
				$contributor -> add_cap( 'upload_files' );
			}
		}
	}
	
}
