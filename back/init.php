<?php
/*
 * Load what is needed for execution.
 */

/*
 * Root directory of zzChat.
 */
$path = dirname( __DIR__ );

require_once "$path/back/Autoloader.php";
spl_autoload_register( array(
	new Autoloader( $path, 'back/classes.json' ),
	'load'
) );
