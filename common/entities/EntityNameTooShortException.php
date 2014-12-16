<?php

class EntityNameTooShortException extends AgoraUserException
{
	public function __construct( $type, $name )
	{
		parent::__construct( "exceptions.$type.entitynametooshort", array( 'name' => $name ) );
	}
}
