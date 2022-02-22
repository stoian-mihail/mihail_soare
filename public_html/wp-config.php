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
define('DB_NAME', 'r46406mi_wp549');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'qlsrfgvxpzdyd1ig1uq2kytok12ci6ozhqfoqjlumydggc5vbmef2redhtrzbupm');
define('SECURE_AUTH_KEY',  'a8kpsbmxprennojguldoio2eeq7d7zqehsr0gsuwky537uqlqulmgez7icsmucrf');
define('LOGGED_IN_KEY',    'aahponqrglvzn0wfglnvr9zwxqodylioixkgtxfvbaxtyybpnktzby8mu9eiumdr');
define('NONCE_KEY',        'dzbybqnz49agxdo7b2hzrha8jfcw02l1pev7gwe1eocpk4lirrm8dpwowgep8l6n');
define('AUTH_SALT',        'qusxvyizbz3lreqqyzg2d40asxfsex0cxvaefqebrlpra5iezjrtymbfh4k2qp25');
define('SECURE_AUTH_SALT', '9eyruzuomi9mrhgqya4tzig5n5wcazo0ymy4x6cp9lpgitfgb8ovdoymhk5dookl');
define('LOGGED_IN_SALT',   'b2tvldudwibgxya1v5n3wwlckaxed8ezegnneuqxwh7prlhgj8jb20qyqehz7len');
define('NONCE_SALT',       'whm2gfakastd3la7qyc7prslqgtblwtrtc3uhoyvows0mm4tykdvkzo4igcghwqz');

define('WP_HOME', 'http://localhost');
define('WP_SITEURL', 'http://localhost');
define('WP_CACHE', false);
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpqf_';

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

ini_set('display_errors', 'Off');
ini_set('error_reporting', E_ALL);
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', false);
ini_set('log_errors', 'On');
ini_set('error_log', '/home/r46406miha/public_html/logs/php-errors.log');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH'))
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
