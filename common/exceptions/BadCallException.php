<?php

/** Exception: bad call of a function.
 * Thrown by a method called with an invalid arguments (bad type).
 */
class BadCallException extends AgoraInternalException
{

	/** Constructor. */
	public function __construct()
	{
		$last = $this->getTrace()[0] ;
		$method = $last['class'] . '::' . $last['function'] ;
		parent::__construct( 'exceptions.badcall', array( 'method' => $method ) ) ;
	}

}
