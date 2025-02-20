<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
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
define('DB_NAME', '');

/** MySQL database username */
define('DB_USER', '');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         ';!JWqf5gyMGYF0dKA:dJgQWL+7~eyY2%?2cUa9/dBxfrr|1kBq8qJ;Ol&0DdU!y9');
define('SECURE_AUTH_KEY',  'm7SPP+4(l0;OkHXYeQoq~smGpQ)A;9RxNJRcIvqSoQ21i1m9t8qC&lcnqaudZAdV');
define('LOGGED_IN_KEY',    'woFcdcS4oZl5YOp!XDi@Q?LR_0Kp^q6b5IaxNv2$)JA!U%$nA`%#6mG:2bD2bbtL');
define('NONCE_KEY',        'Rznvk?@)*4;G5ziSQ(&+2U2~T`o23s*2fQr*RIcEsuYW&tt@1eBZ7w9Q0K8Bn;b2');
define('AUTH_SALT',        'roh|Qj"S|iRa?JhHDR@!CFEcNmz5uAd%D"ALrT|0M)nOGvW"UPDcfJzs"(1W9mm;');
define('SECURE_AUTH_SALT', '*)ou?rxofIe_|yXc3nFdwQI$H632uzrt_^vgT9SNmM0tZ#!1%yz^AnS#N;6oKImE');
define('LOGGED_IN_SALT',   'u*IHo^N$)FGCAbpW;HsWG7H;m^N0K@8VntKuZIRp1MKRxEDh#u@v!k?FO`mo1S?p');
define('NONCE_SALT',       'g0q?pV%$!;N8S_^u%B!J8lQn^hjWAy5ZM|^I@Ety%RGcbeCAA|WVul`z9rm|b%J:');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = '';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'es_ES');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

