<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/**
 * Test for BadCallException.
 * @codeCoverageIgnore
 */
class BadCallExceptionTest extends ClassTester
{
	
	/** Test method discovery. */
	public function testMethodDiscovery()
	{
		$except = new BadCallException() ;
	
		$this->assertEquals(
			$except->getArgs()['method'],
			__METHOD__,
			'The method shown in BadCallException must be the constructing one.'
		) ;
	
	}
}
