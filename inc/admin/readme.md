# Custom Admin Branding

A class to allow theme/plugin developers to easily brand the WordPress login and admin screens

## Features

* Change the login page logo
* Change the login page logo link URL and title attribute
* Add a designer credit to the login page and admin footer
* Remove the built in WordPress menu item from the admin menu bar
* Add a favicon to the login, admin and front end of the site
* Remove Gravatar icon in admin bar
* Add custom style sheet

## Usage

```php
	<?php
	// somewhere in `functions.php`
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
				'remove_gravatar' => FALSE,
				'login_style'     => FALSE
	) );
```

### Arguments

* `login_url`       - Where would you like the logo above the login form to link? Defaults to wordpress.org
* `login_image`     - What will replace the WordPress logo on the login page.
* `login_title`     - The title attribute on the logo link on the login page.
* `login_height`    - Height of the login logo image.
* `login_width`     - Width of the login login logo image. ~320px is recommended. Defaults to 326px.
* `designer_url`    - Used in the credit link on the login and admin pages. Your website!
* `designer_anchor` - Anchor text for the credit link.
* `favicon_url`     - The favicon to be added on the login and admin pages and on the front end.
* `remove_wp`       - Remove the WordPress drop down from the admin menu bar if set to true. The Default is false.
* `remove_gravatar` - Remove Gravatar Icon on WordPress Admin Bar
* `login_style`     - URL to your custom style

## Usage without the theme

You can also use the class as plugin. Create a custom plugin or use the file `custom-admin-branding.php` inside this folder and set the parametes for your requirements.
The example plugin have also a small function to hide the update check for this plugin.

```php
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
```