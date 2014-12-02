<?php

class PostAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$user = $this->loggedInOnly() ;
		
		$channel = Channel::getChannel( $this->getParameter( 'channel' ) ) ;
		
		$content = $this->getParameter( 'content' ) ;
		if ( $content === null )
		{
			throw new WebMissingParameterException( 'content' ) ;
		}
		
		$channel->addPost( $user, $content ) ;
	}
	
}
