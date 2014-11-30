<?php

class NoSuchChannelException extends Exception
{
	public function __construct( $id )
	{
		parent::__construct( "Unknown channel $id" );
	}
}
