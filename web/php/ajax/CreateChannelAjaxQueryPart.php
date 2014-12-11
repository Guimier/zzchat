<?php

class CreateChannelAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$user = $this->loggedInOnly() ;
	
		$name = $this->getParameter( 'name', WebContext::POST ) ;
		if ( $name === null )
		{
			throw new WebMissingParameterException( 'name' ) ;
		}
		
		$title = $this->getParameter( 'title', WebContext::POST ) ;
		if ( $title === null )
		{
			throw new WebMissingParameterException( 'title' ) ;
		}
		
		$type = $this->getParameter( 'type', WebContext::POST ) ;
		
		$channel = Channel::create( $name, $title, $user, $type ) ;
		
		return $channel->getId() ;
	}
	
}
