<?php

class NoSuchEntityException extends AgoraUserException
{
	public function __construct( $id )
	{
		parent::__construct(
			'exceptions.nosuchentity',
			array( 'id' => $id )
		);
	}
}
