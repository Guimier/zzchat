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

$configuration = new Configuration(
	$root,
	'default/configuration.json',
	'local/configuration.json'
) ;
