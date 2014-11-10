<?php

/** Exception: bad call of a function.
 * Thrown by a method called with an invalid arguments (bad type).
 * 
 * Typical use will be:
 * @code
 *   throw new BadCallException( __METHOD__ ) ;
 * @endcode
 */
class BadCallException extends Exception
{

	/** Constructor
	 * @param string $method Name of the method throwing the exception.
	 */
	public function __construct( $method )
	{
		parent::__construct( "$method called with wrong argument(s)" ) ;
	}

}
