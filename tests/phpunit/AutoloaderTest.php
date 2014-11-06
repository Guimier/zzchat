<?php

require_once dirname( __DIR__ ) . '/UnitTestHelper.php' ;

/**
 * Test for Autoloader.
 */
class AutoloaderTest extends PHPUnit_Framework_TestCase
{

	/** Unit-test helper for these tests */
	private static $helper ;

	/** Common test initialization.
	 * Creates the helper object.
	 */
	public static function setUpBeforeClass()
	{
		self::$helper = new UnitTestHelper( 'Autoloader' ) ;
	}

	/** Test of class existance check. */
	public function testClassExistanceCheck()
	{
		$autoloader = new Autoloader( self::$helper->getTestDataDir(), 'classes.json' );

		$this->assertTrue(
			$autoloader->classExists( 'Existant' ),
			'An existant class must be reported as existant'
		) ;

		$this->assertFalse(
			$autoloader->classExists( 'Inexistant'),
			'An inexistant class must be reported as inexistant'
		) ;
	}

	/** Test of class path building */
	public function testClassPathBuild()
	{
		$autoloader = new Autoloader( self::$helper->getTestDataDir(), 'classes.json' );

		$this->assertEquals(
			self::$helper->getTestDataDir() . '/some/path.php',
			$autoloader->getClassFullPath( 'Existant' ),
			'Class full path test'
		) ;
	}

}
