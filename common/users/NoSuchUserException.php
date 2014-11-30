<?php

class NoSuchUserException extends AgoraUserException
{
	public function __construct( $id )
	{
		parent::__construct(
			'exceptions.nosuchuser',
			array( 'userid' => $id )
		);
	}
}
