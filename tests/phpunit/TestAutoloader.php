<?php
/*
 * Test for class Autoloader.
 */

/* Start the test */
require_once dirname( __DIR__ ) . '/UnitTestBase.php' ;

/* Test class */
class TestAutoloader extends PHPUnit_Framework_TestCase
{

	private static $autoloader ;
	private static $tester ;

	/* Build an autoloader object */
	public static function setUpBeforeClass()
	{
		self::$tester = new UnitTestBase( 'Autoloader', 'back' ) ;

		self::$autoloader = new Autoloader(
			self::$tester->getRootDir(),
			self::$tester->getDataPath( 'classes.json' )
		);
	}

	public function testClassExistanceCheck()
	{
		$this->assertTrue(
			self::$autoloader->classExists( 'Existant' ),
			'An existant class must be reported as existant'
		) ;

		$this->assertFalse(
			self::$autoloader->classExists( 'Inexistant'),
			'An inexistant class must be reported as inexistant'
		) ;
	}

	public function testClassPathBuild()
	{
		$this->assertEquals(
			dirname( dirname( __DIR__ ) ) . '/some/path.php',
			self::$autoloader->getClassFullPath( 'Existant' ),
			'Class full path test'
		) ;
	}

}
