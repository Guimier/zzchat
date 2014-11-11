<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for WebContext.
 * @codeCoverageIgnore
 */
class WebParametersTest extends ClassTester
{
	
	/** Tests of parameters access for default selection. */
	public function testParametersDefault()
	{
		$context = new WebParameters(
			array( 'get_existant' => 'foo', 'common' => 'foo2' ),
			array( 'post_existant' => 'bar', 'common' => 'bar2' )
		) ;
		
		$this->assertEquals(
			$context->getValue( 'get_existant' ),
			'foo',
			'GET parameter must be returned in default (BOTH) mode'
		) ;
		
		$this->assertEquals(
			$context->getValue( 'post_existant' ),
			'bar',
			'POST parameter must be returned in default (BOTH) mode'
		) ;
		
		$this->assertNull(
			$context->getValue( 'nonexistant' ),
			'Nonexistant parameter must be replaced by null in default (BOTH) mode'
		) ;
		
		$this->assertEquals(
			$context->getValue( 'common' ),
			'bar2',
			'POST must have priority in default (BOTH) mode'
		) ;
	}
	
	/** Tests of parameters access for BOTH selection. */
	public function testParametersBOTH()
	{
		$context = new WebParameters(
			array( 'get_existant' => 'foo', 'common' => 'foo2' ),
			array( 'post_existant' => 'bar', 'common' => 'bar2' )
		) ;
		
		$this->assertEquals(
			$context->getValue( 'get_existant', WebParameters::BOTH ),
			'foo',
			'GET parameter must be returned in BOTH mode'
		) ;
		
		$this->assertEquals(
			$context->getValue( 'post_existant', WebParameters::BOTH ),
			'bar',
			'POST parameter must be returned in BOTH mode'
		) ;
		
		$this->assertNull(
			$context->getValue( 'nonexistant', WebParameters::BOTH ),
			'Nonexistant parameter must be replaced by null in BOTH mode'
		) ;
		
		$this->assertEquals(
			$context->getValue( 'common', WebParameters::BOTH ),
			'bar2',
			'POST must have priority in BOTH mode'
		) ;
	}
	
	/** Tests of parameters access for POST selection. */
	public function testParametersPOST()
	{
		$context = new WebParameters(
			array( 'get_existant' => 'foo' ),
			array( 'post_existant' => 'bar' )
		) ;
		
		$this->assertEquals(
			$context->getValue( 'post_existant', WebParameters::POST ),
			'bar',
			'POST parameter must be returned in POST mode'
		) ;
		
		$this->assertNull(
			$context->getValue( 'nonexistant', WebParameters::POST ),
			'Nonexistant parameter must be replaced by null in POST mode'
		) ;
		
		$this->assertNull(
			$context->getValue( 'get_existant', WebParameters::POST ),
			'GET parameter must be ignored in POST mode'
		) ;
	}
	
}
