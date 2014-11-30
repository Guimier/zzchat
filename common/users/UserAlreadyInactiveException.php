<?php

class UserAlreadyInactiveException extends AgoraInternalException
{
	public function __construct( $id )
	{
		parent::__construct( 'exceptions.useralreadyinactive', array( 'userid' => $id ) );
	}
}
