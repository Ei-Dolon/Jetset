<?php
define( 'WP_CACHE', true );
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
define( 'DB_NAME', 'u184617492_uJbNF' );

/** Database username */
define( 'DB_USER', 'u184617492_pe1oJ' );

/** Database password */
define( 'DB_PASSWORD', 'GKV7JOoGbW' );

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
define( 'AUTH_KEY',          'BO*!*n=aCMX*gan$iB)<Qndz>rsi`1G^M-`^/p$y+MkU<Y*O=Nr0h1OAAgIJd3m(' );
define( 'SECURE_AUTH_KEY',   '_/^5v[,;aRZV`AH`WF}Y5-f:w%^QBXYjLj5J@,rD9JS2(au.[?In2M&I0C=[u[,.' );
define( 'LOGGED_IN_KEY',     'rS$ faSPgPttZKynU#{z=Pd^`dCs?k}CT0zIC}G4mI`Kzu0+8m!M1E-&4O!e3ngk' );
define( 'NONCE_KEY',         'X9#M/NIbzArcX`{Tt7N}>qYo2!UJxd-& #^12K|/?Y)q*9j }]r6^U-{!gDrJLl3' );
define( 'AUTH_SALT',         'G@UV43%_O(+D`=t4S;kHY{|1Ec_:5t-@% qb?}5ME6:$CB}9BL?gVIB9A:69YYE3' );
define( 'SECURE_AUTH_SALT',  '76Q.V:ZY;D&k6JIR(GG6Y3*fy{)gkO;A9l:e4i6@/gf=-oZ,0fH23Y] <Ucd,9e8' );
define( 'LOGGED_IN_SALT',    'v4t9fz^uW;6+m.4MrMylOFQ!D4p%/xkR{0&UnBz<rV7n9}VRTmpSZ5I:[!qn=q| ' );
define( 'NONCE_SALT',        'M|a ~M*.v<AiN{~SeR~Fx9d0,R%>t.u$>#ES8AXCWmIVx{QtG{$b&RhqeRtWVr1T' );
define( 'WP_CACHE_KEY_SALT', 'EMuh%`cjx/,`t2CE;Qu7P`2~*:R7!-z~zb&CO:r<Zu.7tZLaOaE1s[75GaQFXGEh' );


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
