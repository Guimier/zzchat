<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for QuotationsCommand. */
class ExclusionsCommandTest extends ClassTester
{

/*----- add -----*/

	/** @expectedException CliMissingParameterException */
	public function testAddMissingTestParameter()
	{
		$this->runCommand( 'ExclusionsCommand', 'add' ) ;
	}
	
	public function testAdd()
	{
		$this->runCommand( 'ExclusionsCommand', 'add', '--name=A Name' ) ;
		$this->assertEquals(
			NameExclusions::$calls,
			array(
				array( 'add', 'text' => 'A Name' )
			)
		) ;
	}

/*----- rm -----*/

	/** @expectedException CliMissingParameterException */
	public function testRmMissingTestParameter()
	{
		$this->runCommand( 'ExclusionsCommand', 'rm' ) ;
	}

	public function testRm()
	{
		$this->runCommand( 'ExclusionsCommand', 'rm', '--id=1' ) ;
		$this->assertEquals(
			NameExclusions::$calls,
			array(
				array( 'remove', 'id' => 1 )
			)
		) ;
	}

/*----- show -----*/

	public function testShowAll()
	{
		$this->runCommand( 'ExclusionsCommand', 'show' ) ;
		$this->expectOutputString(
<<<RAW
0] Rodolf
1] a+b

RAW
		) ;
	}

	public function testShowOne()
	{
		$this->runCommand( 'ExclusionsCommand', 'show', '--id=1' ) ;
		
		$this->expectOutputString(
<<<RAW
1] Rodolf

RAW
		) ;
		
		$this->assertEquals(
			NameExclusions::$calls,
			array(
				array( 'get', 'id' => 1 )
			)
		) ;
	}
}
