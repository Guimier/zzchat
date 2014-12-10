<?php

class ContainsIllegalCharacterException extends AgoraUserException
{
	public function __construct( $name )
	{
		parent::__construct(
			'exceptions.containsillegalcharacter',
			array( 'name' => $name )
		);
	}
}
