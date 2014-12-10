<?php

class CreateChannelAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$user = $this->loggedInOnly() ;
	
		$name = $this->getParameter( 'name' ) ;
		if ( $name === null )
		{
			throw new WebMissingParameterException( 'name' ) ;
		}
		
		$title = $this->getParameter( 'title' ) ;
		if ( $title === null )
		{
			throw new WebMissingParameterException( 'title' ) ;
		}
		
		$type = $this->getParameter( 'type' ) ;
		
		$channel = Channel::createChannel( $name, $title, $user, $type ) ;
		
		return $channel->getId() ;
	}
	
}
