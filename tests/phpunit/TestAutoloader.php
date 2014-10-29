<?php
/*
 * Test for class Autoloader.
 */

/* Start the test */
require_once dirname( __DIR__ ) . '/UnitTestHelper.php' ;

/* Test class */
class TestAutoloader extends PHPUnit_Framework_TestCase
{

	private static $helper ;

	/* Build an autoloader object */
	public static function setUpBeforeClass()
	{
		self::$helper = new UnitTestHelper( 'Autoloader', 'back' ) ;
	}

	public function testClassExistanceCheck()
	{
		$autoloader = new Autoloader(
			self::$helper->getRootDir(),
			self::$helper->getDataPath( 'classes.json' )
		);

		$this->assertTrue(
			$autoloader->classExists( 'Existant' ),
			'An existant class must be reported as existant'
		) ;

		$this->assertFalse(
			$autoloader->classExists( 'Inexistant'),
			'An inexistant class must be reported as inexistant'
		) ;
	}

	public function testClassPathBuild()
	{
		$autoloader = new Autoloader(
			self::$helper->getRootDir(),
			self::$helper->getDataPath( 'classes.json' )
		);

		$this->assertEquals(
			dirname( dirname( __DIR__ ) ) . '/some/path.php',
			$autoloader->getClassFullPath( 'Existant' ),
			'Class full path test'
		) ;
	}

}
