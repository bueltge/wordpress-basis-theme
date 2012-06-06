# Custom Admin Branding

A class to allow theme/plugin developers to easily brand the WordPress login and admin screens

## Features

* Change the login page logo
* Change the login page logo link URL and title attribute
* Add a designer credit to the login page and admin footer
* Remove the built in WordPress menu item from the admin menu bar
* Add a favicon to the login, admin and front end of the site

## Usage

```
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
				'remove_wp'       => FALSE
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

