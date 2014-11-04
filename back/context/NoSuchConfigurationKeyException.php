<?php

class NoSuchConfigurationKeyException extends Exception
{

	public function __construct( $key )
	{
		parent::__construct( "Unknown configuration key $key" ) ;
	}

}
