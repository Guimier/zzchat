<?php

class UserNameTooShortException extends AgoraInternalException
{
	public function __construct( $username )
	{
		parent::__construct( 'exceptions.usernametooshirt', array( 'username' => $username ) );
	}
}
