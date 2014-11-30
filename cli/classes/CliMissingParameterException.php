<?php

class CliMissingParameterException extends AgoraUserException
{

	public function __construct( $param )
	{
		parent::__construct( 'exception.climissingparameter', array( 'param' => $param ) ) ;
	}

}
