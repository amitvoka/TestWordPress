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
define( 'DB_NAME', 'TestWordPress_db' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'gO7FfML$5PB@4qTZ1_-u(X!vL#<saXB53EMN`})tv})A-3KYzN/,P+AS^NE=`sCH' );
define( 'SECURE_AUTH_KEY',  'tn.`Xso- TimP.f:MAw)1R_jig_lXvmKUa*/-3VJ7(S,v0Z:@(3WL}9k%S!4mb/z' );
define( 'LOGGED_IN_KEY',    'fTz%=CA+>J:zE+#>^4z8X:9/@<W(T-P]p6_2]6^faWU{ M=/b^_&0};iw2jyjzx~' );
define( 'NONCE_KEY',        '_S+m*6ot3ivd*WXS(+zVt4RV4o=-!PE8mK)43Z&r<;S9v3.ihKsD.[M<C;[!PI#F' );
define( 'AUTH_SALT',        'C%q9@&I,>&W(o{S|yc}AhM[*t!g$0~{fDklbiPzDcRQ.m0lSELCcxJ,I?L6.?DuJ' );
define( 'SECURE_AUTH_SALT', 'wi}c2ve,iyT. yj6f%J5:iW/%Dkj}+=Wn-%W]o$$Vh~u]t|K![3R<q&c*8!^QKe]' );
define( 'LOGGED_IN_SALT',   '/bv4-NcY}.V|umi_!~ZcMyN1UD;%`s&GPfS}_SQo0yVx%l+S/nl1WlPEwi-PbSP7' );
define( 'NONCE_SALT',       'y@qrBkbuhW?;F||:0R|Fs:kj=W/MV k6~,cF_/I@PZR4@u+Pn#J+g%<-.);8HHHy' );

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
