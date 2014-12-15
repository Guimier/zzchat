<?php

class EntityNameTooLongException extends AgoraUserException
{
	public function __construct( $name )
	{
		parent::__construct( 'exceptions.entitynametoolong', array( 'name' => $name ) );
	}
}
