<?php

class EntityNameTooShortException extends AgoraInternalException
{
	public function __construct( $name )
	{
		parent::__construct( 'exceptions.entitynametooshort', array( 'name' => $name ) );
	}
}
