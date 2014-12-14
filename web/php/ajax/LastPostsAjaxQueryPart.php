<?php

class LastPostsAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$channels = $this->getArrayParameter( 'channels' ) ;
		
		$from = $this->getParameter( 'from' ) ;
		if ( $from === null )
		{
			throw new WebMissingParameterException( 'from' ) ;
		}
		
		$res = array() ;
		
		foreach ( $channels as $id )
		{
			$res[$id] = AjaxFormater::posts(
				Channel::getById( $id )->lastPosts( $from )
			) ;
		}
		
		return $res ;
	}
	
}
