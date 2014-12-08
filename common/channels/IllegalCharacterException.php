<?php


class IllegalCharacterException extends AgoraUserException
{
	public function __construct( $channelName )
	{
		parent::__construct( 'exceptions.illegalcharacter', array( 'channelname' => $channelName ) ) ;
	}	
}
