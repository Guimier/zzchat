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
}
