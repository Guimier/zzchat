<?php

class EntityAlreadyInactiveException extends AgoraInternalException
{
	public function __construct( $id )
	{
		parent::__construct( 'exceptions.entityalreadyinactive', array( 'id' => $id ) );
	}
}
