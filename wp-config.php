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
define( 'DB_NAME', 'testwordpress_db' );

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
define( 'AUTH_KEY',         '$( Qk56faoA>}dx{5q^9!43@}bW=]j7_A4?/K|Qe^UhE/Ph=/X7Cs8+@vWOH,t}1' );
define( 'SECURE_AUTH_KEY',  'F^q@#uwyv*MO$Z;{lH.} n-~i7YQF*ta)]_+=0#Jff+=.v.1ttVoTJrv4QGFoJq3' );
define( 'LOGGED_IN_KEY',    '*p6nl/A)3eejV|qyB:?oq&f<4CSJCM%3*~irb?}L=S+5]oe[@VmM|2>LeeT2Cd,Y' );
define( 'NONCE_KEY',        'EM&c;s$v)^.2e@d|}q(<tgUV_7~5,y4B !<[KQ4CS_Iqv45F?CC? X3-g+ef.$R_' );
define( 'AUTH_SALT',        'zQAUECv]gl$D=yM*I*?NirRZQ4T{aEL{#|xw18/nJt+.&j$rDu(&[T?JC|>=u,<c' );
define( 'SECURE_AUTH_SALT', '9L/pz:Fo#QHgKMC@hnnsvmKMTFsn=,JY5K1esb_NXQ|>}+N_&o+BYMyp@p?l oBe' );
define( 'LOGGED_IN_SALT',   'ru7[OS!!__KEX>}Ogo6AsG<lm%.*[>:tbV22--Q{/c@]w-&9UrRd|L1 teyC2URb' );
define( 'NONCE_SALT',       'I}LKf2*XYS2(+r3}[WJ^P~^Cx}n#K|d}?(eSA4];@Uh*mceM)XhADA)`[zrjF(nw' );

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
