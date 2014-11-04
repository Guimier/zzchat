<?php

class BadCallException extends Exception
{

	public function __construct( $method )
	{
		parent::__construct( "$method called with wrong argument(s)" ) ;
	}

}
