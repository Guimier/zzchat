<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/**
 * Test for Autoloader.
 * @codeCoverageIgnore
 */
class AutoloaderTest extends ClassTester
{

	/** Test of class existance check. */
	public function testClassExistanceCheck()
	{
		$autoloader = new Autoloader( $this->getTestDataDir(), 'classes.json' );

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
		$autoloader = new Autoloader( $this->getTestDataDir(), 'classes.json' );

		$this->assertEquals(
			$this->getTestDataDir() . '/some/path.php',
			$autoloader->getClassFullPath( 'Existant' ),
			'Class full path test'
		) ;
	}

}
