<?php


class UserNameAlreadyTakenException extends AgoraUserException
{
	public function __construct( $userName )
	{
		parent::__construct( 'exception.usernamealredytaken', array( 'username' => $userName) ;
	}	
}
