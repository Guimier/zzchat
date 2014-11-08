<?php

/* Placeholders for WebContext. */

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

class NonIndentedContext extends WebContext
{
	public function getIndentParameter() { return null ; }
}

class IndentedContext extends WebContext
{
	public function getIndentParameter() { return '' ; }
}

/* Generic exception */

class GenericException extends Exception {}

/* AjaxQueryPart classes */

class WorkingAjaxQueryPart
{
	public function __construct( $prefix, WebContext $context ) { }
	
	public function execute()
	{
		return array( 'foo', 'bar' ) ;
	}
}

class WorkingNullAjaxQueryPart
{
	public function __construct( $prefix, WebContext $context ) { }
	
	public function execute()
	{
		return null ;
	}
}

class ThrowingAjaxQueryPart
{
	public function __construct( $prefix, WebContext $context ) { }
	
	public function execute()
	{
		throw new GenericException( 'baz' ) ;
	}
}
