<?php

/* Placeholders for WebContext. */

/** @codeCoverageIgnore */
abstract class WebContext
{
	const BOTH = null ;
	abstract public function getIndentParameter() ;
	
	public function getParameter( $type, $more ) {
		switch ( $type )
		{
			case 'indent': return $this->getIndentParameter() ;
			case 'query': return 'working,throwing,workingNull,nonexistant' ;
			default: return '_default_' ;
		}
	}
}

/** @codeCoverageIgnore */
class NonIndentedContext extends WebContext
{
	public function getIndentParameter() { return null ; }
}

/** @codeCoverageIgnore */
class IndentedContext extends WebContext
{
	public function getIndentParameter() { return '' ; }
}

/* Generic exception */

/** @codeCoverageIgnore */
class GenericException extends Exception {}

/* AjaxQueryPart classes */

/** @codeCoverageIgnore */
class WorkingAjaxQueryPart
{
	public function __construct( $prefix, WebContext $context ) { }
	
	public function execute()
	{
		return array( 'foo', 'bar' ) ;
	}
}

/** @codeCoverageIgnore */
class WorkingNullAjaxQueryPart
{
	public function __construct( $prefix, WebContext $context ) { }
	
	public function execute()
	{
		return null ;
	}
}

/** @codeCoverageIgnore */
class ThrowingAjaxQueryPart
{
	public function __construct( $prefix, WebContext $context ) { }
	
	public function execute()
	{
		throw new GenericException( 'baz' ) ;
	}
}
