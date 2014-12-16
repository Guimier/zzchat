<?php
// @codeCoverageIgnoreStart

/* Placeholders for WebContext. */

class WebContext
{
	const BOTH = null ;
	
	private $indent ;
	
	public function __construct( $indent )
	{
		$this->indent = $indent ;
	}
	
	public function getArrayParameter( $type, $more = null ) {
		return array( 'working', 'workingNull', 'workingEmpty', 'throwingUser', 'throwingInternal', 'nonexistant' ) ;
	}
	
	public function getBooleanParameter( $type, $more = null ) {
		return $this->indent ;
	}
}

/* Generic exceptions. */

class GenericException extends Exception {}
class GenericAgoraUserException extends AgoraUserException {}

/* AjaxQueryPart classes */

class WorkingAjaxQueryPart
{
	public function __construct( $prefix, WebContext $context ) { }
	
	public function execute()
	{
		return array( 'foo', 'ß', '×' ) ;
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

class WorkingEmptyAjaxQueryPart
{
	public function __construct( $prefix, WebContext $context ) { }
	
	public function execute()
	{
		return array() ;
	}
}

class ThrowingUserAjaxQueryPart
{
	public function __construct( $prefix, WebContext $context ) { }
	
	public function execute()
	{
		throw new GenericAgoraUserException( 'baz' ) ;
	}
}

class ThrowingInternalAjaxQueryPart
{
	public function __construct( $prefix, WebContext $context ) { }
	
	public function execute()
	{
		throw new GenericException( 'baz' ) ;
	}
}
