<?php

class PostAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$user = $this->loggedInOnly() ;
		
		$channel = Channel::getChannel( $this->getParameter( 'channel', WebContext::POST ) ) ;
		
		$content = $this->getParameter( 'content', WebContext::POST ) ;
		if ( $content === null )
		{
			throw new WebMissingParameterException( 'content' ) ;
		}
		
		$channel->addPost( $user, $content ) ;
	}
	
}
