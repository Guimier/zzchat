<?php
class InactiveChannelException extends AgoraUserException
{
	public function __construct( $channelId )
	{
		parent::__construct( 'exceptions.inactivechannel', array( 'id' => $channelId ) ) ;
	}	
}
