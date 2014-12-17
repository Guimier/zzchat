<?php
/** Loads what is needed for execution.
 * @file
 */

/* Root directory of Agora. */
$root = dirname( __DIR__ ) ;

/* Classes autoloading. */
require_once "$root/common/Autoloader.php" ;
spl_autoload_register( array(
	new Autoloader( $root, 'common/classes.json' ),
	'load'
) ) ;

Configuration::initiate( $root, 'local' ) ;

if ( php_sapi_name() === 'cli' )
{
	Context::setCanonical( new CliContext( $argv ) ) ;
}
else
{
	session_start() ;
	Context::setCanonical( new WebContext( $_GET, $_POST, $_SESSION ) ) ;
}
