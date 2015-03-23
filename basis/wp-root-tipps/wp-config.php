<?php
/** 
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'name');

/** MySQL database username */
define('DB_USER', 'user');

/** MySQL database password */
define('DB_PASSWORD', 'password');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', 'utf8_general_ci');

/** meine Erweiterungen */
define('WP_HOME', 'http://examble.com');
define('WP_SITEURL', 'http://examble.com');
define('ENABLE_CACHE', true);    // Cache on
define('WP_MEMORY_LIMIT', '96M');
define('SAVEQUERIES', true);    // Debug Queries
define('WP_POST_REVISIONS', false);    // Versionierung deaktiviert
define('SCRIPT_DEBUG', true);    // When this is defined and set to true the non-minified versions of the Javascripts will be used.
define('CONCATENATE_SCRIPTS', true); // aktiviert 
define('COMPRESS_SCRIPTS', true); // aktiviert compression JS

//putenv('TMPDIR=/www/htdocs/w0079121/wp-content/tmp');
//define('WP_TEMP_DIR', ABSPATH . 'wp-content/tmp');

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link http://api.wordpress.org/secret-key/1.1/ WordPress.org secret-key service}
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'P]HXm3^#b7qt;`JWBdD<fV/x=R21ZZ(BuAdI1N*mI$Dut>R2M#B%tf9l\"U7a/ @#');
define('SECURE_AUTH_KEY', 'x$BruJk%K{euO[xrbD{vlPO#aMI2qt;0)q\\>uAz0DADILZX\"Hif7*%Us>\"Bru8M ');
define('LOGGED_IN_KEY', 'ZV\\3<{DIK35~{I#Ax*Z)=jGT@@DT$/ D/k^oRWa>1&Bc`$nXma~$\\~$ osD0Up9a');
define('NONCE_KEY', ':JP<^u42Tj;s1z1P<H-FjxRaGfVK<BI<ivT0]@|ZSo+DP9jzi2{F9d>l_N9u+PZM');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'my_wp_prefix_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', 'de_DE');

/* That's all, stop editing! Happy blogging. */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
?>
