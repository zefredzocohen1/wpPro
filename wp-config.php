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
define('AUTH_KEY',         'osj0DR/:0ruq1JH(e_;f;c%e$/`ovX%j}f^q,}0[EnIh2#bAsGcc0~;(p&WWJvmy');
define('SECURE_AUTH_KEY',  'zo4dD*4=EY~mWve#pOMFu<.W1Vdq(*[Gu0%dsLZ/^Y5y(18DmN#I[>r?50oZ,M0F');
define('LOGGED_IN_KEY',    'jt7~(v# =}Ui.wJG-fCAugB%c68o0Kz[bT|hoSE#JOgv,{Jd(V/Q=<;K (ghZDt!');
define('NONCE_KEY',        'OAw.+t{9cLz#-~p<wo>kB6k#nIy>lkf%bu()`@4!%pM[4G`^2IihKde{RR}TF+-G');
define('AUTH_SALT',        '7 1yu~m;](&_GLP4>uqN^n]hc,82%,0H&1]Nya+G<JZ:kBMDo9/]WU8]s#V*JL/-');
define('SECURE_AUTH_SALT', '](W^*64<x{F!=>99f}GeMVe;~r?~07`AEbaBo-j_x$I^EN}bz8i,^2:*aG6R#/-,');
define('LOGGED_IN_SALT',   '?[mp;ETGXeYX5=C#znUNNV/wsn,?W<XsDf@DuKKioW_Y]9aQ>enGvdLT9*P%.rGl');
define('NONCE_SALT',       'G}klk~AO%FcpI-sizM8wpMtBl,:-q^TKPO,L[v4 3S{4Y/,,p%b0&Pm:}Ow30Sr?');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'h2h_';

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
require_once(ABSPATH . 'wp-settings.php');
