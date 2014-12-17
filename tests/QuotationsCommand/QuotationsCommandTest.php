<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for QuotationsCommand. */
class QuotationsCommandTest extends ClassTester
{

/*----- add -----*/

	/** @expectedException CliMissingParameterException */
	public function testAddMissingTestParameter()
	{
		$this->runCommand( 'QuotationsCommand', 'add' ) ;
	}
	
	public function testAddWithAuthor()
	{
		$this->runCommand( 'QuotationsCommand', 'add', '--text=A text', '--author=An author' ) ;
		$this->assertEquals(
			Quotations::$calls,
			array(
				array( 'add', 'text' => 'A text', 'author' => 'An author' )
			)
		) ;
	}
	
	public function testAddWithoutAuthor()
	{
		$this->runCommand( 'QuotationsCommand', 'add', '--text=A text' ) ;
		$this->assertEquals(
			Quotations::$calls,
			array(
				array( 'add', 'text' => 'A text', 'author' => null )
			)
		) ;
	}

/*----- rm -----*/

	/** @expectedException CliMissingParameterException */
	public function testRmMissingTestParameter()
	{
		$this->runCommand( 'QuotationsCommand', 'rm' ) ;
	}

	public function testRm()
	{
		$this->runCommand( 'QuotationsCommand', 'rm', '--id=1' ) ;
		$this->assertEquals(
			Quotations::$calls,
			array(
				array( 'remove', 'id' => 1 )
			)
		) ;
	}

/*----- show -----*/

	public function testShowAll()
	{
		$this->runCommand( 'QuotationsCommand', 'show' ) ;
		$this->expectOutputString(
<<<RAW
0] “I am” — Me
1] “You are” — Anonymous

RAW
		) ;
	}

	public function testShowOne()
	{
		$this->runCommand( 'QuotationsCommand', 'show', '--id=1' ) ;
		
		$this->expectOutputString(
<<<RAW
1] “I am” — Me

RAW
		) ;
		
		$this->assertEquals(
			Quotations::$calls,
			array(
				array( 'get', 'id' => 1 )
			)
		) ;
	}
}
