<?php
/**
 * Grunnkonfigurasjonen til WordPress.
 *
 * Denne filen inneholder følgende konfigurasjoner: MySQL-innstillinger, tabellprefiks,
 * hemmelige nøkler, WordPress-språk og ABSPATH. Du kan finne mer informasjon
 * ved å besøke {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex-siden. Du kan få MySQL-innstillingene fra din nettjener.
 * 
 * Denne filen brukes av koden som lager wp-config.php i løpet av
 * installasjonen. Du trenger ikke å bruke nettstedet til å gjøre det, du trenger bare
 * å kopiere denne filen til "wp-config.php" og fylle inn verdiene.
 *
 * @package WordPress
 */

// ** MySQL-innstillinger - Dette får du fra din nettjener ** //
/** Navnet på WordPress-databasen */
define('DB_NAME', 'database_name_here');

/** MySQL-databasens brukernavn */
define('DB_USER', 'username_here');

/** MySQL-databasens passord */
define('DB_PASSWORD', 'password_here');

/** MySQL-tjener */
define('DB_HOST', 'localhost');

/** Tegnsettet som skal brukes i databasen for å lage tabeller. */
define('DB_CHARSET', 'utf8');

/** Databasens "Collate"-type. La denne være hvis du er i tvil. */
define('DB_COLLATE', '');

/**#@+
 * Autentiseringsnøkler og salter.
 *
 * Endre disse til unike nøkler!
 * Du kan generere nøkler med {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Du kan når som helst endre disse nøklene for å gjøre aktive cookies ugyldige. Dette vil tvinge alle brukere å logge inn igjen.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'angi din unike nøkkel her');
define('SECURE_AUTH_KEY',  'angi din unike nøkkel her');
define('LOGGED_IN_KEY',    'angi din unike nøkkel her');
define('NONCE_KEY',        'angi din unike nøkkel her');
define('AUTH_SALT',        'angi din unike nøkkel her');
define('SECURE_AUTH_SALT', 'angi din unike nøkkel her');
define('LOGGED_IN_SALT',   'angi din unike nøkkel her');
define('NONCE_SALT',       'angi din unike nøkkel her');

/**#@-*/

/**
 * WordPress-databasens tabellprefiks.
 *
 * Du kan ha flere installasjoner i en databasehvis du gir dem hver deres unike
 * prefiks. Kun tall, bokstaver og understrek (_), takk!
 */
$table_prefix  = 'wp_';

/**
 * WordPress-språk, forhåndsinnstilt til norsk (bokmål).
 *
 * Du kan endre denne linjen for å bruke WordPress på et annet språk. En tilsvarende MO-fil for
 * det valgte språket må installeres i wp-content/languages. For eksempel, installer
 * de.mo i wp-content/languages og sett WPLANG til 'de' for å aktivere språkstøtte
 * på tysk.
 */
define('WPLANG', 'nb_NO');

/**
 * For utviklere: WordPress-feilsøkingstilstand.
 *
 * Sett denne til "true" for å aktivere visning av meldinger under utvikling.
 * Det er sterkt anbefalt at innstikks- og tema-utviklere bruker WP_DEBUG
 * i deres utviklermiljøer.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
