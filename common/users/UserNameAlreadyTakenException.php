<?php


class UserNameAlreadyTakenException extends AgoraUserException
{
	public function __construct( $userName )
	{
		parent::__construct( 'exceptions.usernamealredytaken', array( 'username' => $userName ) ) ;
	}	
}
