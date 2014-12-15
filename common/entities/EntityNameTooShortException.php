<?php

class EntityNameTooShortException extends AgoraUserException
{
	public function __construct( $name )
	{
		parent::__construct( 'exceptions.entitynametooshort', array( 'name' => $name ) );
	}
}
