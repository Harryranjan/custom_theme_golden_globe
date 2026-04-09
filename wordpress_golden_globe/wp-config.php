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
define( 'DB_NAME', 'golden_globe' );

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
define( 'AUTH_KEY',         '1YRuzW`^s@R4)FgV$mCqZ^`1Ih/r/q`++5kySq-2$8o.z&9aJ+:%qjm,$@.9w?&3' );
define( 'SECURE_AUTH_KEY',  'G<%)(~:Go!7j$RE{^BGB#non)w./w6+pB`QySi):zM#`]ap_%]Y`k|Io&CRH{v4P' );
define( 'LOGGED_IN_KEY',    'Gn/7g2~n^(*^i_4i_c}RkoFTG?)/` 3sO8Nkf.((Vmalp3+ J%Fg[gDcD^=/oHr5' );
define( 'NONCE_KEY',        '@h81bv,3B$tf|IzQb]_/owz@{+nIdR:^^:SvGOI-~(V)r(52DWSUvpgV-M^H.z|[' );
define( 'AUTH_SALT',        '=NmvzFeG]{&Zo!jhf-R%T~A-z(QoFYR}_Sn kl|In%dZC&S`{>XUee1;ksCO$a%F' );
define( 'SECURE_AUTH_SALT', '8:&N}%v*<WA4pHAn{P*q8dgqq9YkXB}fb1MF9@@)ZN<Tq2))b0AV_HE0L828xdJ&' );
define( 'LOGGED_IN_SALT',   'W[IFUMeN%WPONaogw@C/,cAEs=#GiC `>`hi2!Pk}lykgSJuq>{b96ge*/Z<yxLV' );
define( 'NONCE_SALT',       'r6A&_Nw?#XWF-$iV_DwqH[.a>f<lGh+T`Z|eiJ@*L775{hU_7oz*Z>zln6}AzYD.' );

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
