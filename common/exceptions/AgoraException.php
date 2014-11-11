<?php

/** Parent class of all exceptions.
 * Allows catching only our own exceptions.
 */
abstract class AgoraException extends Exception {

	public function __construct( $message, array $arguments = array() )
	{
		parent::__construct( Context::getCanonical()->getMessage( $message, $arguments ) ) ;
	}

}

