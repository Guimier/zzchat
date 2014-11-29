<?php

class NoSuchUserException extends AgoraUserException
{
	public function __construct( $id )
	{
		parent::__construct(
			'exception.nosuchuser',
			array( 'userid' => $id )
		);
	}
}
