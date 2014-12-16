<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for AjaxQueryPart. */
class AjaxQueryPartTest extends ClassTester
{
	
	/** Test parameter access. */
	public function testParameterAccess()
	{
		$queryPart = new _AjaxQueryPart( 'foo', new WebContext() ) ;
	
		$this->assertEquals(
			$queryPart->_getParameter( 'bar', null ),
			'baz',
			'Parameter access must be correctly prefixed.'
		) ;
	
	}
}
