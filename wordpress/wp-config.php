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
define( 'DB_NAME', 'wordpress_db' );

/** Database username */
define( 'DB_USER', 'ebachmann' );

/** Database password */
define( 'DB_PASSWORD', 'NiMaBe!!6103!!' );

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
define( 'AUTH_KEY',         '&a9*xVST^!yw=fX&n]bmvt8QY:Zc#IQdF;3]=zyuHqVeA2&KY#;eA.*cBhn*hf-@' );
define( 'SECURE_AUTH_KEY',  '>#LbJL$J|L*P}K4<ZTZ~nD m:Z<QImRg,b2>W{*DNWcg,i?t(.ai#JnX2WJTz)SN' );
define( 'LOGGED_IN_KEY',    '9f~Q6f6:!&U?uRxJ{7Fr3j%w*MY:]G40xK1BSVtO[8auNz,}~BT:2T7OL+qZQ91)' );
define( 'NONCE_KEY',        'NbXJ#v-Xp^TGiqqQP:D5Q>QG<{k8a?GFN7be*s2dds8EY)*EhicNS@FU`C}>>_90' );
define( 'AUTH_SALT',        '#FgXq^>-G=9vywBl~+I`o+CPZ]vL9;GvaYU5z(`28)o`:W.4<ukTX_iQM?TE$KD0' );
define( 'SECURE_AUTH_SALT', '<>Lx3Set+pj2O;.t~B1`}uPG`C{7&5MENa^+*z.?Svqn$vg6t&l(bvV&2n7~Uc+:' );
define( 'LOGGED_IN_SALT',   '$Oq->;_Wa678aoi0V)@iCukR,qE ,n0?^sd3)p.akT6Kq1cTW.Pi[!7eLh~p?q<f' );
define( 'NONCE_SALT',       'OGKOWcjYMT<l68$FpwbCaeRD}/m)qIGjN9L-JeyVwgY?|}=g~CvN|M))i1<z2@n6' );
define('WP_HOME','https://elias.trainee-dev.cc/CX/wordpress');
define('WP_SITEURL','https://elias.trainee-dev.cc/CX/wordpress');

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
