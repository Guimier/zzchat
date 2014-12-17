<?php

class CliMissingParameterException extends AgoraUserException
{

	public function __construct( $param )
	{
		parent::__construct( 'exceptions.climissingparameter', array( 'param' => $param ) ) ;
	}

}
