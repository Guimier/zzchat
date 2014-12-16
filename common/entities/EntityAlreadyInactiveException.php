<?php

class EntityAlreadyInactiveException extends AgoraInternalException
{
	public function __construct( $type, $id )
	{
		parent::__construct( "exceptions.$type.entityalreadyinactive", array( 'id' => $id ) );
	}
}
