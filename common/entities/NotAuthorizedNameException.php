<?php

class NotAuthorizedNameException extends AgoraUserException
{
	public function __construct( $name )
	{
		parent::__construct(
			"exceptions.notauthorizedname",
			array( 'name' => $name )
		) ;
	}
}
