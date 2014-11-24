<?php


class ChannelNameAlreadyTakenException extends AgoraUserException
{
	public function __construct( $channelName )
	{
		parent::__construct( 'exceptions.channelnamealredytaken', array( 'channelname' => $channelName) ;
	}	
}
