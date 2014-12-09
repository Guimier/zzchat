<?php
/** Loads what is needed for execution.
 * @file
 */

/* Root directory of zzChat. */
$root = dirname( __DIR__ ) ;

/* Classes autoloading. */
require_once "$root/common/Autoloader.php" ;
spl_autoload_register( array(
	new Autoloader( $root, 'common/classes.json' ),
	'load'
) ) ;

Configuration::initiate(
	$root,
	'default/configuration.json',
	'local/configuration.json'
) ;

Context::setCanonical( php_sapi_name() == 'cli'
	? new CliContext( $argv )
	: new WebContext( $_GET, $_POST )
) ;

