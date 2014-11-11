<?php

/* Placeholders for WebParameters. */

/** @codeCoverageIgnore */
class WebParameters
{
	const BOTH = null ;
	
	private $indent;
	
	public function __construct( $indent )
	{
		$this->indent = $indent ;
	}
	
	public function getValue( $type, $more = null ) {
		return 'working,throwing,workingNull,nonexistant' ;
	}
	
	public function getBooleanValue( $type, $more = null ) {
		return $this->indent ;
	}
}

/* Placeholders for WebContext. */

/** @codeCoverageIgnore */
class WebContext
{
	private $indent ;
	
	public function __construct( $indent )
	{
		$this->params = new WebParameters( $indent ) ;
	}
	
	public function getParameters() { return $this->params ; }
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
		return array( 'foo', 'ß', '×' ) ;
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
