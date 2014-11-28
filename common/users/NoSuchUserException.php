<?php

class NoSuchUserException extends Exception
{
	public function __construct( $id )
	{
		parent::__construct( 'exception.nosuchuser', array( 'userid' => $id ) );
	}
}
