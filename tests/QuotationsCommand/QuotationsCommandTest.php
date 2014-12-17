<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for QuotationsCommand. */
class QuotationsCommandTest extends ClassTester
{
	
	/** @expectedException CliMissingParameterException */
	public function testAdditionMissingTestParameter()
	{
		$this->runCommand( 'QuotationsCommand', 'add' ) ;
	}
	
	public function testAdditionWithAuthor()
	{
		$this->runCommand( 'QuotationsCommand', 'add', '--text=A text', '--author=An author' ) ;
		$this->assertEquals(
			Quotations::$calls,
			array(
				array( 'text' => 'A text', 'author' => 'An author' )
			)
		) ;
	}
	
	public function testAdditionWithoutAuthor()
	{
		$this->runCommand( 'QuotationsCommand', 'add', '--text=A text' ) ;
		$this->assertEquals(
			Quotations::$calls,
			array(
				array( 'text' => 'A text', 'author' => null )
			)
		) ;
	}
}
