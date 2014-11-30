<?php

class WebMissingParameterException extends AgoraUserException
{

	public function __construct( $param )
	{
		parent::__construct( 'exceptions.webmissingparameter', array( 'param' => $param ) ) ;
	}

}
