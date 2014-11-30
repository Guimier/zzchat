<?php

class NoSuchChannelException extends AgoraUserException
{
	public function __construct( $id )
	{
		parent::__construct( 'exceptions.nosuchchannel', array( 'id' => $id ) );
	}
}
