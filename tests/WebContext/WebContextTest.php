<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for WebContext.
 * @codeCoverageIgnore
 */
class WebContextTest extends ClassTester
{
	
	/** Tests of parameters access for GET selection.
	 */
	public function testParametersGET()
	{
		$context = new WebContext(
			$this->getTestDataDir(),
			array( 'get_existant' => 'foo' ),
			array( 'post_existant' => 'bar' )
		) ;
		
		$this->assertEquals(
			$context->getParameter( 'get_existant', WebContext::GET ),
			'foo',
			'GET parameter must be returned in GET mode'
		) ;
		
		$this->assertNull(
			$context->getParameter( 'nonexistant', WebContext::GET ),
			'Nonexistant parameter must be replaced by null in GET mode'
		) ;
		
		$this->assertNull(
			$context->getParameter( 'post_existant', WebContext::GET ),
			'POST parameter must be ignored in GET mode'
		) ;
	}
	
	/** Tests of parameters access for POST selection.
	 */
	public function testParametersPOST()
	{
		$context = new WebContext(
			$this->getTestDataDir(),
			array( 'get_existant' => 'foo' ),
			array( 'post_existant' => 'bar' )
		) ;
		
		$this->assertEquals(
			$context->getParameter( 'post_existant', WebContext::POST ),
			'bar',
			'POST parameter must be returned in POST mode'
		) ;
		
		$this->assertNull(
			$context->getParameter( 'nonexistant', WebContext::POST ),
			'Nonexistant parameter must be replaced by null in POST mode'
		) ;
		
		$this->assertNull(
			$context->getParameter( 'get_existant', WebContext::POST ),
			'GET parameter must be ignored in POST mode'
		) ;
	}
	
	/** Tests of parameters access for BOTH selection.
	 */
	public function testParametersBOTH()
	{
		$context = new WebContext(
			$this->getTestDataDir(),
			array( 'get_existant' => 'foo', 'common' => 'foo2' ),
			array( 'post_existant' => 'bar', 'common' => 'bar2' )
		) ;
		
		$this->assertEquals(
			$context->getParameter( 'get_existant', WebContext::BOTH ),
			'foo',
			'GET parameter must be returned in BOTH mode'
		) ;
		
		$this->assertEquals(
			$context->getParameter( 'post_existant', WebContext::BOTH ),
			'bar',
			'POST parameter must be returned in BOTH mode'
		) ;
		
		$this->assertNull(
			$context->getParameter( 'nonexistant', WebContext::BOTH ),
			'Nonexistant parameter must be replaced by null in BOTH mode'
		) ;
		
		$this->assertEquals(
			$context->getParameter( 'common', WebContext::BOTH ),
			'bar2',
			'POST must have priority in BOTH mode'
		) ;
	}
}
