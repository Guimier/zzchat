<?php

class ChannelAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$channels = array() ;
	
		$names = $this->getArrayParameter( 'name' ) ;
		if ( count( $names ) === 0 )
		{
			$ids = $this->getArrayParameter( 'id' ) ;
			
			foreach ( $ids as $id )
			{
				$channels[$id] = Channel::getById( $id ) ;
			}
		}
		else
		{
			foreach ( $names as $name )
			{
				$channel = Channel::getByName( $name ) ;
				$channels[$channel->getId()] = $channel ;
			}
		}
		
		$chanInfo = array() ;
		
		foreach ( $channels as $id => $channel )
		{
			$channel->activatedBy( $this->getContext()->getUser() ) ;
			$chanInfo[$id] = AjaxFormater::channel( $channel ) ;
		}
		
		return $chanInfo ;
	}
	
}
