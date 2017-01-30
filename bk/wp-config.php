<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_ad_theme');

/** MySQL database username */
define('DB_USER', 'homestead');

/** MySQL database password */
define('DB_PASSWORD', 'secret');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'c~`C3R XATU+V5<U[6+&g;J|g&42Ia4WX;2)GDXPnUZ{UF&l@~GE8W5`,&X(rhvF');
define('SECURE_AUTH_KEY',  'E?8}J$B^T4,B-x;$R9ruN kL]vc/uY%rEwZyFzMgm^BtYvb%J}3I,WQjCtmU<AH=');
define('LOGGED_IN_KEY',    '_#86P|js+27S[N^!W)K.X^5MO=z*i*H?}G%5jMiZl0Yd>=K/b&dT-59p5b0WjS.f');
define('NONCE_KEY',        'm4a60xQU`JM+;wsi6&:&|wtzUkU8@h6/mJsBeEARptUAa.Cgu{/9A:f#:9$UCXNF');
define('AUTH_SALT',        '|a sTlseY@pR..+:Zvyqg-T`8u6G]U?5kk,!37]14)NoU#>)u-`ztPH~?~D}CRd$');
define('SECURE_AUTH_SALT', 'N{A8f|;FGNj1Sj<EXnG[k>e b/L>:jc[-zSG8wLD#_d=^H,d-M-X?E!u+Buupq00');
define('LOGGED_IN_SALT',   'G3Dlt6H|Meyrh`VGDb]jlIsYVJt6mWVkjgaI|&~cpZyqWvi*o{3iFpqS[|u,kJ>:');
define('NONCE_SALT',       '#?FbhrB<G.&PAvC2Dw~}dLZ~N.=/86Y,NX<`]#,J?gVJaSj>$m-Y:{Tgj(t%A.Hm');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'adscr_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
set_time_limit(0);
require_once(ABSPATH . 'wp-settings.php');

define('DOMAIN', 'http://themesdownload.online.local.vn/');
/** set config ads */
$type_ads_api = array(
	'adf_ly'	=> array(
		'url'	=> 'http://api.adf.ly/api.php?',
		'account'	=> array(
				array(
					'key' 			=> 'd418dd3c1236b1b0f1f505061ec92543',
				    'uid' 			=> '9921033',
				    'domain'		=> DOMAIN
			    )
			)
		)
	);
