<?php

/** Parent class of all exceptions.
 * Allows catching only our own exceptions.
 *
 * @codeCoverageIgnore Too simple
 */
abstract class AgoraException extends Exception
{

	/** Original message structure. */
	private $messageStructure ;

	public function __construct( $message, array $arguments = array() )
	{
		$this->messageStructure = array(
			'message' => $message,
			'arguments' => $arguments
		) ;
		
		parent::__construct( Context::getCanonical()->getMessage( $message, $arguments ) ) ;
	}

	/** Get the original message structure. */
	public function getMessageStructure()
	{
		return $this->messageStructure ;
	}

}

