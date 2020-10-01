<?php
/**

 * The base configuration for WordPress

 *

 * The wp-config.php creation script uses this file during the

 * installation. You don't have to use the web site, you can

 * copy this file to 'wp-config.php' and fill in the values.

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

define('DB_NAME', 'mrg91573_greenlife');




/** MySQL database username */

define('DB_USER', 'mrg91573_luyen');



/** MySQL database password */

define('DB_PASSWORD', 'Mail123@');



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

define('AUTH_KEY',         'Oe/|@u`oP;)zz3YJiFpj}F}j[]0H%]N$#svOvK/=Sqlt!csp|A)X_7Ogh.p=,g^z');

define('SECURE_AUTH_KEY',  'nDU/_D&+{XR*|;Ux5ejx<W[8gV4!LNym*DVdkt^Fl( @MoMFs<+hPAx +Z');

define('LOGGED_IN_KEY',    '/#HBbhR8rn&,AZjCW%YU-jz*`0U8Ck}kh:#mGPeDYG)`0!a_bMJ]|<m j@A$!Zh|');

define('NONCE_KEY',        '<)8|YZ[,`;O%hM+Lz03KZ:}@/)%f)miC>J%i?r>vbAe F^[@L.2AyM!s&i#`V');

define('AUTH_SALT',        'j*q|Xak{(eb#L?c+[Naf]2:-oK_.J#eGtF=VT=Tr/4P~Xmno4rnuo%;=gtqT6Hfl');

define('SECURE_AUTH_SALT', 'tWQO*A&|Gay6V_V_kBQ(|]YirOs77aV;5gut>pnxUag2FD6}oLH$)k?n3');

define('LOGGED_IN_SALT',   'L:hl?f9gBWQ?S;r<XQJT9NKog5]5WLQA}CdR0/10!P@-| sc4f{v+wxh:hObr6#E');

define('NONCE_SALT',       ' {*75iXH=lm bV+K&`L$0fG|h]OMlppT9=FGl#O.@zN#sI_0QGt_>NGsB-k,MA5g');



/**#@-*/



/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix  = 'bz_';



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

define('WP_DEBUG', false);



/* That's all, stop editing! Happy blogging. */



/** Absolute path to the WordPress directory. */

if ( !defined('ABSPATH') )

	define('ABSPATH', dirname(__FILE__) . '/');



/** Sets up WordPress vars and included files. */

require_once(ABSPATH . 'wp-settings.php');


