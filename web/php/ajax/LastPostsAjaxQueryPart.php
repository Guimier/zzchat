<?php

class LastPostsAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$channelList = Channel::getChannel( $this->getArrayParameter( 'channelList' ) ) ;
		
		$content = $this->getParameter( 'beginning' ) ;
		if ( $content === null )
		{
			throw new WebMissingParameterException( 'beginning' ) ;
		}
		
		$channel->lastPosts( $content ) ;
	}
	
}
