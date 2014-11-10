<?php
/** Loads what is needed for execution.
 * @file
 */

/* Root directory of zzChat. */
$root = dirname( __DIR__ ) ;

/* Classes autoloading. */
require_once "$root/back/Autoloader.php" ;
spl_autoload_register( array(
	new Autoloader( $root, 'back/classes.json' ),
	'load'
) ) ;
