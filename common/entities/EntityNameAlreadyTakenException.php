<?php


class EntityNameAlreadyTakenException extends AgoraUserException
{
	public function __construct( $this, $name )
	{
		parent::__construct( "exceptions.$type.entitynamealredytaken", array( 'name' => $name ) ) ;
	}	
}
