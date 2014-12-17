<?php


class EntityNameAlreadyTakenException extends AgoraUserException
{
	public function __construct( $type, $name )
	{
		parent::__construct( "exceptions.$type.entitynamealredytaken", array( 'name' => $name ) ) ;
	}	
}
