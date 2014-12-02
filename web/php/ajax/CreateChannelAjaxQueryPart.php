<?php

class CreateChannelAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$user = $this->loggedInOnly() ;
	
		$content = $this->getParameter( 'name' ) ;
		if ( $content === null )
		{
			throw new WebMissingParameterException( 'name' ) ;
		}
		
		$content = $this->getParameter( 'title' ) ;
		if ( $content === null )
		{
			throw new WebMissingParameterException( 'title' ) ;
		}
		
		$content = $this->getParameter( 'type' ) ;
		
		$channel = Channel::createChannel( $name, $title, $user, $type ) ;
		
		return $channel->getId() ;
	}
	
}
