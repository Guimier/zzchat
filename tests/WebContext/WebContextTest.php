<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for WebContext. */
class WebContextTest extends ClassTester
{
	
	/** Tests of parameters access for default selection. */
	public function testParametersDefault()
	{
		$session = array() ;
		$context = new WebContext(
			array( 'get_existant' => 'foo', 'common' => 'foo2' ),
			array( 'post_existant' => 'bar', 'common' => 'bar2' ),
			$session
		) ;
		
		$this->assertEquals(
			$context->getParameter( 'get_existant' ),
			'foo',
			'GET parameter must be returned in default (BOTH) mode'
		) ;
		
		$this->assertEquals(
			$context->getParameter( 'post_existant' ),
			'bar',
			'POST parameter must be returned in default (BOTH) mode'
		) ;
		
		$this->assertNull(
			$context->getParameter( 'nonexistant' ),
			'Nonexistant parameter must be replaced by null in default (BOTH) mode'
		) ;
		
		$this->assertEquals(
			$context->getParameter( 'common' ),
			'bar2',
			'POST must have priority in default (BOTH) mode'
		) ;
	}
	
	/** Tests of parameters access for BOTH selection. */
	public function testParametersBOTH()
	{
		$session = array() ;
		$context = new WebContext(
			array( 'get_existant' => 'foo', 'common' => 'foo2' ),
			array( 'post_existant' => 'bar', 'common' => 'bar2' ),
			$session
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
	
	/** Tests of parameters access for POST selection. */
	public function testParametersPOST()
	{
		$session = array() ;
		$context = new WebContext(
			array( 'get_existant' => 'foo' ),
			array( 'post_existant' => 'bar' ),
			$session
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
	
}
