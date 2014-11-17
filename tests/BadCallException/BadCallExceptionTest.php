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
		$args = $except->getArgs() ;

		$this->assertEquals(
			$args['method'],
			__METHOD__,
			'The method shown in BadCallException must be the constructing one.'
		) ;
	
	}
}
