<?php

class PostAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$user = $this->loggedInOnly() ;
		
		$id = $this->getParameter( 'channel', WebContext::POST ) ;
		if ( $id === null )
		{
			throw new WebMissingParameterException( 'channel' ) ;
		}
		
		$content = $this->getParameter( 'content', WebContext::POST ) ;
		if ( $content === null )
		{
			throw new WebMissingParameterException( 'content' ) ;
		}
		
		$channel = Channel::getById( (int) $id ) ;
		$channel->addPost( $user, $content ) ;
	}
	
}
