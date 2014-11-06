<?php

/** Exception: bad call of a function.
 * Thrown by a method called with an invalid arguments (bad type).
 */
class BadCallException extends Exception
{

	/** Constructor */
	public function __construct( $method )
	{
		parent::__construct( "$method called with wrong argument(s)" ) ;
	}

}
