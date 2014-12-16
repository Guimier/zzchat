<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for BadCallException. */
class BadCallExceptionTest extends ClassTester
{
	
	/** Test method discovery. */
	public function testMethodDiscovery()
	{
		$except = new BadCallException() ;
		$struct = $except->getMessageStructure() ;

		$this->assertEquals(
			$struct['arguments']['method'],
			__METHOD__,
			'The method shown in BadCallException must be the constructing one.'
		) ;
	
	}
}
