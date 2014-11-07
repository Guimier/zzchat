<?php

/** Exception: nonexistent configuration parameter access
 * Thrown when trying to access undefined configuration key.
 */
class NoSuchConfigurationKeyException extends Exception
{

	/** Constructor
	 * @param string $key Unknown key causing the exception.
	 */
	public function __construct( $key )
	{
		parent::__construct( "Unknown configuration key $key" ) ;
	}

}
