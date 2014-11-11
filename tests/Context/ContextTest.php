<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/**
 * Test for Context.
 * We may want to test shortcuts here in the future.
 * @codeCoverageIgnore
 */
class ContextTest extends ClassTester
{
	
	/** Checks returned values are the given ones. */
	public function testConsistency()
	{
		$conf = new Configuration() ;
		$params = new Parameters() ;
		
		$context = new _Context( $conf, $params ) ;
		
		$this->assertEquals(
			$context->getConfiguration(),
			$conf,
			'Returned configuration must be the one given at construction'
		) ;
		
		$this->assertEquals(
			$context->getParameters(),
			new Parameters(),
			'Returned Parameters object must be the one given at construction'
		) ;
	}
}


