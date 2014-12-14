<?php

class EntityNameTooLongException extends AgoraInternalException
{
	public function __construct( $name )
	{
		parent::__construct( 'exceptions.entitynametoolong', array( 'name' => $name ) );
	}
}
