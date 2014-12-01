<?php

class NotLoggedInUserException extends AgoraUserException
{
	public function __construct()
	{
		parent::__construct( 'exceptions.notloggedinuser' );
	}
}
