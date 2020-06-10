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
define( 'DB_NAME', 'wp_vidipha' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'toor' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'wy>>bw@NPl5b~TQBw{W-Fd];6GkR/8}i0>l.H%zK4vyxLGxVkFe_[>0m7*_N1-e5' );
define( 'SECURE_AUTH_KEY',  'XMg6!Li-56N2C4$DPeF<+aVW(j(kJ{1sU(^`<DkGGtxy>^xt,{ gyS*`UGX,-DsU' );
define( 'LOGGED_IN_KEY',    '~c_T,_0t=aP$q77Qaf1S@bSN?{@G}v0r}Fl<E&#DYn<y;:Vc-]dY1|`tOw)jLp#g' );
define( 'NONCE_KEY',        '=VZlifEe|lrc`GWHC?Ht^Eu?Oxc,{pq`Z@AYz`{FL1uOmprS4r92pkr+y=y=m/S~' );
define( 'AUTH_SALT',        'KN8cj[#EHi<s0Y/YN?,|fp$&jaex.6-*+5V}S*wqb%Gg1FtcBxXo7uLWX6X;Y31z' );
define( 'SECURE_AUTH_SALT', 'G[eh*<3i!4`gsYzxB+wbd>~O&0Gz|_94*Iy~[+]G%U]*&ilg){6[|Q5v=W^D27{M' );
define( 'LOGGED_IN_SALT',   'tycnqywne%weiB:jLOdNJUz?hYAz.ivC^:_R=@~C18z ]{zHrz_|ydML[8iG%N?G' );
define( 'NONCE_SALT',       '%2KsLj]1#(Qfgz[TV~xk[%z GR  C^B.IGW]+D&{/H|IkWt q_67J[_EN+H>-?=*' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', true );
define('FS_METHOD','direct');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

