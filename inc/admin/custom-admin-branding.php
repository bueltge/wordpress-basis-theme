<?php
/**
 * Plugin Name: Custom Admin Branding
 * Plugin URI:  https://github.com/bueltge/WordPress-Basis-Theme/tree/namespace/inc/admin
 * Description: Allow theme/plugin developers to easily brand the WordPress login and admin screens
 * Version:     0.0.1
 * Author:      Frank Bültge
 * Author URI:  http://bueltge.de/
 */

! defined( 'ABSPATH' ) and exit;

if ( class_exists( 'Wp_Basis_Admin_Branding' ) )
	return NULL;

require_once( trailingslashit( dirname( __FILE__ ) ) . 'class-branding.php' );

// Create a new instance of the `Wp_Basis_Admin_Branding` class
// Pass in whatever values you want (see the "Arguments" section below)
new Wp_Basis_Admin_Branding( array( 
			'login_url'       => home_url( '/' ),
			'login_image'     => FALSE,
			'login_title'     => get_bloginfo( 'name', 'display' ),
			'login_height'    => '67px',
			'login_width'     => '326px',
			'designer_url'    => 'http://bueltge.de',
			'designer_anchor' => 'Frank Bültge',
			'favicon_url'     => TRUE,
			'remove_wp'       => TRUE,
			'remove_gravatar' => TRUE,
			'login_style'     => 'http://cafe-brueheim.de/wp-content/plugins/custom-admin-branding/login.css'
) );

add_filter( 'site_transient_update_plugins', 'Wp_Basis_remove_update_nag' );
/**
 * Disable plugin update notifications
 * 
 * @param  unknown_type $value
 * @since  01/07/2013
 * @link   http://dd32.id.au/2011/03/01/disable-plugin-update-notification-for-a-specific-plugin-in-wordpress-3-1/
 * @retrun array string $value
 */
function Wp_Basis_remove_update_nag( $value ) {
	
	if ( isset( $value ) && is_object( $value ) )
		unset( $value->response[ plugin_basename(__FILE__) ]);
	
	return $value;
}