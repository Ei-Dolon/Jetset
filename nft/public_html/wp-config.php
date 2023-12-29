<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u184617492_Ybh9v' );

/** Database username */
define( 'DB_USER', 'u184617492_7Dgfc' );

/** Database password */
define( 'DB_PASSWORD', 'Z3kHFsDdJB' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          ',U`)?`pq!_n%^c:l=@u~w( p<&Pk#:r_>6eq>>z3w,bg6g5~B YgkzKD0h|kwR3+' );
define( 'SECURE_AUTH_KEY',   ' X0%(od}9BuH_ETyboyW@X_kKUC$:EeQf2Lds8jMWi6F2YgYJduv0iW+Q_63/VSH' );
define( 'LOGGED_IN_KEY',     'ruCERw-Lz+f#gy@pN!C@AqCB9W7<a-`$>[Fha^0G495.6b4TZpp}M0I%ld3IoyT[' );
define( 'NONCE_KEY',         ',&%Gn;7ID-w6BVh@p}hrwfw!:#`tJKUPrB(|sn9NY<cf|vixe`KU`;-81YX WoQ^' );
define( 'AUTH_SALT',         'D#;M^TQN_257y~Qyisk}josI$( I}0eJpVfP34}G4w3pmB#`L3~eExbaEq#0AA3j' );
define( 'SECURE_AUTH_SALT',  'u&iYXZlr^jlKu^%jFJ 1rRvh(Nk?gs]+hY][DK00V1.Mf>]8pm!G :b;w&Kn0X@n' );
define( 'LOGGED_IN_SALT',    'J(`(1B62}U8-591#isE|GN W,<bf]S<g5:[K:kY>,D6kWtz+rlfC  H.Jho59~2e' );
define( 'NONCE_SALT',        '7ZYJ_,+xgLSdIh9#<D9Q$i]n8KIWWKAGpEc*T;;?*e4TUc$TDm7N;1e$R%T<0N<=' );
define( 'WP_CACHE_KEY_SALT', 'eV+Vd9?EuzCY!/$@-Tj3p45&PBXz|?gPD _X;Jlf+?60?<Pyhi@G(bk6nw:J@?~B' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
