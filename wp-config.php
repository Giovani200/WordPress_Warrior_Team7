<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'gite_mon_plaisir' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ',bvfv+]%/GB/?w@4IS87{j!*#>5>Pw%T[KC1xUcI>Ou@wu e^-;,hZ&eQNl}r!@&' );
define( 'SECURE_AUTH_KEY',  ' NeCA&j7#),o#qJVWc;+Dq]O4*dLQD0N#{L3oI.kTfr@SRU^@0oYDq,UR~P^eKfs' );
define( 'LOGGED_IN_KEY',    'nrbHUI]/4zR-dnj0bWp1H~N4]C%M}GBEqSp;W3h406!OCHxc)pP|:^66?/ChNGLJ' );
define( 'NONCE_KEY',        's--A?53Gf7XGjt[.tdA6d60#ZU~/<.Pv>G-&QW5;0^3dNn<HQ8OP]m$_=wcsI~D:' );
define( 'AUTH_SALT',        'iw?bol*8`>mAM:h_G{-N$#OP,s;;^Gyn9n|sT32vf{EdK24z2y*iJ@6~va{5?11<' );
define( 'SECURE_AUTH_SALT', '&agZUe)=|]+#5k3QeKeF4|z.eOpjD%K}CmEK:w^!m$i(9@(qRb0bz]:wr>D[E),z' );
define( 'LOGGED_IN_SALT',   'KbdFf2nrpI`)I[`${V2`j9Ht`[#D*}aeXQV2&UVq|b(Cp>vL*Mc%!c;o~FD~cYwN' );
define( 'NONCE_SALT',       'yp||pB>70z;s&KQc{y<=^] lZj+$Cwz*XsYzcLu9&7leyRo_LP(B)`(P3qYioV@&' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
