<?php

class EntityNameTooLongException extends AgoraUserException
{
	public function __construct( $type, $name )
	{
		parent::__construct( "exceptions.$type.entitynametoolong", array( 'name' => $name ) );
	}
}
