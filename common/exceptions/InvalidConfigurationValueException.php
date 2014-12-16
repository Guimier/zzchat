<?php

/** Exception: trying to give the bad type. */
class InvalidConfigurationValueException extends AgoraInternalException
{

	/** Constructor
	 * @param string $key Unknown key causing the exception.
	 */
	public function __construct( $key, $expected, $given )
	{
		parent::__construct( 'exceptions.invalidconfigurationvalue', array(
			'key' => $key,
			'expected' => $expected,
			'given' => $given
		) ) ;
	}

}
