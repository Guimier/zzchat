<?php

class AddPostAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$channel = Channel::getChannel( $this->getParameter( 'channel' ) ) ;
		
		$content = $this->getParameter( 'content' ) ;
		if ( $content === null )
		{
			throw new WebMissingParameterException( 'content' ) ;
		}
		
		$channel->addPost( Context::getCanonical()->getUser(), $content ) ;
	}
	
}
