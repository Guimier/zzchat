<?php


class EntityNameAlreadyTakenException extends AgoraUserException
{
	public function __construct( $name )
	{
		parent::__construct( 'exceptions.entitynamealredytaken', array( 'name' => $name ) ) ;
	}	
}
