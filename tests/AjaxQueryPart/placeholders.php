<?php

/** Placeholder for WebContext.
 * @codeCoverageIgnore
 */
class WebContext
{
	function getParameter( $key, $more )
	{
		switch ( $key )
		{
			case 'foo_bar': return 'baz' ; break ;
			default: return '_default_' ;
		}
	}
}

ClassTester::load( 'AjaxQueryPart' ) ;

/** Generic child class of AjaxQueryPart
 * @codeCoverageIgnore
 */
class _AjaxQueryPart extends AjaxQueryPart
{
	public function execute() {}
	
	/* Access to protected method getParameter. */
	public function _getParameter( $name, $more )
	{
		return $this->getParameter( $name, $more ) ;
	}
}

