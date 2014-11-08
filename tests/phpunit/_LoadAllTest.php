<?php

/** Testing that all inclusions work.
 * This tests ensures all classes load correctly, but its main point
 * is to load all classes in order to have full information about
 * code coverage.
 * @codeCoverageIgnore
 */
class _LoadAllTest extends PHPUnit_Framework_TestCase
{

	public function testAnyInclusion()
	{
		$root = dirname( dirname( __DIR__ ) ) ;

		require_once "$root/back/Autoloader.php" ;
		spl_autoload_register( array(
			new Autoloader( $root, 'back/classes.json' ),
			'load'
		) ) ;

		$classes = array_keys( json_decode(
			file_get_contents( "$root/back/classes.json" ),
			true
		) ) ;

		$correct = true ;

		$i = 0 ;

		while ( $i < count( $classes ) && $correct )
		{
			$correct = class_exists( $classes[$i] ) ;
			$i++ ;
		}

		$this->assertTrue(
			$correct,
			'All classes must exist and load without error.'
		) ;
	}

}
