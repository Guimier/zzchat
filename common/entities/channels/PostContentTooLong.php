<?php

class NoSuchEntityException extends AgoraUserException
{
	public function __construct( $type, $id )
	{
		parent::__construct(
			"exceptions.$type.nosuchentity",
			array( 'id' => $id )
		);
	}
}
