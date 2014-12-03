<?php

class ChannelAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$channels = array() ;
	
		$names = $this->getArrayParameter( 'name' ) ;
		if ( $names === null )
		{
			$ids = $this->getArrayParameter( 'id' ) ;
			if ( $ids === null )
			{
				throw new WebMissingParameterException( 'id' ) ;
			}
			
			foreach ( $ids as $id )
			{
				$channels[$id] = Channel::getChannel( $id ) ;
			}
		}
		else
		{
			foreach ( $names as $name )
			{
				$channel = Channel::getActiveChannel( $name ) ;
				$channels[$channel->getId()] = $channel ;
			}
		}
		
		$chanInfo = array() ;
		
		foreach ( $channels as $id => $channel )
		{
			$chanInfo[$id] = array(
				'name' => $channel->getName(),
				'title' => $channel->getTitle()
			) ;
		}
		
		return $chanInfo ;
	}
	
}
