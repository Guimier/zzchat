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
		
		return array_map(
			function ( $id )
			{
				return AjaxFormater::posts(
					Channel::getById( $id )->lastPosts( $from )
				) ;
			},
			$channels
		) ;
	}
	
}
